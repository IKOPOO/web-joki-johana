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
    @if(auth()->user()->role === 'STUDIO_STAF')
      <div class="dashboard-welcome">
        <h2>Selamat Datang, {{ session('user_name', 'User') }}!</h2>
        <p>Anda login sebagai <strong>Staff {{ auth()->user()->studio->name ?? 'Studio' }}</strong></p>
      </div>
    @endif

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

    <!-- Stats Cards Row -->
    <div class="booking-stats-grid">
      <!-- Booking Pending -->
      @include('admin.partials.booking-stat-card', [
        'modifier' => 'stat-pending',
        'iconModifier' => 'pending',
        'icon' => 'fas fa-clock',
        'value' => $pendingBookings,
        'label' => 'Booking Pending',
        'incomeLabel' => 'Pending Income',
        'incomeValue' => $pendingIncome
      ])

      <!-- Booking Hari Ini -->
      @include('admin.partials.booking-stat-card', [
        'modifier' => 'stat-today',
        'iconModifier' => 'today',
        'icon' => 'fas fa-calendar-day',
        'value' => $todayBookings,
        'label' => 'Booking Hari Ini',
        'incomeLabel' => 'Income Hari Ini',
        'incomeValue' => $todayIncome
      ])

      <!-- Booking Bulan Ini -->
      @include('admin.partials.booking-stat-card', [
        'modifier' => 'stat-month',
        'iconModifier' => 'month',
        'icon' => 'fas fa-calendar-alt',
        'value' => $monthlyBookings,
        'label' => 'Booking Bulan Ini',
        'incomeLabel' => 'Income Bulan Ini',
        'incomeValue' => $monthlyIncome
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
              <th>Studio</th>
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
                </td>
                <td><span class="badge badge-studio">{{ $booking->studio?->name ?? '-' }}</span></td>
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
                    <button class="btn-action btn-edit" title="Edit" onclick="openEditModal({{ json_encode($booking) }})">
                      <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn-action btn-delete" title="Hapus" onclick="confirmDelete({{ $booking->id }})">
                      <i class="fas fa-trash"></i>
                    </button>
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
  <script>
    // Packages data for dynamic dropdown
    const packagesData = @json($packages);

    // Status filter
    function filterByStatus() {
      const status = document.getElementById('statusFilter').value;
      const url = new URL(window.location.href);

      if (status) {
        url.searchParams.set('status', status);
      } else {
        url.searchParams.delete('status');
      }
      url.searchParams.delete('page');
      window.location.href = url.toString();
    }

    // Client-side search
    const searchInput = document.getElementById('searchInput');
    const tableBody = document.getElementById('bookingTableBody');

    if (searchInput) {
      searchInput.addEventListener('input', function () {
        const searchTerm = this.value.toLowerCase();
        const rows = tableBody.querySelectorAll('tr');

        rows.forEach(row => {
          const name = row.querySelector('.customer-name')?.textContent.toLowerCase() || '';
          const phone = row.querySelector('.phone-number')?.textContent.toLowerCase() || '';
          const studio = row.querySelector('.badge-studio')?.textContent.toLowerCase() || '';

          const matches = name.includes(searchTerm) || phone.includes(searchTerm) || studio.includes(searchTerm);
          row.style.display = matches ? '' : 'none';
        });
      });
    }

    // Load packages based on studio
    function loadPackages(studioId, prefix) {
      const packageSelect = document.getElementById(prefix + '_package_id');
      packageSelect.innerHTML = '<option value="">Pilih Paket</option>';

      if (studioId) {
        const studioPackages = packagesData.filter(p => p.studio_id == studioId);
        studioPackages.forEach(pkg => {
          const option = document.createElement('option');
          option.value = pkg.id;
          option.textContent = `${pkg.name} - Rp ${Number(pkg.price).toLocaleString('id-ID')}`;
          packageSelect.appendChild(option);
        });
      }
    }

    // Add Modal
    function openAddModal() {
      document.getElementById('addModal').classList.add('active');
    }

    function closeAddModal() {
      document.getElementById('addModal').classList.remove('active');
    }

    // Edit Modal
    function openEditModal(booking) {
      const form = document.getElementById('editForm');
      form.action = `/admin/bookings/${booking.id}`;
      
      document.getElementById('edit_user_id').value = booking.user_id;
      document.getElementById('edit_studio_id').value = booking.studio_id;
      
      // Load packages for the studio first
      loadPackages(booking.studio_id, 'edit');
      // Then set the package after a small delay to ensure options are loaded
      setTimeout(() => {
        document.getElementById('edit_package_id').value = booking.package_id;
      }, 100);
      
      // Format date and time for inputs
      if (booking.booking_datetime) {
        const dt = new Date(booking.booking_datetime);
        const dateStr = dt.toISOString().slice(0, 10);
        const timeStr = dt.toTimeString().slice(0, 5);
        document.getElementById('edit_booking_date').value = dateStr;
        document.getElementById('edit_booking_time').value = timeStr;
      }
      
      document.getElementById('edit_note').value = booking.note || '';
      document.getElementById('edit_status').value = booking.status;
      document.getElementById('edit_payment_status').value = booking.payment_status;
      
      document.getElementById('editModal').classList.add('active');
    }

    function closeEditModal() {
      document.getElementById('editModal').classList.remove('active');
    }

    // Delete Modal
    function confirmDelete(id) {
      const form = document.getElementById('deleteForm');
      form.action = `/admin/bookings/${id}`;
      document.getElementById('deleteModal').classList.add('active');
    }

    function closeDeleteModal() {
      document.getElementById('deleteModal').classList.remove('active');
    }

    // Close modals on backdrop click
    document.querySelectorAll('.modal-overlay').forEach(modal => {
      modal.addEventListener('click', function(e) {
        if (e.target === this) {
          this.classList.remove('active');
        }
      });
    });

    // Close on escape key
    document.addEventListener('keydown', function(e) {
      if (e.key === 'Escape') {
        document.querySelectorAll('.modal-overlay.active').forEach(modal => {
          modal.classList.remove('active');
        });
      }
    });

    // Auto-hide alerts after 5 seconds
    document.querySelectorAll('.alert').forEach(alert => {
      setTimeout(() => {
        alert.style.transition = 'opacity 0.5s ease';
        alert.style.opacity = '0';
        setTimeout(() => alert.remove(), 500);
      }, 5000);
    });
  </script>
@endsection