@extends('layouts.admin')

@section('title', 'Dashboard')

@section('styles')
  <link rel="stylesheet" href="{{ asset('css/admin/dashboard.css') }}">
@endsection

@section('content')
  <!-- Header -->
  <header class="content-header">
    <div class="header-left">
      <h1>Dashboard</h1>
    </div>
    <div class="header-right">
      <div class="user-info">
        <div class="user-avatar">
          <i class="fas fa-user-circle"></i>
        </div>
        <div class="user-details">
          <span class="user-name">{{ session('user_name', 'User') }}</span>
          <span class="user-role">{{ ucfirst(session('user_role', 'customer')) }}</span>
        </div>
      </div>
    </div>
  </header>

  <!-- Content Area -->
  <main class="content-body">
    @if(session('success'))
      <div class="alert alert-success">
        <i class="fas fa-check-circle"></i>
        {{ session('success') }}
      </div>
    @endif

    @if(session('error'))
      <div class="alert alert-error">
        <i class="fas fa-exclamation-circle"></i>
        {{ session('error') }}
      </div>
    @endif

    <!-- Dashboard Content -->
    <div class="dashboard-welcome">
      <h2>Selamat Datang, {{ session('user_name', 'User') }}!</h2>
      <p>Anda login sebagai <strong>{{ ucfirst(session('user_role', 'customer')) }}</strong></p>
    </div>

    <!-- Stats Cards -->
    <div class="stats-grid">
      @include('admin.partials.stat-card', ['icon' => 'fas fa-building', 'value' => $stats['totalStudios'], 'label' => 'Total Studio'])
      @include('admin.partials.stat-card', ['icon' => 'fas fa-calendar-check', 'value' => $stats['totalBookings'], 'label' => 'Total Booking'])
      @include('admin.partials.stat-card', ['icon' => 'fas fa-users', 'value' => $stats['totalUsers'], 'label' => 'Total Users'])
      @include('admin.partials.stat-card', ['icon' => 'fas fa-money-bill-wave', 'value' => 'Rp ' . number_format($stats['totalIncome'], 0, ',', '.'), 'label' => 'Total Pendapatan'])
    </div>

    <!-- Charts Section 1: Booking Analytics -->
    @include('admin.partials.section-title', ['icon' => 'fas fa-chart-bar', 'title' => 'Analisis Booking'])

    <div class="charts-grid-2col">
      @include('admin.partials.chart-card', [
        'title' => 'Rasio Booking',
        'subtitle' => 'Confirmed vs Cancelled',
        'chartId' => 'bookingRatioChart',
        'type' => 'pie'
      ])

      @include('admin.partials.chart-card', [
        'title' => 'Jumlah Booking per Studio',
        'subtitle' => 'Perbandingan booking antar studio',
        'chartId' => 'bookingPerStudioChart',
        'type' => 'normal'
      ])
    </div>

    @include('admin.partials.chart-card', [
      'title' => 'Statistik Booking per Bulan',
      'subtitle' => 'Tren booking dalam 12 bulan terakhir',
      'chartId' => 'bookingPerMonthChart',
      'type' => 'full'
    ])

    <!-- Charts Section 2: Income Analytics -->
    @include('admin.partials.section-title', ['icon' => 'fas fa-wallet', 'title' => 'Analisis Pendapatan'])

    <div class="charts-grid-2col">
      @include('admin.partials.chart-card', [
        'title' => 'Segmentasi Pengguna',
        'subtitle' => 'Berdasarkan lifecycle & aktivitas',
        'chartId' => 'userSegmentationChart',
        'type' => 'pie'
      ])

      @include('admin.partials.chart-card', [
        'title' => 'Jumlah Income per Studio',
        'subtitle' => 'Pendapatan masing-masing studio',
        'chartId' => 'incomePerStudioChart',
        'type' => 'normal'
      ])
    </div>

    @include('admin.partials.chart-card', [
      'title' => 'Prediksi & Proyeksi Income',
      'subtitle' => 'All Transactions vs Done Transactions',
      'chartId' => 'incomeProjectionChart',
      'type' => 'full'
    ])

    <!-- Latest Booking Status Section -->
    <div class="section-title" id="bookings-table">
      <h2><i class="fas fa-clipboard-list"></i> Status Booking Terbaru</h2>
    </div>

    <div class="table-card">
      <div class="table-responsive">
        <table class="data-table">
          <thead>
            <tr>
              <th>No</th>
              <th>Nama</th>
              <th>Jadwal</th>
              <th>Studio</th>
              <th>Paket</th>
              <th>Total Income</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse($recentBookings as $index => $booking)
              <tr>
                <td>{{ $recentBookings->firstItem() + $index }}</td>
                <td>
                  <div class="customer-info">
                    <span class="customer-name">{{ $booking->user->name ?? 'Guest' }}</span>
                  </div>
                </td>
                <td>
                  <div class="schedule-info">
                    <span class="schedule-date">{{ $booking->booking_datetime->format('d M Y') }}</span>
                    <span class="schedule-time">{{ $booking->booking_datetime->format('H:i') }}</span>
                  </div>
                </td>
                <td><span class="badge badge-studio">{{ $booking->studio->name ?? '-' }}</span></td>
                <td><span class="badge badge-package">{{ $booking->package->name ?? '-' }}</span></td>
                <td><span class="income-amount">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</span></td>
                <td>
                  <a href="{{ route('admin.bookings.index') }}?search={{ $booking->id }}" class="btn-view" title="Lihat Detail">
                    <i class="fas fa-eye"></i>
                  </a>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="7" style="text-align: center; padding: 20px;">Belum ada booking terbaru.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      @if($recentBookings->hasPages())
        <div class="table-pagination">
          <div class="pagination-info">
            Menampilkan {{ $recentBookings->firstItem() }} - {{ $recentBookings->lastItem() }} dari {{ $recentBookings->total() }} booking
          </div>
          <div class="pagination-nav">
            @if($recentBookings->onFirstPage())
              <button class="pagination-btn" disabled>
                <i class="fas fa-chevron-left"></i> Prev
              </button>
            @else
              <a href="{{ $recentBookings->previousPageUrl() }}#bookings-table" class="pagination-btn">
                <i class="fas fa-chevron-left"></i> Prev
              </a>
            @endif
            
            <span class="pagination-page">{{ $recentBookings->currentPage() }} / {{ $recentBookings->lastPage() }}</span>
            
            @if($recentBookings->hasMorePages())
              <a href="{{ $recentBookings->nextPageUrl() }}#bookings-table" class="pagination-btn">
                Next <i class="fas fa-chevron-right"></i>
              </a>
            @else
              <button class="pagination-btn" disabled>
                Next <i class="fas fa-chevron-right"></i>
              </button>
            @endif
          </div>
        </div>
      @endif
    </div>

  </main>
