@extends('layouts.admin')

@section('title', 'Booking Management')

@section('styles')
  <link rel="stylesheet" href="{{ asset('css/admin/booking.css') }}">
@endsection

@section('content')
  <!-- Header -->
  <header class="content-header">
    <div class="header-left">
      <h1>Booking Management</h1>
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

    <!-- Booking Stats Grid (3 columns x 2 rows) -->
    <div class="booking-stats-grid">
      <!-- 1. Booking Bulan Ini -->
      @include('admin.partials.booking-stat-card', [
        'modifier' => 'stat-month',
        'iconModifier' => 'month',
        'icon' => 'fas fa-calendar-alt',
        'value' => $monthlyBookings,
        'label' => 'Booking Bulan Ini',
        'incomeLabel' => 'Income Bulan Ini',
        'incomeValue' => $monthlyIncome
      ])

      <!-- 2. Booking Hari Ini -->
      @include('admin.partials.booking-stat-card', [
        'modifier' => 'stat-today',
        'iconModifier' => 'today',
        'icon' => 'fas fa-calendar-day',
        'value' => $todayBookings,
        'label' => 'Booking Hari Ini',
        'incomeLabel' => 'Income Hari Ini',
        'incomeValue' => $todayIncome
      ])

      <!-- 3. Menunggu Bayar (Pending & Unpaid) -->
      @include('admin.partials.booking-stat-card', [
        'modifier' => 'stat-pending',
        'iconModifier' => 'pending',
        'icon' => 'fas fa-clock',
        'value' => $waitingPayment,
        'label' => 'Menunggu Bayar',
        'incomeLabel' => 'Pending Income',
        'incomeValue' => $waitingPaymentIncome
      ])

      <!-- 4. Verifikasi (All Pending) -->
      @include('admin.partials.booking-stat-card', [
        'modifier' => 'stat-info',
        'iconModifier' => 'today',
        'icon' => 'fas fa-clipboard-check',
        'value' => $verificationPending,
        'label' => 'Verifikasi'
      ])

      <!-- 5. Di Konfirmasi -->
      @include('admin.partials.booking-stat-card', [
        'modifier' => 'stat-success',
        'iconModifier' => 'month',
        'icon' => 'fas fa-check-circle',
        'value' => $confirmedBookings,
        'label' => 'Di Konfirmasi'
      ])

      <!-- 6. Selesai -->
      @include('admin.partials.booking-stat-card', [
        'modifier' => 'stat-primary',
        'iconModifier' => 'pending', 
        'icon' => 'fas fa-flag-checkered',
        'value' => $completedBookings,
        'label' => 'Selesai'
      ])
    </div>

    <!-- Quick Stats Summary -->
    <div class="quick-stats">
      <div class="quick-stat-item">
        <span class="quick-label">Total Income:</span>
        <span class="quick-value">Rp {{ number_format($monthlyIncome, 0, ',', '.') }}</span>
      </div>
      <div class="quick-stat-divider"></div>
      <div class="quick-stat-item">
        <span class="quick-label">Paket Favorit:</span>
        <span class="quick-value">{{ $favoritePackage?->package?->name ?? '-' }}</span>
      </div>
      @if(auth()->user()->role === 'LENSIA_ADMIN')
        <div class="quick-stat-divider"></div>
        <div class="quick-stat-item">
          <span class="quick-label">Studio Favorit:</span>
          <span class="quick-value">{{ $favoriteStudio?->studio?->name ?? '-' }}</span>
        </div>
      @endif
    </div>

    <!-- Booking Table Section -->
    <div class="section-title" id="bookings-table">
      <h2><i class="fas fa-list"></i> Daftar Booking</h2>
    </div>

    <div class="table-card">
      <!-- Table Toolbar -->
      <div class="table-toolbar">
        <div class="toolbar-left">
          <button class="btn-add" onclick="openAddModal()">
            <i class="fas fa-plus"></i> Tambah Booking
          </button>
          <div class="search-box">
            <i class="fas fa-search"></i>
            <input type="text" placeholder="Cari nama, no HP, atau studio..." id="searchInput">
          </div>
        </div>
        <div class="toolbar-right">
          <select class="status-filter" id="statusFilter" onchange="filterByStatus()">
            <option value="">Semua Status</option>
            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
            <option value="done" {{ request('status') == 'done' ? 'selected' : '' }}>Done</option>
            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
          </select>
        </div>
      </div>

      <!-- Data Table -->
      <div class="table-responsive">
        <table class="data-table">
          <thead>
            <tr>
              <th>No</th>
              <th>Nama</th>
              <th>No HP</th>
              <th>Jadwal</th>
              @if(auth()->user()->role === 'LENSIA_ADMIN')
                <th>Studio</th>
              @endif
              <th>Paket</th>
              <th>Income</th>
              <th>Status</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody id="bookingTableBody">
            @forelse($bookings as $index => $booking)
              <tr>
                <td>{{ $bookings->firstItem() + $index }}</td>
                <td>
                  <div class="customer-info">
                    <span class="customer-name">{{ $booking->user?->name ?? 'N/A' }}</span>
                  </div>
                </td>
                <td><span class="phone-number">{{ $booking->user?->phone ?? '-' }}</span></td>
                <td>
                  <div class="schedule-info">
                    <span class="schedule-date">{{ $booking->booking_datetime?->format('d M Y') }}</span>
                    <span class="schedule-time">{{ $booking->booking_datetime?->format('H:i') }}</span>
                  </div>
                  </div>
                </td>
                @if(auth()->user()->role === 'LENSIA_ADMIN')
                  <td><span class="badge badge-studio">{{ $booking->studio?->name ?? '-' }}</span></td>
                @endif
                <td><span class="badge badge-package">{{ $booking->package?->name ?? '-' }}</span></td>
                <td><span class="income-amount">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</span></td>
                <td>
                  @php
                    $statusClass = match(strtoupper($booking->status)) {
                      'PENDING' => 'status-pending',
                      'CONFIRMED' => 'status-confirmed',
                      'DONE' => 'status-confirmed',
                      'CANCELLED' => 'status-cancelled',
                      default => 'status-pending'
                    };
                  @endphp
                  <span class="status-badge {{ $statusClass }}">{{ ucfirst(strtolower($booking->status)) }}</span>
                </td>
                <td>
                  <div class="action-buttons">
                    <button class="btn-action btn-view" title="Lihat Detail" onclick="openDetailModal({{ $booking->id }})">
                      <i class="fas fa-eye"></i>
                    </button>
                    <button class="btn-action btn-edit" title="Edit" onclick="openEditModal({{ json_encode($booking) }})">
                      <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn-action btn-delete" title="Hapus" onclick="confirmDelete({{ $booking->id }})">
                      <i class="fas fa-trash"></i>
                    </button>
                  </div>
                  <div id="booking-detail-{{ $booking->id }}" style="display:none;">
                    @include('admin.partials.booking-detail', ['booking' => $booking])
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="9" style="text-align: center; padding: 2rem;">Tidak ada booking ditemukan.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      @if($bookings->hasPages())
        <div class="table-pagination">
          <div class="pagination-info">
            Menampilkan {{ $bookings->firstItem() }}-{{ $bookings->lastItem() }} dari {{ $bookings->total() }} booking
          </div>
          <div class="pagination-nav">
            @if ($bookings->onFirstPage())
              <button class="pagination-btn" disabled>
                <i class="fas fa-chevron-left"></i> Prev
              </button>
            @else
              <a href="{{ $bookings->previousPageUrl() }}#bookings-table" class="pagination-btn">
                <i class="fas fa-chevron-left"></i> Prev
              </a>
            @endif

            <div class="pagination-pages">
              @php
                $currentPage = $bookings->currentPage();
                $lastPage = $bookings->lastPage();
                $start = max(1, $currentPage - 1);
                $end = min($lastPage, $currentPage + 1);
              @endphp
              
              @for ($page = $start; $page <= $end; $page++)
                @if ($page == $currentPage)
                  <button class="page-btn active">{{ $page }}</button>
                @else
                  <a href="{{ $bookings->url($page) }}#bookings-table" class="page-btn">{{ $page }}</a>
                @endif
              @endfor
            </div>

            @if ($bookings->hasMorePages())
              <a href="{{ $bookings->nextPageUrl() }}#bookings-table" class="pagination-btn">
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

  <!-- Add Booking Modal -->
  <div class="modal-overlay" id="addModal">
    <div class="modal-content">
      <div class="modal-header">
        <h3><i class="fas fa-plus-circle"></i> Tambah Booking Baru</h3>
        <button class="modal-close" onclick="closeAddModal()">&times;</button>
      </div>
      <form action="{{ route('admin.bookings.store') }}" method="POST">
        @csrf
        @include('admin.partials.forms.booking-form', ['prefix' => 'add', 'users' => $users, 'studios' => $studios])
        <div class="modal-footer">
          <button type="button" class="btn-cancel" onclick="closeAddModal()">Batal</button>
          <button type="submit" class="btn-save">Simpan</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Edit Booking Modal -->
  <div class="modal-overlay" id="editModal">
    <div class="modal-content">
      <div class="modal-header">
        <h3><i class="fas fa-edit"></i> Edit Booking</h3>
        <button class="modal-close" onclick="closeEditModal()">&times;</button>
      </div>
      <form id="editForm" method="POST">
        @csrf
        @method('PUT')
        @include('admin.partials.forms.booking-form', ['prefix' => 'edit', 'users' => $users, 'studios' => $studios])
        <div class="modal-footer">
          <button type="button" class="btn-cancel" onclick="closeEditModal()">Batal</button>
          <button type="submit" class="btn-save">Simpan Perubahan</button>
        </div>
      </form>
    </div>
  </div>

  <!-- View Booking Detail Modal -->
  <div class="modal-overlay" id="detailModal">
    <div class="modal-content" style="max-width: 500px;">
      <div class="modal-header">
        <h3><i class="fas fa-info-circle"></i> Detail Booking</h3>
        <button class="modal-close" onclick="closeDetailModal()">&times;</button>
      </div>
      <div class="modal-body" id="detailModalBody">
        <!-- Content injected here -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn-cancel" onclick="closeDetailModal()">Tutup</button>
      </div>
    </div>
  </div>

  <!-- Delete Confirmation Modal -->
  <div class="modal-overlay" id="deleteModal">
    <div class="modal-content">
      <div class="modal-header">
        <h3><i class="fas fa-exclamation-triangle" style="color: #dc3545;"></i> Konfirmasi Hapus</h3>
        <button class="modal-close" onclick="closeDeleteModal()">&times;</button>
      </div>
      <div class="modal-body">
        <p>Apakah Anda yakin ingin menghapus booking ini?</p>
        <p class="modal-warning" style="color: #999; font-size: 0.85rem;">Tindakan ini tidak dapat dibatalkan.</p>
      </div>
      <form id="deleteForm" method="POST">
        @csrf
        @method('DELETE')
        <div class="modal-footer">
          <button type="button" class="btn-cancel" onclick="closeDeleteModal()">Batal</button>
          <button type="submit" class="btn-save btn-confirm-delete">Hapus</button>
        </div>
      </form>
    </div>
  </div>
@endsection

@section('scripts')
  @section('scripts')
  <script>
    // Packages data for dynamic dropdown
    const packagesData = @json($packages);
  </script>
  <script src="{{ asset('js/admin/booking.js') }}"></script>
@endsection
@endsection