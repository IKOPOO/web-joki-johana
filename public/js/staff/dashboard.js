function initDashboardCharts(monthLabels, bookingData, revenueData) {
  // Global Defaults
  Chart.defaults.font.family = "'Poppins', sans-serif";
  Chart.defaults.color = '#666';

  // 1. Booking Line Chart with Gradient
  const bookingCtx = document.getElementById('bookingChart').getContext('2d');

  // Create Gradient
  let bookingGradient = bookingCtx.createLinearGradient(0, 0, 0, 400);
  bookingGradient.addColorStop(0, 'rgba(254, 199, 46, 0.4)');
  bookingGradient.addColorStop(1, 'rgba(254, 199, 46, 0)');

  new Chart(bookingCtx, {
    type: 'line',
    data: {
      labels: monthLabels,
      datasets: [{
        label: 'Jumlah Reservasi',
        data: bookingData,
        borderColor: '#FEC72E',
        backgroundColor: bookingGradient,
        tension: 0.4, // Smooth curve
        fill: true,
        borderWidth: 3,
        pointBackgroundColor: '#fff',
        pointBorderColor: '#FEC72E',
        pointBorderWidth: 3,
        pointRadius: 5,
        pointHoverRadius: 7
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: { display: false },
        tooltip: {
          backgroundColor: '#202020',
          titleFont: { size: 13 },
          bodyFont: { size: 14, weight: 'bold' },
          padding: 10,
          displayColors: false,
          callbacks: {
            label: function (context) {
              return context.parsed.y + ' Reservasi';
            }
          }
        }
      },
      scales: {
        y: {
          beginAtZero: true,
          grid: { color: '#f0f0f0', borderDash: [5, 5] },
          ticks: { padding: 10 }
        },
        x: {
          grid: { display: false },
          ticks: { padding: 10 }
        }
      }
    }
  });

  // 2. Revenue Bar Chart with Gradient
  const revenueCtx = document.getElementById('revenueChart').getContext('2d');

  // Gradient Green
  let revenueGradient = revenueCtx.createLinearGradient(0, 0, 0, 400);
  revenueGradient.addColorStop(0, '#28a745');
  revenueGradient.addColorStop(1, '#20c997');

  new Chart(revenueCtx, {
    type: 'bar',
    data: {
      labels: monthLabels,
      datasets: [{
        label: 'Pendapatan',
        data: revenueData,
        backgroundColor: revenueGradient,
        borderRadius: 8,
        barPercentage: 0.5,
        hoverBackgroundColor: '#1e7e34'
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: { display: false },
        tooltip: {
          backgroundColor: '#202020',
          padding: 10,
          displayColors: false,
          callbacks: {
            label: function (context) {
              if (context.parsed.y !== null) {
                return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(context.parsed.y);
              }
              return '';
            }
          }
        }
      },
      scales: {
        y: {
          beginAtZero: true,
          grid: { color: '#f0f0f0', borderDash: [5, 5] },
          ticks: {
            padding: 10,
            callback: function (value) {
              // Shorten large numbers (e.g. 1jt)
              if (value >= 1000000) return 'Rp ' + (value / 1000000) + ' Jt';
              if (value >= 1000) return 'Rp ' + (value / 1000) + ' Rb';
              return value;
            }
          }
        },
        x: {
          grid: { display: false }
        }
      }
    }
  });
}
