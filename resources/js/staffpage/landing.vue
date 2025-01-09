<template>
  <div class="container">
      <!-- Sidebar -->
      <div class="sidebar">
          <div class="logo">StockVease</div>
          <div class="nav-item active">
              <i class="fas fa-home"></i>
              Dashboard
          </div>
          <div class="nav-item">
              <i class="fas fa-box"></i>
              Daftar Barang
          </div>
          <div class="nav-item">
              <i class="fas fa-history"></i>
              Riwayat Peminjaman
          </div>
          <div class="nav-item">
              <i class="fas fa-user"></i>
              Profil
          </div>
          <div class="nav-item logout" @click="logout">
            <i class="fas fa-sign-out-alt"></i>
            Logout
        </div>
      </div>

      <!-- Main Content -->
      <div class="main-content">
          <!-- Header -->
          <div class="header">
              <h1>Dashboard Staff</h1>
              <div class="user-info">
                  <div class="user-avatar">JS</div>
                  <div>John Smith</div>
              </div>
          </div>

          <!-- Statistics -->
          <div class="stats-container">
              <div class="stat-card">
                  <h3>Barang Dipinjam</h3>
                  <div class="value">5</div>
              </div>
              <div class="stat-card">
                  <h3>Barang Perlu Dikembalikan</h3>
                  <div class="value">2</div>
              </div>
              <div class="stat-card">
                  <h3>Total Peminjaman</h3>
                  <div class="value">12</div>
              </div>
          </div>

          <!-- Recent Items -->
          <div class="recent-items">
              <h2>Riwayat Peminjaman Terakhir</h2>
              <table class="items-table">
                  <thead>
                      <tr>
                          <th>Nama Barang</th>
                          <th>Tanggal Pinjam</th>
                          <th>Tanggal Kembali</th>
                          <th>Status</th>
                      </tr>
                  </thead>
                  <tbody>
                      <tr>
                          <td>Laptop Dell XPS 13</td>
                          <td>10 Nov 2024</td>
                          <td>17 Nov 2024</td>
                          <td><span class="status-badge status-dipinjam">Dipinjam</span></td>
                      </tr>
                      <tr>
                          <td>Proyektor Epson</td>
                          <td>8 Nov 2024</td>
                          <td>9 Nov 2024</td>
                          <td><span class="status-badge status-dikembalikan">Dikembalikan</span></td>
                      </tr>
                      <tr>
                          <td>Monitor LG 27"</td>
                          <td>5 Nov 2024</td>
                          <td>12 Nov 2024</td>
                          <td><span class="status-badge status-dipinjam">Dipinjam</span></td>
                      </tr>
                      <tr>
                          <td>Keyboard Mechanical</td>
                          <td>1 Nov 2024</td>
                          <td>8 Nov 2024</td>
                          <td><span class="status-badge status-dikembalikan">Dikembalikan</span></td>
                      </tr>
                  </tbody>
              </table>
          </div>
      </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
    methods: {
        async logout() {
            try {
                await axios.delete('/api/auth/logout'); // Ganti sesuai URL Anda jika perlu
                // Lakukan clean-up, seperti menghapus token dari local storage
                localStorage.removeItem('authToken'); // atau nama yang Anda gunakan
                this.$router.push('/sign-in'); // Alihkan ke halaman sign-in setelah logout
            } catch (error) {
                console.error('Logout failed:', error);
            }
        }
    }
};
</script>

<style scoped>
@import url("https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css");

* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

:root {
  --primary-color: #2563eb;
  --secondary-color: #1d4ed8;
  --background-color: #f3f4f6;
  --card-color: #ffffff;
  --text-primary: #1f2937;
  --text-secondary: #6b7280;
}

body {
  background-color: var(--background-color);
}

.container {
  display: flex;
  min-height: 100vh;
}

/* Sidebar Styles */
.sidebar {
  width: 250px;
  background-color: var(--card-color);
  padding: 20px;
  box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
}

.logo {
  font-size: 24px;
  font-weight: bold;
  color: var(--primary-color);
  margin-bottom: 30px;
  text-align: center;
}

.nav-item {
  padding: 12px 15px;
  margin: 5px 0;
  border-radius: 8px;
  cursor: pointer;
  display: flex;
  align-items: center;
  gap: 10px;
  color: var(--text-primary);
  transition: background-color 0.3s;
}

.nav-item:hover {
  background-color: var(--background-color);
}

.nav-item.active {
  background-color: var(--primary-color);
  color: white;
}

/* Main Content Styles */
.main-content {
  flex: 1;
  padding: 20px;
}

.header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 30px;
}

.user-info {
  display: flex;
  align-items: center;
  gap: 10px;
}

.user-avatar {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  background-color: var(--primary-color);
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
}

.stats-container {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
  gap: 20px;
  margin-bottom: 30px;
}

.stat-card {
  background-color: var(--card-color);
  padding: 20px;
  border-radius: 10px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.stat-card h3 {
  color: var(--text-secondary);
  font-size: 14px;
  margin-bottom: 10px;
}

.stat-card .value {
  font-size: 24px;
  font-weight: bold;
  color: var(--text-primary);
}

.recent-items {
  background-color: var(--card-color);
  padding: 20px;
  border-radius: 10px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.recent-items h2 {
  margin-bottom: 20px;
  color: var(--text-primary);
}

.items-table {
  width: 100%;
  border-collapse: collapse;
}

.items-table th,
.items-table td {
  padding: 12px;
  text-align: left;
  border-bottom: 1px solid var(--background-color);
}

.items-table th {
  color: var(--text-secondary);
  font-weight: 600;
}

.status-badge {
  padding: 5px 10px;
  border-radius: 15px;
  font-size: 12px;
  font-weight: 500;
}

.status-dipinjam {
  background-color: #fef3c7;
  color: #92400e;
}

.status-dikembalikan {
  background-color: #d1fae5;
  color: #065f46;
}

.logout {
    color: #e11d48;
}
.logout:hover {
    background-color: #fde8e8;
}

@media (max-width: 768px) {
  .container {
      flex-direction: column;
  }

  .sidebar {
      width: 100%;
      padding: 10px;
  }

  .main-content {
      padding: 10px;
  }
}
</style>
