<script setup lang="ts">
import { useRouter } from "vue-router";
import VueApexCharts from "vue3-apexcharts";
import { ref, onMounted } from 'vue';
import axios from 'axios';

const router = useRouter()

// State untuk menyimpan data
const loanData = ref({
  currentLoans: 0,
  dueLoans: 0,
  totalLoans: 0
});

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
      rotateAlways: false
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

// Function untuk mengambil data statistik
const fetchStatistics = async () => {
  try {
    // Mengambil data dari berbagai endpoint
    const [rawData, loanData, lateData] = await Promise.all([
      axios.post('/databaru/raw'),
      axios.post('/databaru/loan'),
      axios.post('/databaru/late')
    ]);

    loanData.value = {
      currentLoans: rawData.data.length || 0,
      dueLoans: lateData.data.length || 0,
      totalLoans: loanData.data.length || 0
    };
  } catch (error) {
    console.error('Error fetching statistics:', error);
  }
};

// Function untuk mengambil data chart
const fetchChartData = async () => {
  try {
    const response = await axios.get('/peminjaman/monthly-stats');
    
    if (response.data.status) {
      const data = response.data.data;
      
      chartOptions.value.series = [{
        name: 'Total Peminjaman',
        data: data.map(item => item.total)
      }];
      
      chartOptions.value.xaxis.categories = data.map(item => item.month);
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

  // Mengubah data menjadi format yang dibutuhkan chart
  const labels = Object.keys(monthlyData);
  const values = Object.values(monthlyData);

  return { labels, values };
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

// Fetch data when component is mounted
onMounted(() => {
  fetchChartData();
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