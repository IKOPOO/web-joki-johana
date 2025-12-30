@extends('layouts.app')

@section('content')
  <div class="studio-wrapper">
    <!-- HERO SECTION -->
    <div class="studio-hero"
      style="background-image: linear-gradient(to bottom, rgba(0,0,0,0.1), rgba(0,0,0,0.6)), url('{{ $studio->image ? asset($studio->image) : asset('images/bgweb.png') }}');">
      <div class="hero-content">
        <h1>{{ $studio->name }}</h1>
        <p><i class="fas fa-map-marker-alt"></i> {{ $studio->address ?? 'Kota Semarang' }}</p>
      </div>
    </div>

    <!-- MAIN CONTENT -->
    <div class="detail-container">
      <!-- MAIN INFO (Left) -->
      <div class="main-info">
        <h2 class="section-title">Tentang Studio</h2>
        <p class="studio-desc">
          {{ $studio->description ?? 'Studio foto profesional dengan peralatan lengkap dan suasana nyaman untuk mengabadikan momen spesial Anda.' }}
        </p>

        <h3 class="section-title" style="margin-top:20px">Pilihan Paket</h3>
        <div class="packages-list">
          <!-- Descriptive only -->
          @foreach($packages as $package)
            <div class="package-item">
              <div class="pkg-info">
                <div class="pkg-name">{{ $package->name }}</div>
                <div class="pkg-desc">{{ $package->description }}</div>
              </div>
              <div class="pkg-price">Rp {{ number_format($package->price, 0, ',', '.') }}</div>
            </div>
            <hr style="border: 0; border-top: 1px solid #eee; margin: 0;">
          @endforeach
        </div>
      </div>

      <!-- SIDEBAR (Right) -->
      <div class="sidebar-booking">
        <h3 class="section-title">Booking Sekarang</h3>
        <div style="margin-bottom:15px; font-size:0.95rem; color:#555">
          Mulai dari <strong style="color: #FEC72E;">Rp 40.000</strong>
        </div>

        <button type="button" class="btn-book-big" onclick="openBookingModal()">Pilih Jadwal & Booking</button>
      </div>
    </div>
  </div>

  <!-- BOOKING MODAL -->
  <div class="booking-overlay" id="bookingOverlay" onclick="closeBookingModal()"></div>
  <div class="booking-modal" id="bookingModal">
    <div class="modal-header">
      <h3>Booking Form</h3>
      <span>{{ $studio->name }}</span>
      <button type="button" class="close-btn" onclick="closeBookingModal()">&times;</button>
    </div>

    <div class="modal-body">
      @if ($errors->any())
        <div
          style="background: #ffe6e6; color: #d93025; padding: 10px; border-radius: 6px; margin-bottom: 15px; border: 1px solid #ffcdd2;">
          <ul style="margin: 0; padding-left: 20px;">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <form action="{{ route('customer.booking.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="studio_id" value="{{ $studio->id }}">

        <div class="form-group">
          <label style="margin-bottom: 15px; display:block;">Pilih Paket</label>
          <div class="cards-grid">
            @foreach($packages as $package)
              <label class="selection-card">
                <input type="radio" name="package_id" value="{{ $package->id }}" data-price="{{ $package->price }}"
                  required>
                <div class="card-content" style="padding: 10px;">
                  <div class="card-name">{{ $package->name }}</div>
                  <div class="card-desc" style="font-size:0.85rem; color:#777; margin-bottom:8px; line-height:1.4;">
                    {{ $package->description }}
                  </div>
                  <div class="card-price">Rp {{ number_format($package->price, 0, ',', '.') }}</div>
                </div>
              </label>
            @endforeach
          </div>
        </div>

        <div class="row-inputs">
          <div class="form-group" style="flex:1;">
            <label for="date">Tanggal Sesi</label>
            <input type="date" name="date" id="date" required min="{{ date('Y-m-d') }}">
          </div>
        </div>

        <!-- Dynamic Session Slots -->
        <div class="form-group">
          <label style="margin-bottom: 10px; display:block;">Pilih Sesi</label>
          <div id="slots-loading" style="display:none; color:#666;">Memuat jadwal...</div>
          <div id="slots-container" class="slots-grid">
            <p style="color:#999; font-style:italic;">Silakan pilih tanggal terlebih dahulu.</p>
          </div>
          <input type="hidden" name="session_slot_id" id="session_slot_id" required>
        </div>

        <div class="form-group">
          <label for="note">Catatan Tambahan (Opsional)</label>
          <textarea name="note" id="note" rows="3"
            placeholder="Contoh: Bawa properti tambahan, request backdrop warna..."></textarea>
        </div>

        <!-- Payment section removed as per request to move to next step -->

        <!-- Total Price Summary -->
        <div class="summary-box" id="summaryBox"
          style="display:none; background: #fffdf5; padding: 15px; border-radius: 8px; border: 1px solid #FEC72E; margin-bottom: 20px;">
          <div style="display: flex; justify-content: space-between; align-items: center;">
            <div style="font-size: 0.9rem; color: #666;">Total Estimasi</div>
            <div style="font-size: 1.2rem; font-weight: 700; color: #202020;" id="totalPrice">Rp 0</div>
          </div>
        </div>

        <button type="submit" class="btn-book-big" style="margin-top: 10px;">Reservasi Sekarang</button>
      </form>
    </div>
  </div>

  @push('styles')
    <link rel="stylesheet" href="{{ asset('css/customer/booking/create.css') }}">
  @endpush

  @push('scripts')
    <script>
      window.hasErrors = {{ $errors->any() ? 'true' : 'false' }};
      window.bookingSlotsRoute = "{{ route('customer.booking.slots') }}";
    </script>
    <script src="{{ asset('js/customer/booking/create.js') }}"></script>
  @endpush
@endsection