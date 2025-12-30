@extends('layouts.admin')

@section('title', 'Verifikasi Pembayaran')

@section('styles')
  <link rel="stylesheet" href="{{ asset('css/admin/booking.css') }}">
  <link rel="stylesheet" href="{{ asset('css/admin/bookings/verification.css') }}">
@endsection

@section('content')
  <!-- Header -->
  <header class="content-header">
    <div class="header-left">
      <h1>Verifikasi Pembayaran</h1>
    </div>
    <div class="header-right">
      <div class="user-info">
        <div class="user-avatar">
          <i class="fas fa-user-circle"></i>
        </div>
        <div class="user-details">
          <span class="user-name">{{ session('user_name', 'Admin') }}</span>
          <span class="user-role">{{ ucfirst(session('user_role', 'admin')) }}</span>
        </div>
      </div>
    </div>
  </header>

  <!-- Content Area -->
  <main class="content-body">
    <!-- Page Title Section -->
    <div class="page-title-section">
      <h1 class="main-title">Daftar Verifikasi Pembayaran</h1>
      <p class="subtitle">Studio: <span class="studio-name">{{ auth()->user()->studio->name ?? 'Studio' }}</span> |
        Tanggal: <span class="studio-name">{{ now()->format('d F Y') }}</span></p>
    </div>

    @if(session('success'))

      <div class="alert alert-success" style="margin-bottom: 20px;">
        <i class="fas fa-check-circle"></i>
        {{ session('success') }}
      </div>
    @endif

    @if(session('error'))
      <div class="alert alert-error" style="margin-bottom: 20px;">
        <i class="fas fa-exclamation-circle"></i>
        {{ session('error') }}
      </div>
    @endif

    @if($bookings->isEmpty())
      <div class="empty-state"
        style="background: white; padding: 60px 30px; border-radius: 16px; text-align: center; box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
        <div style="font-size: 4rem; color: #eee; margin-bottom: 20px;"><i class="fas fa-check-circle"></i></div>
        <h3 style="font-size: 1.5rem; color: #424242; font-weight: 600;">Semua Pembayaran Terverifikasi</h3>
        <p style="color: #999; margin-top: 10px;">Tidak ada booking yang menunggu verifikasi saat ini.</p>
      </div>
    @else
      @foreach($bookings as $booking)
        <div class="verification-card" onclick="toggleCard(this)">
          <div class="card-header">
            <div style="display: flex; align-items: center; gap: 15px;">
              <div style="color: #FEC72E; font-weight: 600; font-size: 1.1rem; letter-spacing: 0.5px;">
                Booking #{{ $booking->id }}
              </div>
              <div style="height: 20px; width: 1px; background: rgba(255,255,255,0.2);"></div>
              <div style="font-size: 13px; color: rgba(255,255,255,0.7);">
                <i class="fas fa-calendar-alt" style="margin-right: 5px;"></i>
                {{ \Carbon\Carbon::parse($booking->booking_datetime)->format('d M Y') }}
              </div>
              <div style="font-size: 13px; color: rgba(255,255,255,0.7);">
                • {{ $booking->user->name ?? 'Deleted User' }}
              </div>
            </div>

            <div style="display: flex; align-items: center; gap: 15px;">
              <span
                style="background: rgba(255, 193, 7, 0.2); color: #FFC107; padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; border: 1px solid rgba(255, 193, 7, 0.3);">
                <i class="fas fa-hourglass-half" style="margin-right: 4px;"></i> MENUNGGU VERIFIKASI
              </span>
              <i class="fas fa-chevron-down expand-icon" style="color: rgba(255,255,255,0.5);"></i>
            </div>
          </div>

          <div class="card-body" onclick="event.stopPropagation()">
            <!-- Info Column -->
            <div>
              <h4
                style="margin-bottom: 20px; color: #424242; font-size: 1.1rem; display: flex; align-items: center; gap: 10px;">
                <i class="fas fa-info-circle" style="color: #FEC72E;"></i> Detail Booking
              </h4>
              <div class="info-grid" style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px;">
                <div class="info-item">
                  <label
                    style="font-size: 12px; color: #999; text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-bottom: 5px;">Customer</label>
                  <div style="font-weight: 600; color: #424242; font-size: 1rem;">{{ $booking->user->name ?? 'Deleted User' }}
                  </div>
                </div>
                <div class="info-item">
                  <label
                    style="font-size: 12px; color: #999; text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-bottom: 5px;">Email</label>
                  <div style="font-weight: 500; color: #424242;">{{ $booking->user->email ?? '-' }}</div>
                </div>
                <div class="info-item">
                  <label
                    style="font-size: 12px; color: #999; text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-bottom: 5px;">No.
                    HP</label>
                  <div style="font-weight: 500; color: #424242;">{{ $booking->user->phone ?? '-' }}</div>
                </div>
                <div class="info-item">
                  <label
                    style="font-size: 12px; color: #999; text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-bottom: 5px;">Studio</label>
                  <div style="font-weight: 600; color: #424242;">{{ $booking->studio->name }}</div>
                </div>
                <div class="info-item">
                  <label
                    style="font-size: 12px; color: #999; text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-bottom: 5px;">Paket</label>
                  <div style="font-weight: 600; color: #424242;">{{ $booking->package->name }}</div>
                </div>
                <div class="info-item">
                  <label
                    style="font-size: 12px; color: #999; text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-bottom: 5px;">Jadwal
                    Sesi</label>
                  <div style="font-weight: 600; color: #424242;">
                    {{ \Carbon\Carbon::parse($booking->booking_datetime)->isoFormat('dddd, D MMMM Y HH:mm') }}
                  </div>
                </div>
                <div class="info-item" style="grid-column: span 2;">
                  <label
                    style="font-size: 12px; color: #999; text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-bottom: 5px;">Total
                    Tagihan</label>
                  <div
                    style="color: #FEC72E; background: #202020; padding: 8px 16px; border-radius: 8px; display: inline-flex; align-items: center; font-weight: 700; font-size: 1.2rem; gap: 8px;">
                    <span>Rp {{ number_format($booking->total_price, 0, ',', '.') }}</span>
                    <span
                      style="font-size: 0.8rem; font-weight: 400; color: #aaa; background: rgba(255,255,255,0.1); padding: 2px 6px; border-radius: 4px;">{{ $booking->payment_method ?? 'TRANSFER' }}</span>
                  </div>
                </div>
              </div>
            </div>

            <!-- Proof Column -->
            <div class="proof-preview"
              style="background: #fafafa; border-radius: 12px; padding: 20px; text-align: center; border: 1px dashed #ddd;">
              <h4 style="margin-bottom: 15px; color: #424242; font-size: 1rem;">📎 Bukti Transfer</h4>
              @if($booking->payment_proof)
                <a href="{{ asset($booking->payment_proof) }}" target="_blank"
                  style="display: block; overflow: hidden; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); margin-bottom: 15px;">
                  <img src="{{ asset($booking->payment_proof) }}" alt="Bukti Transfer"
                    style="width: 100%; height: 200px; object-fit: cover; display: block;">
                </a>
                <div style="font-size: 12px; color: #999; margin-bottom: 10px;">
                  <i class="fas fa-clock"></i> Uploaded: {{ $booking->updated_at->format('d M Y H:i') }}
                </div>

                <div class="action-buttons" style="display: flex; gap: 10px; margin-top: 15px;">
                  <form action="{{ route('admin.bookings.verify', $booking->id) }}" method="POST" style="flex: 1;">
                    @csrf
                    <button type="submit" class="btn-receipt-action btn-save">
                      <i class="fas fa-check-circle"></i> Agree
                    </button>
                  </form>
                  <form action="{{ route('admin.bookings.reject', $booking->id) }}" method="POST" style="flex: 1;">
                    @csrf
                    <button type="submit" class="btn-receipt-action btn-confirm-delete">
                      <i class="fas fa-times-circle"></i> Reject
                    </button>
                  </form>
                </div>
              @else
                <div style="padding: 40px 0; color: #999;">
                  <i class="fas fa-image" style="font-size: 2rem; margin-bottom: 10px; display: block;"></i>
                  Tidak ada bukti transfer
                </div>
              @endif
            </div>
          </div>
        </div>
      @endforeach
    @endif
  </main>

  @section('scripts')
    <script src="{{ asset('js/admin/bookings/verification.js') }}"></script>
  @endsection
@endsection