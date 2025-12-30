<div class="ticket-receipt" style="opacity: 1; max-height: none; margin: 0; box-shadow: none;">
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
      <span class="status-badge status-{{ strtolower($booking->status) }}">{{ $booking->status }}</span>
    </div>

    <!-- User Info (Admin Extra) -->
    <div class="receipt-row">
      <label>Customer</label>
      <span>{{ $booking->user->name }}</span>
    </div>
    <div class="receipt-row">
      <label>No. HP</label>
      <span style="font-family:'Roboto Mono'">{{ $booking->user->phone_number }}</span>
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
      <label>Status Bayar</label>
      <span class="status-badge status-{{ strtolower($booking->payment_status) }}">{{ $booking->payment_status }}</span>
    </div>

    @if($booking->payment_proof)
      <div class="proof-image-container" style="margin-top: 15px;">
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

    @if($booking->status === 'PENDING')
      <div class="dashed-line"></div>
      <div class="receipt-actions" style="display: flex; gap: 10px; margin-top: 20px;">
        <form action="{{ route('admin.bookings.verify', $booking->id) }}" method="POST" style="flex: 1;">
          @csrf
          <button type="submit" class="btn-receipt-action btn-save">
            <i class="fas fa-check-circle"></i> Agree Payment
          </button>
        </form>
        <form action="{{ route('admin.bookings.reject', $booking->id) }}" method="POST" style="flex: 1;">
          @csrf
          <button type="submit" class="btn-receipt-action btn-confirm-delete">
            <i class="fas fa-times-circle"></i> Reject Payment
          </button>
        </form>
      </div>
    @endif
  </div>
</div>