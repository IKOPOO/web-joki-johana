@extends('layouts.admin')

@section('title', 'Studio & Package')

@section('styles')
  <link rel="stylesheet" href="{{ asset('css/admin/booking.css') }}">
  <link rel="stylesheet" href="{{ asset('css/admin/sourcePackage.css') }}">
@endsection

@section('content')
  <header class="content-header">
    <div class="header-left">
      <h1>Studio & Package</h1>
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

    <!-- Stats Grid -->
    <div class="stats-grid">
      <div class="stat-card">
        <div class="stat-icon bg-blue">
          <i class="fas fa-building"></i>
        </div>
        <div class="stat-info">
          <h3>{{ $totalStudios }}</h3>
          <p>Total Studio</p>
        </div>
      </div>
      <div class="stat-card">
        <div class="stat-icon bg-green">
          <i class="fas fa-check-circle"></i>
        </div>
        <div class="stat-info">
          <h3>{{ $activeStudios }}</h3>
          <p>Studio Aktif</p>
        </div>
      </div>
      <div class="stat-card">
        <div class="stat-icon bg-purple">
          <i class="fas fa-box-open"></i>
        </div>
        <div class="stat-info">
          <h3>{{ $totalPackages }}</h3>
          <p>Total Package</p>
        </div>
      </div>
      <div class="stat-card">
        <div class="stat-icon bg-orange">
          <i class="fas fa-tags"></i>
        </div>
        <div class="stat-info">
          <h3>{{ $activePackages }}</h3>
          <p>Package Aktif</p>
        </div>
      </div>
    </div>

    <!-- Studio Table Section -->
    <div class="section-title">
      <h2><i class="fas fa-list"></i> Daftar Studio</h2>
    </div>

    <div class="table-card">
      <!-- Table Toolbar -->
      <div class="table-toolbar">
        <div class="toolbar-left">
          <button class="btn-add" onclick="openAddStudioModal()">
            <i class="fas fa-plus"></i> Tambah Studio
          </button>
          <div class="search-box">
            <i class="fas fa-search"></i>
            <input type="text" placeholder="Cari studio..." id="searchInput">
          </div>
        </div>
        <div class="toolbar-right">
          <select class="status-filter" id="statusFilter" onchange="filterByStatus()">
            <option value="">Semua Status</option>
            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
          </select>
        </div>
      </div>

      <!-- Data Table -->
      <div class="table-responsive">
        <table class="data-table">
          <thead>
            <tr>
              <th>No</th>
              <th>Nama Studio</th>
              <th>Alamat</th>
              <th>Kota</th>
              <th>Status</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody id="studioTableBody">
            @forelse($studios as $index => $studio)
              <tr>
                <td>{{ $studios->firstItem() + $index }}</td>
                <td>
                  <div class="customer-info">
                    <span class="customer-name">{{ $studio->name }}</span>
                  </div>
                </td>
                <td>{{ $studio->address }}</td>
                <td>{{ $studio->city }}</td>
                <td>
                  <span class="status-badge {{ $studio->status == 'active' ? 'status-confirmed' : 'status-cancelled' }}">
                    {{ ucfirst($studio->status) }}
                  </span>
                </td>
                <td>
                  <div class="action-buttons">
                    <a href="{{ route('admin.packages.index', $studio) }}" class="btn-view-package">
                      View Package
                    </a>
                    <button class="btn-action btn-edit" title="Edit"
                      onclick="openEditStudioModal({{ json_encode($studio) }})">
                      <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn-action btn-delete" title="Hapus"
                      onclick="openDeleteStudioModal({{ $studio->id }}, '{{ $studio->name }}', {{ $studio->packages_count }})">
                      <i class="fas fa-trash"></i>
                    </button>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="6" style="text-align: center; padding: 2rem;">Tidak ada studio ditemukan.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      @if($studios->hasPages())
        <div class="table-pagination">
          <div class="pagination-info">
            Menampilkan {{ $studios->firstItem() }}-{{ $studios->lastItem() }} dari {{ $studios->total() }} studio
          </div>
          <div class="pagination-nav">
            @if ($studios->onFirstPage())
              <button class="pagination-btn" disabled>
                <i class="fas fa-chevron-left"></i> Prev
              </button>
            @else
              <a href="{{ $studios->previousPageUrl() }}" class="pagination-btn">
                <i class="fas fa-chevron-left"></i> Prev
              </a>
            @endif

            <div class="pagination-pages">
              @php
                $currentPage = $studios->currentPage();
                $lastPage = $studios->lastPage();
                $start = max(1, $currentPage - 1);
                $end = min($lastPage, $currentPage + 1);
              @endphp

              @for ($page = $start; $page <= $end; $page++)
                @if ($page == $currentPage)
                  <button class="page-btn active">{{ $page }}</button>
                @else
                  <a href="{{ $studios->url($page) }}" class="page-btn">{{ $page }}</a>
                @endif
              @endfor
            </div>

            @if ($studios->hasMorePages())
              <a href="{{ $studios->nextPageUrl() }}" class="pagination-btn">
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

  <!-- Add Studio Modal -->
  <div class="modal-overlay" id="addStudioModal">
    <div class="modal-content">
      <div class="modal-header">
        <h3><i class="fas fa-plus-circle"></i> Tambah Studio Baru</h3>
        <button class="modal-close" onclick="closeAddStudioModal()">&times;</button>
      </div>
      <form action="{{ route('admin.studios.store') }}" method="POST">
        @csrf
        @include('admin.partials.forms.studio-form', ['prefix' => 'add'])
        <div class="modal-footer">
          <button type="button" class="btn-cancel" onclick="closeAddStudioModal()">Batal</button>
          <button type="submit" class="btn-save">Simpan</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Edit Studio Modal -->
  <div class="modal-overlay" id="editStudioModal">
    <div class="modal-content">
      <div class="modal-header">
        <h3><i class="fas fa-edit"></i> Edit Studio</h3>
        <button class="modal-close" onclick="closeEditStudioModal()">&times;</button>
      </div>
      <form id="editStudioForm" method="POST">
        @csrf
        @method('PUT')
        @include('admin.partials.forms.studio-form', ['prefix' => 'edit_studio'])
        <div class="modal-footer">
          <button type="button" class="btn-cancel" onclick="closeEditStudioModal()">Batal</button>
          <button type="submit" class="btn-save">Simpan Perubahan</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Delete Studio Modal -->
  <div class="modal-overlay" id="deleteStudioModal">
    <div class="modal-content">
      <div class="modal-header">
        <h3><i class="fas fa-exclamation-triangle" style="color: #dc3545;"></i> Konfirmasi Hapus</h3>
        <button class="modal-close" onclick="closeDeleteStudioModal()">&times;</button>
      </div>
      <div class="modal-body">
        <p>Apakah Anda yakin ingin menghapus studio <strong id="deleteStudioName"></strong>?</p>
        <p id="deleteWarningPackages" style="color: #dc3545; font-size: 0.9rem; display: none;">
          <i class="fas fa-exclamation-circle"></i> <span id="packageCountText"></span> package yang terkait juga akan
          terhapus!
        </p>
        <p style="color: #999; font-size: 0.85rem; margin-top: 0.5rem;">Tindakan ini tidak dapat dibatalkan.</p>
      </div>
      <form id="deleteStudioForm" method="POST">
        @csrf
        @method('DELETE')
        <div class="modal-footer">
          <button type="button" class="btn-cancel" onclick="closeDeleteStudioModal()">Batal</button>
          <button type="submit" class="btn-save btn-confirm-delete">Hapus</button>
        </div>
      </form>
    </div>
  </div>
@endsection

@section('scripts')
  <script src="{{ asset('js/admin/source-package.js') }}"></script>
@endsection