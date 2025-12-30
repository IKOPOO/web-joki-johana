@extends('layouts.admin')

@section('title', 'Dashboard Studio')

@section('styles')
  <link rel="stylesheet" href="{{ asset('css/staff/dashboard.css') }}">
@endsection

@section('content')
  <header class="content-header">
    <div class="header-left">
      <h1>Dashboard Studio</h1>
    </div>
    <div class="header-right">
      <div class="user-info">
        <div class="user-avatar">
          <i class="fas fa-user-circle"></i>
        </div>
        <div class="user-details">
          <span class="user-name">{{ session('user_name', 'Staff') }}</span>
          <span class="user-role">Staff Studio</span>
        </div>
      </div>
    </div>
  </header>

  <main class="content-body">
    <!-- Welcome Card -->
    <div class="welcome-card">
      <div class="welcome-content">
        <h2>Selamat Datang, {{ session('user_name', 'User') }}! 👋</h2>
        <p>Anda login sebagai <strong>Staff {{ auth()->user()->studio->name ?? 'Studio' }}</strong>. Kelola studio dan
          pantau performa bisnis Anda disini.</p>
      </div>
      <i class="fas fa-camera-retro welcome-icon"></i>
    </div>

    <!-- Summary Cards -->
    <div class="cards-grid">
      <!-- Total Booking -->
      <div class="stat-card">
        <div class="stat-header">
          <div class="stat-title">Total Reservasi</div>
          <div class="stat-icon"><i class="fas fa-calendar-alt"></i></div>
        </div>
        <div class="stat-value">{{ number_format($total_booking) }}</div>
      </div>

      <!-- Today Booking -->
      <div class="stat-card today">
        <div class="stat-header">
          <div class="stat-title">Reservasi Hari Ini</div>
          <div class="stat-icon"><i class="fas fa-clock"></i></div>
        </div>
        <div class="stat-value">{{ number_format($today_booking) }} <small
            style="font-size: 14px; color: #888; font-weight:500;">Sesi</small></div>
      </div>

      <!-- Total Revenue -->
      <div class="stat-card revenue">
        <div class="stat-header">
          <div class="stat-title">Total Pendapatan</div>
          <div class="stat-icon"><i class="fas fa-wallet"></i></div>
        </div>
        <div class="stat-value" style="color: #28a745;">Rp {{ number_format($total_revenue, 0, ',', '.') }}</div>
      </div>

      <!-- Status Breakdown -->
      <div class="stat-card">
        <div class="stat-header" style="margin-bottom: 10px;">
          <div class="stat-title">Ringkasan Status</div>
          <div class="stat-icon" style="background: #f3e5f5; color: #9c27b0;"><i class="fas fa-chart-pie"></i></div>
        </div>
        <div class="status-list">
          <div class="status-item">
            <div class="status-label">
              <div class="status-dot" style="background: #ffc107;"></div> Pending
            </div>
            <span class="badge-count">{{ $status['PENDING'] ?? 0 }}</span>
          </div>
          <div class="status-item">
            <div class="status-label">
              <div class="status-dot" style="background: #28a745;"></div> Success
            </div>
            <span class="badge-count">{{ ($status['CONFIRMED'] ?? 0) + ($status['COMPLETED'] ?? 0) }}</span>
          </div>
          <div class="status-item">
            <div class="status-label">
              <div class="status-dot" style="background: #dc3545;"></div> Cancelled
            </div>
            <span class="badge-count">{{ $status['CANCELLED'] ?? 0 }}</span>
          </div>
        </div>
      </div>
    </div>

    <!-- Charts -->
    <div class="charts-grid">
      <!-- Booking Line Chart -->
      <div class="chart-container">
        <div class="chart-header">
          <div class="chart-title"><i class="fas fa-chart-line"></i> Trend Reservasi</div>
          <span class="chart-period">6 Bulan Terakhir</span>
        </div>
        <canvas id="bookingChart" height="250"></canvas>
      </div>

      <!-- Revenue Bar Chart -->
      <div class="chart-container">
        <div class="chart-header">
          <div class="chart-title"><i class="fas fa-coins" style="color:#28a745"></i> Pendapatan Bersih</div>
          <span class="chart-period">6 Bulan Terakhir</span>
        </div>
        <canvas id="revenueChart" height="250"></canvas>
      </div>
    </div>
  </main>

  <!-- Chart.js -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="{{ asset('js/staff/dashboard.js') }}"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      initDashboardCharts(
        @json($month_labels),
        @json($booking_per_month),
        @json($revenue_per_month)
      );
    });
  </script>
@endsection