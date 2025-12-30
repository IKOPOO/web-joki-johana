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
    const dashboardData = {
      bookingPerStudio: {
        labels: {!! json_encode($bookingPerStudio->pluck('name')) !!},
        counts: {!! json_encode($bookingPerStudio->pluck('count')) !!}
      },
      monthlyTrend: {
        labels: {!! json_encode($monthlyTrend->pluck('month')) !!},
        counts: {!! json_encode($monthlyTrend->pluck('count')) !!}
      },
      bookingRatio: {
        done: {{ $bookingRatio['done'] }},
        cancelled: {{ $bookingRatio['cancelled'] }},
        others: {{ $bookingRatio['others'] }}
      },
      incomePerStudio: {
        labels: {!! json_encode($incomePerStudio->pluck('name')) !!},
        income: {!! json_encode($incomePerStudio->pluck('income')) !!}
      },
      incomeTrend: {
        labels: {!! json_encode($incomeTrend->pluck('month')) !!},
        income: {!! json_encode($incomeTrend->pluck('income')) !!},
        pending: {!! json_encode($incomeTrend->pluck('pending')) !!}
      },
      userSegmentation: {
        new: {{ $userSegmentation['new'] }},
        engaged: {{ $userSegmentation['engaged'] }},
        casual: {{ $userSegmentation['casual'] }},
        dormant: {{ $userSegmentation['dormant'] }}
      }
    };
  </script>
  <script src="{{ asset('js/admin/dashboard.js') }}"></script>
@endsection