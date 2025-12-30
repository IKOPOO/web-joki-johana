@extends('layouts.app', ['hideFooter' => true])

@section('content')
  <div class="container profile-page-container">
    <div class="profile-container">
      <div class="profile-header">
        <div class="avatar-circle">
          {{ substr($user->name, 0, 1) }}
        </div>
        <h2 class="profile-name">{{ $user->name }}</h2>
        <p class="profile-email">{{ $user->email }}</p>
        <span class="badge role-badge">
          {{ ucwords(strtolower($user->role)) }}
        </span>
      </div>

      <div class="profile-details">
        <div class="detail-group border-bottom">
          <label class="detail-label">No. Handphone</label>
          <div class="detail-value">{{ $user->phone ?? '-' }}</div>
        </div>
        <div class="detail-group">
          <label class="detail-label">Status Akun</label>
          <div class="detail-value status-active">{{ $user->status }}</div>
        </div>
      </div>

      <div class="profile-actions">
        <a href="{{ route('customer.reservations.index') }}" class="btn-action btn-history">
          <i class="fas fa-list-alt btn-icon-left"></i> Riwayat Booking
        </a>

        <form action="{{ route('logout') }}" method="POST" style="display: inline;">
          @csrf
          <button type="submit" class="btn-action btn-logout">
            <i class="fas fa-sign-out-alt btn-icon-left"></i> Logout
          </button>
        </form>
      </div>
    </div>
  </div>
  @push('styles')
    <link rel="stylesheet" href="{{ asset('css/customer/profile.css') }}">
  @endpush
@endsection