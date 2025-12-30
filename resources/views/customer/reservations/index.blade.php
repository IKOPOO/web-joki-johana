@extends('layouts.app', ['hideNavbar' => true, 'hideFooter' => true])

@section('content')
  <div class="container" style="padding-top: 40px; padding-bottom: 60px; min-height: 80vh;">
    <div style="margin-bottom: 20px;">
      <a href="{{ route('customer.profile') }}" class="btn-back-custom">
        <i class="fas fa-arrow-left"></i> Kembali
      </a>
    </div>

    <div class="section-header" style="margin-bottom: 30px;">
      <h2 class="title" style="font-size: 2rem; color: #424242;">Your Reservations</h2>
      <p style="color: #666;">Riwayat dan status booking Anda.</p>
    </div>

    @if(session('success'))
      <div class="alert alert-success"
        style="background: #d4edda; color: #155724; padding: 15px; border-radius: 10px; margin-bottom: 20px;">
        {{ session('success') }}
      </div>
    @endif

    <div class="reservations-list">
      @forelse($bookings as $booking)
        <div class="booking-item-wrapper" id="booking-{{ $booking->id }}">
          <!-- Main Dark Card -->
          <div class="reservation-card">
            <div class="res-info">
              <div class="studio-name">{{ $booking->studio->name }}</div>
              <div class="res-meta">
                <span><i class="fas fa-calendar-alt"></i>
                  {{ \Carbon\Carbon::parse($booking->booking_datetime)->isoFormat('d MMMM Y') }}</span>
                <span><i class="fas fa-clock"></i> {{ \Carbon\Carbon::parse($booking->booking_datetime)->format('H:i') }}
                  WIB</span>
              </div>
              <div class="res-package">
                <span class="pkg-badge">{{ $booking->package->name }}</span>
              </div>
            </div>

            <div class="res-action">
              <div class="status-text {{ strtolower($booking->status) }}">{{ $booking->status }}</div>
              <button type="button" class="btn-toggle-detail" onclick="toggleDetail({{ $booking->id }})">
                <span class="btn-text">Lihat Detail</span>
                <i class="fas fa-chevron-down btn-icon"></i>
              </button>

              @if($booking->payment_status === 'UNPAID' && $booking->status !== 'CANCELLED' && !$booking->payment_proof)
                <a href="{{ route('customer.booking.payment', $booking->id) }}" class="btn-pay-now"
                  style="margin-top:10px; text-decoration:none; display:inline-block; background:#28a745; color:white; padding:6px 15px; border-radius:30px; font-size:0.8rem; font-weight:600;">
                  <i class="fas fa-upload" style="margin-right:5px;"></i> Bayar
                </a>
              @endif

              <a href="{{ route('customer.booking.print', $booking->id) }}" target="_blank" class="btn-print"
                style="margin-top:5px; text-decoration:none; display:inline-block; background:#6c757d; color:white; padding:6px 15px; border-radius:30px; font-size:0.8rem; font-weight:600;">
                <i class="fas fa-print" style="margin-right:5px;"></i> Voucher
              </a>
            </div>
          </div>

          <!-- Slide Down Receipt -->
          <div class="ticket-receipt" id="receipt-{{ $booking->id }}">
            <div class="receipt-content">
              <div class="receipt-header">
                <span class="receipt-label">DETAIL RESERVASI</span>
                <span class="receipt-id">#{{ $booking->id }}</span>
              </div>

              <!-- Jadwal Sesi -->
              <div class="section-title">Jadwal Sesi Foto</div>
              <div class="receipt-row">
                <label>Tanggal</label>
                <span>{{ \Carbon\Carbon::parse($booking->booking_datetime)->isoFormat('dddd, D MMMM Y') }}</span>
              </div>
              <div class="receipt-row">
                <label>Waktu</label>
                <span>{{ \Carbon\Carbon::parse($booking->booking_datetime)->format('H:i') }} WIB</span>
              </div>

              <div class="dashed-line"></div>

              <!-- Status Booking -->
              <div class="receipt-row">
                <label>Status Booking</label>
                <span class="status-text {{ strtolower($booking->status) }}"
                  style="font-size:0.9rem;">{{ $booking->status }}</span>
              </div>

              <!-- Studio & Paket -->
              <div class="receipt-row">
                <label>Nama Studio</label>
                <span>{{ $booking->studio->name }}</span>
              </div>
              <div class="receipt-row">
                <label>Paket</label>
                <span>{{ $booking->package->name }}</span>
              </div>
              <div class="receipt-row">
                <label>Total Harga</label>
                <span class="price-val">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</span>
              </div>

              <div class="dashed-line"></div>

              <!-- Pembayaran -->
              <div class="section-title">Bukti Pembayaran</div>
              <div class="receipt-row">
                <label>Metode</label>
                <span style="text-transform: uppercase;">{{ $booking->payment_method ?? '-' }}</span>
              </div>
              <div class="receipt-row">
                <label>Status</label>

                @if($booking->payment_status == 'UNPAID' && $booking->payment_proof)
                  <span class="status-text" style="color:#ffc107; font-size:0.9rem;">MENUNGGU KONFIRMASI</span>
                @else
                  <span class="status-text {{ strtolower($booking->payment_status) }}"
                    style="font-size:0.9rem;">{{ $booking->payment_status }}</span>
                @endif
              </div>

              @if($booking->payment_proof)
                <div class="proof-image-container">
                  <label style="font-size:0.85rem; color:#888; display:block; margin-bottom:5px;">Foto Bukti:</label>
                  <img src="{{ asset($booking->payment_proof) }}" alt="Bukti Pembayaran" onclick="window.open(this.src)"
                    style="width: 100%; height: auto; border-radius: 8px; border: 1px solid #ddd; cursor: pointer; display: block;">
                </div>
              @else
                <div class="receipt-row">
                  <label>Foto Bukti</label>
                  <span style="font-style: italic; color:#999;">Tidak ada bukti</span>
                </div>
              @endif

              <div class="punch-hole-bottom"></div>
            </div>
          </div>
        </div>
      @empty
        <div class="empty-state">
          <i class="fas fa-calendar-times"></i>
          <h3>Belum ada booking</h3>
          <p>Anda belum melakukan reservasi apapun.</p>
          <a href="{{ route('customer.booking.index') }}" class="btn-book-now">Booking Sekarang</a>
        </div>
      @endforelse
    </div>
  </div>

  @push('styles')
    <link rel="stylesheet" href="{{ asset('css/customer/reservations/index.css') }}">
  @endpush

  @push('scripts')
    <script src="{{ asset('js/customer/reservations/index.js') }}"></script>
  @endpush
@endsection