@extends('layouts.admin')

@section('title', 'Staff Dashboard')

@section('styles')
  <link rel="stylesheet" href="{{ asset('css/admin/dashboard.css') }}">
  <style>
    .welcome-banner {
      background: linear-gradient(135deg, #FEC72E 0%, #E65100 100%);
    }
  </style>
@endsection

@section('content')
  <!-- Header -->
  <header class="content-header">
    <div class="header-left">
      <h1>Dashboard Staff</h1>
      <p class="text-subtitle">Selamat datang kembali, {{ auth()->user()->name }}!</p>
    </div>
    <div class="header-right">
      <div class="user-info">
        <div class="user-avatar">
          {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
        </div>
        <div class="user-details">
          <span class="user-name">{{ auth()->user()->name }}</span>
          <span class="user-role">Staff Studio</span>
        </div>
      </div>
    </div>
  </header>

  <!-- Content Area -->
  <main class="content-body">

    <!-- Welcome Banner -->
    <div class="welcome-banner">
      <div class="welcome-text">
        <h2>Halo, Staff {{ auth()->user()->studio->name ?? 'Studio' }}! 👋</h2>
        <p>Siap melayani customer hari ini?</p>
      </div>
      <div class="welcome-img">
        <i class="fas fa-calendar-check" style="font-size: 80px; color: rgba(255,255,255,0.2);"></i>
      </div>
    </div>

    <!-- Stats Grid -->
    <div class="stats-grid">
      <!-- Total Bookings -->
      <div class="stat-card">
        <div class="stat-icon-wrapper total">
          <i class="fas fa-calendar-alt"></i>
        </div>
        <div class="stat-content">
          <h3 class="stat-value">{{ $stats['totalBookings'] }}</h3>
          <p class="stat-label">Total Booking</p>
        </div>
      </div>

      <!-- Pending Bookings -->
      <div class="stat-card">
        <div class="stat-icon-wrapper pending">
          <i class="fas fa-clock"></i>
        </div>
        <div class="stat-content">
          <h3 class="stat-value">{{ $stats['pendingBookings'] }}</h3>
          <p class="stat-label">Booking Pending</p>
        </div>
      </div>

      <!-- Today Bookings -->
      <div class="stat-card">
        <div class="stat-icon-wrapper success">
          <i class="fas fa-calendar-day"></i>
        </div>
        <div class="stat-content">
          <h3 class="stat-value">{{ $stats['todayBookings'] }}</h3>
          <p class="stat-label">Booking Hari Ini</p>
        </div>
      </div>
    </div>

    <!-- Recent Bookings Table -->
    <div class="section-title">
      <h2><i class="fas fa-history"></i> Booking Terbaru</h2>
      <a href="{{ route('admin.bookings.index') }}" class="btn-link">Lihat Semua</a>
    </div>

    <div class="table-card">
      <div class="table-responsive">
        <table class="data-table">
          <thead>
            <tr>
              <th>Customer</th>
              <th>Paket</th>
              <th>Tanggal</th>
              <th>Status</th>
              <th>Harga</th>
            </tr>
          </thead>
          <tbody>
            @forelse($recentBookings as $booking)
              <tr>
                <td>
                  <div class="user-cell-info">
                    <span class="user-name">{{ $booking->user->name }}</span>
                  </div>
                </td>
                <td>{{ $booking->package->name }}</td>
                <td>{{ \Carbon\Carbon::parse($booking->booking_datetime)->format('d M Y H:i') }}</td>
                <td>
                  <span class="status-badge status-{{ strtolower($booking->status) }}">
                    {{ ucfirst(strtolower($booking->status)) }}
                  </span>
                </td>
                <td>Rp {{ number_format($booking->total_price, 0, ',', '.') }}</td>
              </tr>
            @empty
              <tr>
                <td colspan="5" class="text-center">Belum ada booking terbaru.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

  </main>
@endsection