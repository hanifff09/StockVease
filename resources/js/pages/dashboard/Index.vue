<script setup lang="ts">
import { useRouter } from "vue-router";
import VueApexCharts from "vue3-apexcharts";
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { useTahunStore } from "@/stores/tahun";

const router = useRouter()
const { tahun } = useTahunStore()

// State untuk menyimpan data
const loanData = ref({
  currentLoans: 0,
  dueLoans: 0,
  totalLoans: 0
});

// Array of month names in Bahasa Indonesia
const monthNames = [
  'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
  'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
];

const chartOptions = ref({
  chart: {
    type: 'area',
    height: 350,
    toolbar: {
      show: false
    }
  },
  series: [{
    name: 'Total Peminjaman',
    data: []
  }],
  xaxis: {  
    categories: [],
    labels: {
      style: {
        colors: '#777777'
      },
      rotate: -45,
      rotateAlways: false,
      formatter: function(value) {
        // Handle long month names if needed
        if (value && value.length > 10) {
          const parts = value.split(' ');
          if (parts.length > 1) {
            return parts[0].substring(0, 3) + ' ' + parts[1];
          }
        }
        return value;
      }
    }
  },
  yaxis: {
    labels: {
      style: {
        colors: '#777777'
      },
      formatter: function(value) {
        return Math.floor(value);
      }
    },
    title: {
      text: 'Jumlah Peminjaman'
    }
  },
  colors: ['#009ef7'],
  stroke: {
    curve: 'smooth',
    width: 2 
  },
  fill: {
    type: 'gradient',
    gradient: {
      shadeIntensity: 1,
      opacityFrom: 0.7,
      opacityTo: 0.3,
    }
  },
  dataLabels: {
    enabled: true,
    formatter: function(val) {
      return Math.floor(val);
    }
  },
  tooltip: {
    theme: 'dark',
    y: {
      formatter: function(value) {
        return Math.floor(value) + ' peminjaman';
      }
    }
  }
});

// Helper function to format month names
const formatMonthName = (monthStr) => {
  // If it's already in the format "Month Year", return as is
  if (/[A-Za-z]/.test(monthStr)) return monthStr;
  
  // If it's in "M/YYYY" format (e.g., "1/2023")
  const [month, year] = monthStr.split('/');
  
  // Get the month name (subtract 1 because array is 0-indexed)
  const monthName = monthNames[parseInt(month) - 1];
  
  // Return formatted string
  return `${monthName} ${year}`;
};

// Function untuk mengambil data statistik
const fetchStatistics = async () => {
  try {
    axios.get('/peminjaman/databaru/raw').then(res => loanData.value.currentLoans = res.data),
    axios.get('/peminjaman/databaru/loan').then(res => loanData.value.totalLoans = res.data),
    axios.get('/peminjaman/databaru/late').then(res => loanData.value.dueLoans = res.data)
    // Mengambil data dari berbagai endpoint
    // const [rawData, loanData, lateData] = await Promise.all([
    // ]);

    // loanData.value = {
    //   currentLoans: Array.isArray(rawData.data) ? rawData.data.length : 0,
    //   dueLoans: Array.isArray(lateData.data) ? lateData.data.length : 0,
    //   totalLoans: Array.isArray(loanData.data) ? loanData.data.length : 0
    // };
  } catch (error) {
    console.error('Error fetching statistics:', error);
  }
};  

// Function untuk mengambil data chart
const fetchChartData = async () => {
  try {
    const response = await axios.get(`/peminjaman/monthly-stats?tahun=${tahun}`);
    
    if (response.data.status) {
      const data = response.data.data;
      
      // Format month names properly
      const formattedData = data.map(item => {
        // Format the month name if it's a numeric or standard representation
        const monthDisplay = formatMonthName(item.month);
        
        return {
          ...item,
          displayMonth: monthDisplay
        };
      });
      
      // Sort the data chronologically if needed
      formattedData.sort((a, b) => {
        // Extract year and month for comparison
        const parseMonthYear = (str) => {
          for (let i = 0; i < monthNames.length; i++) {
            if (str.includes(monthNames[i])) {
              const year = str.split(' ')[1];
              return { month: i + 1, year: parseInt(year) };
            }
          }
          return null;
        };
        
        const dateA = parseMonthYear(a.displayMonth);
        const dateB = parseMonthYear(b.displayMonth);
        
        if (dateA && dateB) {
          if (dateA.year !== dateB.year) return dateA.year - dateB.year;
          return dateA.month - dateB.month;
        }
        
        return 0;
      });
      
      chartOptions.value.series = [{
        name: 'Total Peminjaman',
        data: formattedData.map(item => item.total)
      }];
      
      chartOptions.value.xaxis.categories = formattedData.map(item => item.displayMonth);
    }
  } catch (error) {
    console.error('Error fetching chart data:', error);
  }
};

