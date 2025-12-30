@extends('layouts.admin')

@section('title', 'Packages - ' . $studio->name)

@section('styles')
  <link rel="stylesheet" href="{{ asset('css/admin/booking.css') }}">
  <link rel="stylesheet" href="{{ asset('css/admin/packages.css') }}">
@endsection

@section('content')
  <header class="content-header">
    <div class="header-left">
      <h1>Package Management</h1>
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

    @if($errors->any())
      <div class="alert alert-error">
        <i class="fas fa-exclamation-circle"></i>
        <ul style="margin: 0; padding-left: 1.5rem;">
          @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <!-- Back Button -->
    @if(auth()->user()->role === 'LENSIA_ADMIN')
      <a href="{{ route('admin.studios.index') }}" class="btn-back">
        <i class="fas fa-arrow-left"></i> Kembali
      </a>
    @endif

    <!-- Page Title -->
    <div class="page-title-section" id="packages-table">
      <h1 class="main-title">Daftar Package</h1>
      <p class="subtitle">Studio: <span class="studio-name">{{ $studio->name }}</span></p>
    </div>

    <div class="table-card">
      <!-- Table Toolbar -->
      <div class="table-toolbar">
        <div class="toolbar-left">
          <button class="btn-add" onclick="openAddModal()">
            <i class="fas fa-plus"></i> Tambah Package
          </button>
        </div>
        <div class="toolbar-right">
          <select class="status-filter" id="statusFilter" onchange="filterByStatus()">
            <option value="">Semua Status</option>
            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
          </select>
        </div>
      </div>

      <!-- Data Table -->
      <div class="table-responsive">
        <table class="data-table">
          <thead>
            <tr>
              <th>No</th>
              <th>Nama Package</th>
              <th>Deskripsi</th>
              <th>Durasi</th>
              <th>Harga</th>
              <th>Status</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse($packages as $index => $package)
              <tr>
                <td>{{ $packages->firstItem() + $index }}</td>
                <td>
                  <div class="customer-info">
                    <span class="customer-name">{{ $package->name }}</span>
                  </div>
                </td>
                <td>{{ Str::limit($package->description, 50) }}</td>
                <td>{{ $package->duration_minutes }} menit</td>
                <td class="price-cell">Rp {{ number_format($package->price, 0, ',', '.') }}</td>
                <td>
                  <span class="status-badge {{ $package->is_active ? 'status-confirmed' : 'status-cancelled' }}">
                    {{ $package->is_active ? 'Aktif' : 'Tidak Aktif' }}
                  </span>
                </td>
                <td>
                  <div class="action-buttons">
                    <button class="btn-action btn-edit" title="Edit" onclick="openEditModal({{ json_encode($package) }})">
                      <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn-action btn-delete" title="Hapus"
                      onclick="openDeleteModal({{ $package->id }}, '{{ $package->name }}')">
                      <i class="fas fa-trash"></i>
                    </button>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="7" style="text-align: center; padding: 2rem;">Tidak ada package ditemukan.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      @if($packages->hasPages())
        <div class="table-pagination">
          <div class="pagination-info">
            Menampilkan {{ $packages->firstItem() }}-{{ $packages->lastItem() }} dari {{ $packages->total() }} package
          </div>
          <div class="pagination-nav">
            @if ($packages->onFirstPage())
              <button class="pagination-btn" disabled>
                <i class="fas fa-chevron-left"></i> Prev
              </button>
            @else
              <a href="{{ $packages->previousPageUrl() }}#packages-table" class="pagination-btn">
                <i class="fas fa-chevron-left"></i> Prev
              </a>
            @endif

            <div class="pagination-pages">
              @php
                $currentPage = $packages->currentPage();
                $lastPage = $packages->lastPage();
                $start = max(1, $currentPage - 1);
                $end = min($lastPage, $currentPage + 1);
              @endphp

              @for ($page = $start; $page <= $end; $page++)
                @if ($page == $currentPage)
                  <button class="page-btn active">{{ $page }}</button>
                @else
                  <a href="{{ $packages->url($page) }}#packages-table" class="page-btn">{{ $page }}</a>
                @endif
              @endfor
            </div>

            @if ($packages->hasMorePages())
              <a href="{{ $packages->nextPageUrl() }}#packages-table" class="pagination-btn">
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

  <!-- Add Package Modal -->
  <div class="modal-overlay" id="addModal">
    <div class="modal-content">
      <div class="modal-header">
        <h3><i class="fas fa-plus-circle"></i> Tambah Package Baru</h3>
        <button class="modal-close" onclick="closeAddModal()">&times;</button>
      </div>
      <form action="{{ route('admin.packages.store', $studio) }}" method="POST">
        @csrf
        @include('admin.partials.forms.package-form', ['prefix' => 'add', 'checked' => true])
        <div class="modal-footer">
          <button type="button" class="btn-cancel" onclick="closeAddModal()">Batal</button>
          <button type="submit" class="btn-save">Simpan</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Edit Package Modal -->
  <div class="modal-overlay" id="editModal">
    <div class="modal-content">
      <div class="modal-header">
        <h3><i class="fas fa-edit"></i> Edit Package</h3>
        <button class="modal-close" onclick="closeEditModal()">&times;</button>
      </div>
      <form id="editForm" method="POST">
        @csrf
        @method('PUT')
        @include('admin.partials.forms.package-form', ['prefix' => 'edit', 'checked' => false])
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
        <p>Apakah Anda yakin ingin menghapus package <strong id="deletePackageName"></strong>?</p>
        <p style="color: #dc3545; font-size: 0.9rem;">Tindakan ini tidak dapat dibatalkan.</p>
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
    const studioId = {{ $studio->id }};
  </script>
  <script src="{{ asset('js/admin/packages.js') }}"></script>
@endsection