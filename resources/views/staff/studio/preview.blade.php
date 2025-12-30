@extends('layouts.admin')

@section('title', 'Preview Studio - ' . $studio->name)

@section('styles')
  <link rel="stylesheet" href="{{ asset('css/admin/booking.css') }}">
  <link rel="stylesheet" href="{{ asset('css/staff/studio-preview.css') }}">
@endsection

@section('content')
  <!-- Header -->
  <header class="content-header">
    <div class="header-left">
      <h1>Preview Studio</h1>
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
    <!-- Feedback Messages -->
    @if(session('success'))
      <div class="alert alert-success"
        style="margin-bottom: 20px; background: #d4edda; color: #155724; padding: 15px; border-radius: 8px;">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
      </div>
    @endif

    <!-- Statistics -->
    <div class="stats-grid">
      <div class="stat-card">
        <div class="label"><i class="fas fa-calendar-check" style="margin-right:5px;"></i> Total Booking</div>
        <div class="value">{{ number_format($stats['total_booking']) }}</div>
      </div>
      <div class="stat-card">
        <div class="label"><i class="fas fa-chart-line" style="margin-right:5px;"></i> Bulan Ini</div>
        <div class="value">{{ number_format($stats['this_month']) }}</div>
      </div>
      <div class="stat-card">
        <div class="label"><i class="fas fa-check-circle" style="margin-right:5px;"></i> Confirmed</div>
        <div class="value">{{ number_format($stats['confirmed']) }}</div>
      </div>
      <div class="stat-card revenue">
        <div class="label"><i class="fas fa-wallet" style="margin-right:5px;"></i> Total Revenue</div>
        <div class="value">Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}</div>
      </div>
    </div>

    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 30px;">

      <!-- Left Column -->
      <div>
        <!-- Studio Info -->
        <div class="studio-card">
          <div class="card-header">
            <h2><i class="fas fa-store"></i> Informasi Studio</h2>
            <button class="btn-edit" onclick="openEditModal()">
              <i class="fas fa-edit"></i> Edit Info
            </button>
          </div>
          <div class="card-body">
            @if($studio->image)
              <img src="{{ asset($studio->image) }}" alt="{{ $studio->name }}" class="studio-image">
            @endif

            <div class="info-item">
              <label>Nama Studio</label>
              <div class="value">{{ $studio->name }}</div>
            </div>
            <div class="info-item">
              <label>Deskripsi</label>
              <div class="value" style="white-space: pre-line;">{{ $studio->description ?? 'Belum ada deskripsi' }}</div>
            </div>
            <div class="info-item">
              <label>Alamat</label>
              <div class="value">{{ $studio->address }}, {{ $studio->city ?? '' }}</div>
            </div>
          </div>
        </div>

        <!-- Booking URL -->
        <div class="studio-card">
          <div class="card-header">
            <h2><i class="fas fa-link"></i> Link Halaman Booking</h2>
          </div>
          <div class="card-body">
            <p style="color: #666; margin-bottom: 0;">
              Bagikan link ini kepada customer untuk booking di studio Anda:
            </p>
            <div class="url-box">
              <input type="text" value="{{ $booking_url }}" id="bookingUrl" readonly>
              <button class="btn-copy" onclick="copyUrl()">
                <i class="fas fa-copy"></i> Copy
              </button>
            </div>

            <div class="actions" style="margin-top: 25px; text-align: right;">
              <a href="{{ $booking_url }}?preview=true" target="_blank" class="btn-preview">
                <i class="fas fa-eye"></i> Preview Halaman Booking
              </a>
            </div>
          </div>
        </div>
      </div>

      <!-- Right Column -->
      <div>
        <!-- Quick Tips -->
        <div class="studio-card">
          <div class="card-header">
            <h2><i class="fas fa-lightbulb"></i> Tips</h2>
          </div>
          <div class="card-body">
            <ul class="tips-list">
              <li><i class="fas fa-check-circle"></i> Deskripsi studio menarik</li>
              <li><i class="fas fa-check-circle"></i> Update jam operasional</li>
              <li><i class="fas fa-check-circle"></i> Slot waktu fleksibel</li>
              <li><i class="fas fa-check-circle"></i> Verifikasi pembayaran cepat</li>
              <li><i class="fas fa-check-circle"></i> Share link di medsos</li>
            </ul>
          </div>
        </div>
      </div>

    </div>
  </main>

  <!-- Edit Modal -->
  <div class="modal-overlay" id="editModal">
    <div class="modal-content">
      <div class="modal-header">
        <h3>Edit Informasi Studio</h3>
        <button class="close-modal" onclick="closeEditModal()"><i class="fas fa-times"></i></button>
      </div>

      <form action="{{ route('staff.studio.update') }}" method="POST" enctype="multipart/form-data"
        style="display: flex; flex-direction: column; height: 100%; overflow: hidden;">
        @csrf
        @method('PUT')

        <div class="modal-body-scroll">
          <div class="form-group">
            <label>Nama Studio</label>
            <input type="text" name="name" class="form-control" value="{{ $studio->name }}" required
              placeholder="Contoh: Lensia Photography">
          </div>

          <div class="form-group">
            <label>Kota</label>
            <input type="text" name="city" class="form-control" value="{{ $studio->city }}" required
              placeholder="Contoh: Semarang">
          </div>

          <div class="form-group">
            <label>Alamat Lengkap</label>
            <textarea name="address" class="form-control" rows="3" required
              placeholder="Alamat lengkap studio...">{{ $studio->address }}</textarea>
          </div>

          <div class="form-group">
            <label>Deskripsi Studio</label>
            <textarea name="description" class="form-control" rows="5"
              placeholder="Ceritakan tentang studio anda...">{{ $studio->description }}</textarea>
          </div>

          <div class="form-group">
            <label>Banner Studio</label>
            <div class="file-upload-wrapper">
              <input type="file" name="image" id="imageInput" accept="image/*" onchange="previewFile()">
              <div class="file-upload-icon"><i class="fas fa-cloud-upload-alt"></i></div>
              <div class="file-upload-text" id="fileName">Klik atau geser foto ke sini</div>
              <div class="file-upload-info">JPG, PNG (Max: 2MB)</div>
            </div>
          </div>
        </div>

        <div class="modal-footer">
          <button type="submit" class="btn-save">
            <i class="fas fa-save" style="margin-right: 8px;"></i> Simpan Perubahan
          </button>
        </div>
      </form>
    </div>
  </div>

  <script src="{{ asset('js/staff/studio-preview.js') }}"></script>
@endsection