@endsection

@section('scripts')
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script>
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

    // 1. Booking per Studio (Bar Chart)
    new Chart(document.getElementById('bookingPerStudioChart'), {
      type: 'bar',
      data: {
        labels: {!! json_encode($bookingPerStudio->pluck('name')) !!},
        datasets: [{
          label: 'Jumlah Booking',
          data: {!! json_encode($bookingPerStudio->pluck('count')) !!},
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

    // 2. Booking per Month (Line Chart)
    new Chart(document.getElementById('bookingPerMonthChart'), {
      type: 'line',
      data: {
        labels: {!! json_encode($monthlyTrend->pluck('month')) !!},
        datasets: [{
          label: 'Total Booking',
          data: {!! json_encode($monthlyTrend->pluck('count')) !!},
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

    // 3. Booking Ratio (Pie Chart)
    new Chart(document.getElementById('bookingRatioChart'), {
      type: 'pie',
      data: {
        labels: ['Selesai (Done)', 'Dibatalkan (Cancelled)', 'Lainnya (Pending/Process)'],
        datasets: [{
          data: [
            {{ $bookingRatio['done'] }}, 
            {{ $bookingRatio['cancelled'] }}, 
            {{ $bookingRatio['others'] }}
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

    // 4. Income per Studio (Bar Chart)
    new Chart(document.getElementById('incomePerStudioChart'), {
      type: 'bar',
      data: {
        labels: {!! json_encode($incomePerStudio->pluck('name')) !!},
        datasets: [{
          label: 'Total Income',
          data: {!! json_encode($incomePerStudio->pluck('income')) !!},
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
                if (value >= 1000000) return 'Rp ' + (value/1000000).toFixed(1) + 'jt';
                if (value >= 1000) return 'Rp ' + (value/1000).toFixed(0) + 'rb';
                return 'Rp ' + value;
              }
            }
          },
          x: { grid: { display: false } }
        }
      }
    });

    // 5. Monthly Income Trend (Line Chart)
    new Chart(document.getElementById('incomeProjectionChart'), {
      type: 'line',
      data: {
        labels: {!! json_encode($incomeTrend->pluck('month')) !!},
        datasets: [
          {
            label: 'Pendapatan Diterima (PAID)',
            data: {!! json_encode($incomeTrend->pluck('income')) !!},
            borderColor: colors.success,
            backgroundColor: 'rgba(40, 167, 69, 0.1)',
            fill: true,
            tension: 0.4,
            pointRadius: 4,
          },
          {
            label: 'Proyeksi Pendapatan (UNPAID)',
            data: {!! json_encode($incomeTrend->pluck('pending')) !!},
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
                if (value >= 1000000) return 'Rp ' + (value/1000000).toFixed(1) + 'jt';
                if (value >= 1000) return 'Rp ' + (value/1000).toFixed(0) + 'rb';
                return 'Rp ' + value;
              }
            }
          },
          x: { grid: { display: false } }
        }
      }
    });

    // 6. User Segmentation (Pie Chart) - Lifecycle Based
    new Chart(document.getElementById('userSegmentationChart'), {
      type: 'pie',
      data: {
        labels: ['New (<30 Hari)', 'Engaged (≥2 Booking)', 'Casual (1 Booking)', 'Dormant (Belum Booking)'],
        datasets: [{
          data: [
            {{ $userSegmentation['new'] }}, 
            {{ $userSegmentation['engaged'] }}, 
            {{ $userSegmentation['casual'] }}, 
            {{ $userSegmentation['dormant'] }}
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
  </script>
@endsection