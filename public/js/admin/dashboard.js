// Color palette
const colors = {
  primary: '#FEC72E',
  secondary: '#424242',
  success: '#28a745',
  danger: '#dc3545',
  warning: '#FEC72E',
  cream: '#FDF9F1',
};

// Chart.js global defaults
Chart.defaults.font.family = "'Poppins', sans-serif";
Chart.defaults.plugins.legend.labels.usePointStyle = true;

document.addEventListener('DOMContentLoaded', function () {
  if (typeof dashboardData === 'undefined') return;

  // 1. Booking per Studio (Bar Chart)
  if (document.getElementById('bookingPerStudioChart')) {
    new Chart(document.getElementById('bookingPerStudioChart'), {
      type: 'bar',
      data: {
        labels: dashboardData.bookingPerStudio.labels,
        datasets: [{
          label: 'Jumlah Booking',
          data: dashboardData.bookingPerStudio.counts,
          backgroundColor: [
            'rgba(254, 199, 46, 0.9)',
            'rgba(66, 66, 66, 0.8)',
            'rgba(254, 199, 46, 0.7)',
            'rgba(66, 66, 66, 0.6)',
            'rgba(254, 199, 46, 0.5)'
          ],
          borderRadius: 8,
          borderSkipped: false,
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: { display: false }
        },
        scales: {
          y: {
            beginAtZero: true,
            grid: { color: 'rgba(0,0,0,0.05)' },
            ticks: { stepSize: 1 }
          },
          x: { grid: { display: false } }
        }
      }
    });
  }

  // 2. Booking per Month (Line Chart)
  if (document.getElementById('bookingPerMonthChart')) {
    new Chart(document.getElementById('bookingPerMonthChart'), {
      type: 'line',
      data: {
        labels: dashboardData.monthlyTrend.labels,
        datasets: [{
          label: 'Total Booking',
          data: dashboardData.monthlyTrend.counts,
          borderColor: colors.primary,
          backgroundColor: 'rgba(254, 199, 46, 0.15)',
          fill: true,
          tension: 0.4,
          pointBackgroundColor: colors.primary,
          pointBorderColor: '#fff',
          pointBorderWidth: 2,
          pointRadius: 5,
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: { display: false }
        },
        scales: {
          y: {
            beginAtZero: true,
            grid: { color: 'rgba(0,0,0,0.05)' },
            ticks: { stepSize: 1 }
          },
          x: { grid: { display: false } }
        }
      }
    });
  }

  // 3. Booking Ratio (Pie Chart)
  if (document.getElementById('bookingRatioChart')) {
    new Chart(document.getElementById('bookingRatioChart'), {
      type: 'pie',
      data: {
        labels: ['Selesai (Done)', 'Dibatalkan (Cancelled)', 'Lainnya (Pending/Process)'],
        datasets: [{
          data: [
            dashboardData.bookingRatio.done,
            dashboardData.bookingRatio.cancelled,
            dashboardData.bookingRatio.others
          ],
          backgroundColor: [
            'rgba(40, 167, 69, 0.8)',
            'rgba(220, 53, 69, 0.8)',
            'rgba(254, 199, 46, 0.8)'
          ],
          borderWidth: 0,
          hoverOffset: 10
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            position: 'bottom',
            labels: { padding: 20 }
          }
        }
      }
    });
  }

  // 4. Income per Studio (Bar Chart)
  if (document.getElementById('incomePerStudioChart')) {
    new Chart(document.getElementById('incomePerStudioChart'), {
      type: 'bar',
      data: {
        labels: dashboardData.incomePerStudio.labels,
        datasets: [{
          label: 'Total Income',
          data: dashboardData.incomePerStudio.income,
          backgroundColor: [
            'rgba(66, 66, 66, 0.9)',
            'rgba(254, 199, 46, 0.9)',
            'rgba(66, 66, 66, 0.7)',
            'rgba(254, 199, 46, 0.7)',
            'rgba(66, 66, 66, 0.5)'
          ],
          borderRadius: 8,
          borderSkipped: false,
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: { display: false }
        },
        scales: {
          y: {
            beginAtZero: true,
            grid: { color: 'rgba(0,0,0,0.05)' },
            ticks: {
              callback: function (value) {
                if (value >= 1000000) return 'Rp ' + (value / 1000000).toFixed(1) + 'jt';
                if (value >= 1000) return 'Rp ' + (value / 1000).toFixed(0) + 'rb';
                return 'Rp ' + value;
              }
            }
          },
          x: { grid: { display: false } }
        }
      }
    });
  }

  // 5. Monthly Income Trend (Line Chart)
  if (document.getElementById('incomeProjectionChart')) {
    new Chart(document.getElementById('incomeProjectionChart'), {
      type: 'line',
      data: {
        labels: dashboardData.incomeTrend.labels,
        datasets: [
          {
            label: 'Pendapatan Diterima (PAID)',
            data: dashboardData.incomeTrend.income,
            borderColor: colors.success,
            backgroundColor: 'rgba(40, 167, 69, 0.1)',
            fill: true,
            tension: 0.4,
            pointRadius: 4,
          },
          {
            label: 'Proyeksi Pendapatan (UNPAID)',
            data: dashboardData.incomeTrend.pending,
            borderColor: colors.warning,
            backgroundColor: 'rgba(254, 199, 46, 0.1)',
            fill: true,
            tension: 0.4,
            pointRadius: 4,
          }
        ]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            position: 'top',
            align: 'end'
          }
        },
        scales: {
          y: {
            beginAtZero: true,
            grid: { color: 'rgba(0,0,0,0.05)' },
            ticks: {
              callback: function (value) {
                if (value >= 1000000) return 'Rp ' + (value / 1000000).toFixed(1) + 'jt';
                if (value >= 1000) return 'Rp ' + (value / 1000).toFixed(0) + 'rb';
                return 'Rp ' + value;
              }
            }
          },
          x: { grid: { display: false } }
        }
      }
    });
  }

  // 6. User Segmentation (Pie Chart) - Lifecycle Based
  if (document.getElementById('userSegmentationChart')) {
    new Chart(document.getElementById('userSegmentationChart'), {
      type: 'pie',
      data: {
        labels: ['New (<30 Hari)', 'Engaged (≥2 Booking)', 'Casual (1 Booking)', 'Dormant (Belum Booking)'],
        datasets: [{
          data: [
            dashboardData.userSegmentation.new,
            dashboardData.userSegmentation.engaged,
            dashboardData.userSegmentation.casual,
            dashboardData.userSegmentation.dormant
          ],
          backgroundColor: [
            'rgba(40, 167, 69, 0.8)',  // Hijau untuk New
            'rgba(254, 199, 46, 0.9)', // Kuning untuk Engaged
            'rgba(66, 66, 66, 0.6)',   // Abu untuk Casual
            'rgba(220, 53, 69, 0.7)'   // Merah untuk Dormant
          ],
          borderWidth: 0,
          hoverOffset: 10
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            position: 'bottom',
            labels: { padding: 15 }
          }
        }
      }
    });
  }
});