// Function untuk mengolah data peminjaman menjadi format chart
const processLoanDataForChart = (data) => {
  // Mengelompokkan data berdasarkan bulan
  const monthlyData = data.reduce((acc, loan) => {
    const date = new Date(loan.created_at);
    const monthYear = `${date.getMonth() + 1}/${date.getFullYear()}`;

    if (!acc[monthYear]) {
      acc[monthYear] = 0;
    }
    acc[monthYear]++;
    return acc;
  }, {});

  // Mengubah data menjadi format yang dibutuhkan chart dengan sorting
  const sortedEntries = Object.entries(monthlyData)
    .map(([key, value]) => {
      const [month, year] = key.split('/');
      return {
        key,
        value,
        monthNum: parseInt(month),
        year: parseInt(year),
        displayMonth: `${monthNames[parseInt(month) - 1]} ${year}`
      };
    })
    .sort((a, b) => {
      // Sort by year first, then by month
      if (a.year !== b.year) return a.year - b.year;
      return a.monthNum - b.monthNum;
    });

  const labels = sortedEntries.map(entry => entry.displayMonth);
  const values = sortedEntries.map(entry => entry.value);

  return { labels, values };
};

// Function to initialize chart with processed data
const initializeChartWithProcessedData = async () => {
  try {
    const response = await axios.post('/databaru/loan');
    if (response.data && Array.isArray(response.data)) {
      const { labels, values } = processLoanDataForChart(response.data);
      
      chartOptions.value.series = [{
        name: 'Total Peminjaman',
        data: values
      }];
      
      chartOptions.value.xaxis.categories = labels;
    }
  } catch (error) {
    console.error('Error initializing chart with processed data:', error);
  }
};

// Navigation functions
const butt = () => {
  router.push('/dashboard/peminjaman');
}

const but = () => {
  router.push('/dashboard/loan');
}

const bat = () => {
  router.push('/dashboard/late');
}

// const dataAwal = () => {
//   axios.get('/databaru/tes')
// }

// Fetch data when component is mounted
onMounted(() => {
  fetchStatistics();
  fetchChartData();

  
  // dataAwal()
  // If the API doesn't provide properly formatted data, use this instead:
  // initializeChartWithProcessedData();
});
</script>

<template>
  <div class="card mb-10 pb-10">
    <div
      class="card-header rounded bgi-no-repeat bgi-size-cover bgi-position-y-top bgi-position-x-center align-items-start h-200px bg-primary">
      <h3 class="card-title align-items-start flex-column text-white pt-10">
        <span class="fw-bold fs-2x cursor">StockVease</span>
      </h3>
    </div>
    <div class="card-body mt-n20">
      <div class="mt-n20 position-relative">


        <!-- Cards Section -->
        <div class="row g-3 g-lg-6">
          <div class="col-xl-4 col-sm-6">
            <div class="bg-gray-100 bg-opacity-70 rounded-2 px-6 py-5 border-success border-left-5 border-start d-block"
              @click="but">
              <div class="symbol symbol-30px me-5 mb-8">
                <span class="symbol-label">
                  <i class="ki-duotone ki-scooter fs-2x text-success">
                    <span class="path1"></span>
                    <span class="path2"></span>
                    <span class="path3"></span>
                    <span class="path4"></span>
                    <span class="path5"></span>
                    <span class="path6"></span>
                    <span class="path7"></span>
                  </i>
                </span>
              </div>
              <div class="m-0 d-flex justify-content-between align-items-center">
                <div>
                  <span class="text-success fw-bolder d-block fs-2qx lh-1 ls-n1 mb-1 cursor">
                    {{ loanData.currentLoans }}
                  </span>
                  <span class="text-gray-500 fw-semibold fs-6 cursor">Barang yang sedang dipinjam</span>
                </div>
              </div>
            </div>
          </div>

          <div class="col-xl-4 col-sm-6">
            <div class="bg-gray-100 bg-opacity-70 rounded-2 px-6 py-5 border-warning border-left-5 border-start d-block"
              @click="bat">
              <div class="symbol symbol-30px me-5 mb-8">
                <span class="symbol-label">
                  <i class="ki-duotone ki-time fs-2x text-warning">
                    <span class="path1"></span>
                    <span class="path2"></span>
                  </i>
                </span>
              </div>
              <div class="m-0 d-flex justify-content-between align-items-center">
                <div>
                  <span class="text-warning fw-bolder cursor d-block fs-2qx lh-1 ls-n1 mb-1">
                    {{ loanData.dueLoans }}
                  </span>
                  <span class="text-gray-500 fw-semibold cursor fs-6">Barang yang perlu dikembalikan</span>
                </div>
              </div>
            </div>
          </div>

          <div class="col-xl-4 col-sm-6">
            <div class="bg-gray-100 bg-opacity-70 rounded-2 px-6 py-5 border-info border-left-5 border-start d-block"
              @click="butt">
              <div class="symbol symbol-30px me-5 mb-8">
                <span class="symbol-label">
                  <i class="ki-duotone ki-user fs-2x text-info">
                    <span class="path1"></span>
                    <span class="path2"></span>
                  </i>
                </span>
              </div>
              <div class="m-0 d-flex justify-content-between align-items-center">
                <div>
                  <span class="text-info fw-bolder cursor d-block fs-2qx lh-1 ls-n1 mb-1">
                    {{ loanData.totalLoans }}
                  </span>
                  <span class="text-gray-500 fw-semibold cursor fs-6">Seluruh rekapan data peminjaman</span>
                </div>
              </div>
            </div>
          </div>

          <!-- Chart Section -->
          <div class="bg-gray-100 rounded p-6 mt-10">
            <h4 class="my-4">Statistik Peminjaman</h4>
            <apexchart height="350" :options="chartOptions" :series="chartOptions.series"></apexchart>
          </div>
            
        </div>
      </div>
    </div>
  </div>
</template>