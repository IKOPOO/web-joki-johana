@extends('layouts.admin')

@section('title', 'User Management')

@section('styles')
  <link rel="stylesheet" href="{{ asset('css/admin/users.css') }}">
@endsection

@section('content')
  <!-- Header -->
  <header class="content-header">
    <div class="header-left">
      <h1>User Management</h1>
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

    <!-- Stats Cards Row -->
    <div class="stats-grid">
      <!-- Total Pengguna -->
      <div class="stat-card stat-total">
        <div class="stat-icon-wrapper total">
          <i class="fas fa-users"></i>
        </div>
        <div class="stat-content">
          <h3 class="stat-value">{{ $stats['total'] }}</h3>
          <p class="stat-label">Total Pengguna</p>
        </div>
        <div class="stat-footer">
          <span class="stat-trend positive"><i class="fas fa-arrow-up"></i> +12%</span>
          <span class="stat-desc">dari bulan lalu</span>
        </div>
      </div>

      <!-- Staff Studio -->
      <div class="stat-card stat-staff">
        <div class="stat-icon-wrapper staff">
          <i class="fas fa-user-tie"></i>
        </div>
        <div class="stat-content">
          <h3 class="stat-value">{{ $stats['staff'] }}</h3>
          <p class="stat-label">Staff Studio</p>
        </div>
        <div class="stat-footer">
          <span class="stat-trend neutral"><i class="fas fa-minus"></i> 0%</span>
          <span class="stat-desc">dari bulan lalu</span>
        </div>
      </div>

      <!-- Jumlah Customer -->
      <div class="stat-card stat-customer">
        <div class="stat-icon-wrapper customer">
          <i class="fas fa-user"></i>
        </div>
        <div class="stat-content">
          <h3 class="stat-value">{{ $stats['customer'] }}</h3>
          <p class="stat-label">Jumlah Customer</p>
        </div>
        <div class="stat-footer">
          <span class="stat-trend positive"><i class="fas fa-arrow-up"></i> +14%</span>
          <span class="stat-desc">dari bulan lalu</span>
        </div>
      </div>
    </div>

    <!-- Charts Row -->
    <div class="charts-grid-2col">
      @include('admin.partials.chart-card', [
        'title' => 'Segmentasi Pengguna',
        'subtitle' => 'Berdasarkan role pengguna',
        'chartId' => 'userSegmentationChart',
        'type' => 'pie'
      ])

      @include('admin.partials.chart-card', [
        'title' => 'Status Akun',
        'subtitle' => 'Aktif vs Non-aktif',
        'chartId' => 'userStatusChart',
        'type' => 'pie'
      ])
    </div>

    <!-- Users Table Section -->
    <div class="section-title" id="users-table">
      <h2><i class="fas fa-users-cog"></i> Daftar Pengguna</h2>
    </div>

    <div class="table-card">
      <!-- Table Toolbar -->
      <form action="{{ url('/admin/users') }}" method="GET" class="table-toolbar">
        <div class="toolbar-left">
          <button type="button" class="btn-add" onclick="openAddModal()">
            <i class="fas fa-plus"></i> Tambah User
          </button>
          <div class="search-box">
            <i class="fas fa-search"></i>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, email, atau no HP..." onchange="this.form.submit()">
          </div>
        </div>
        <div class="toolbar-right">
          <select name="role" class="status-filter" onchange="this.form.submit()">
            <option value="">Semua Role</option>
            <option value="LENSIA_ADMIN" {{ request('role') == 'LENSIA_ADMIN' ? 'selected' : '' }}>Admin</option>
            <option value="STUDIO_STAF" {{ request('role') == 'STUDIO_STAF' ? 'selected' : '' }}>Staff</option>
            <option value="CUSTOMER" {{ request('role') == 'CUSTOMER' ? 'selected' : '' }}>Customer</option>
          </select>
          <select name="status" class="status-filter" onchange="this.form.submit()">
            <option value="">Semua Status</option>
            <option value="ACTIVE" {{ request('status') == 'ACTIVE' ? 'selected' : '' }}>Active</option>
            <option value="SUSPENDED" {{ request('status') == 'SUSPENDED' ? 'selected' : '' }}>Suspended</option>
          </select>
          <button type="submit" name="export" value="true" class="btn-export" id="exportBtn">
            <i class="fas fa-file-export"></i> Export
          </button>
        </div>
      </form>

      <!-- Data Table -->
      <div class="table-responsive">
        <table class="data-table">
          <thead>
            <tr>
              <th>No</th>
              <th>Role</th>
              <th>Nama</th>
              <th>Email</th>
              <th>No HP</th>
              <th>Status</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse($users as $index => $user)
              <tr>
                <td>{{ $users->firstItem() + $index }}</td>
                <td>
                  @php
                    $roleClass = 'role-customer';
                    $roleName = 'Customer';
                    if ($user->role == 'LENSIA_ADMIN') {
                        $roleClass = 'role-admin';
                        $roleName = 'Admin';
                    } elseif ($user->role == 'STUDIO_STAF') {
                        $roleClass = 'role-staff';
                        $roleName = 'Staff';
                    }
                  @endphp
                  <span class="badge badge-role {{ $roleClass }}">{{ $roleName }}</span>
                </td>
                <td>
                  <div class="user-cell-info">
                    <div class="user-cell-avatar" style="background-color: {{ ['#FEC72E', '#28a745', '#dc3545', '#1976D2', '#e0e0e0'][rand(0,4)] }}">
                      {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <span class="user-name">{{ $user->name }}</span>
                  </div>
                </td>
                <td>{{ $user->email }}</td>
                <td><span class="phone-number">{{ $user->phone }}</span></td>
                <td>
                  @php
                    $statusClass = $user->status == 'ACTIVE' ? 'status-active' : 'status-inactive';
                    $statusName = $user->status == 'ACTIVE' ? 'Active' : 'Suspended';
                  @endphp
                  <span class="status-badge {{ $statusClass }}">{{ $statusName }}</span>
                </td>
                <td>
                  <div class="action-buttons">
                    <button class="btn-action btn-edit" title="Edit" onclick='openEditModal(@json($user))'>
                      <i class="fas fa-edit"></i>
                    </button>
                    <form id="delete-form-{{ $user->id }}" action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="d-inline">
                      @csrf
                      @method('DELETE')
                      <button type="button" class="btn-action btn-delete" title="Hapus" onclick="confirmDelete({{ $user->id }})">
                        <i class="fas fa-trash"></i>
                      </button>
                    </form>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="7" style="text-align: center; padding: 20px;">Data tidak ditemukan.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      <div class="table-pagination">
        <div class="pagination-info">
          Menampilkan {{ $users->firstItem() ?? 0 }}-{{ $users->lastItem() ?? 0 }} dari {{ $users->total() }} user
        </div>
        <div class="pagination-nav">
          @if ($users->onFirstPage())
            <button class="pagination-btn" disabled><i class="fas fa-chevron-left"></i> Prev</button>
          @else
            <a href="{{ $users->previousPageUrl() }}#users-table" class="pagination-btn"><i class="fas fa-chevron-left"></i> Prev</a>
          @endif

          <div class="pagination-pages">
            {{-- Simplified Pagination Elements --}}
            @foreach ($users->getUrlRange(max(1, $users->currentPage() - 1), min($users->lastPage(), $users->currentPage() + 1)) as $page => $url)
               <a href="{{ $url }}#users-table" class="page-btn {{ $page == $users->currentPage() ? 'active' : '' }}">{{ $page }}</a>
            @endforeach
          </div>

          @if ($users->hasMorePages())
            <a href="{{ $users->nextPageUrl() }}#users-table" class="pagination-btn">Next <i class="fas fa-chevron-right"></i></a>
          @else
            <button class="pagination-btn" disabled>Next <i class="fas fa-chevron-right"></i></button>
          @endif
        </div>
      </div>
    </div>
  </main>

  {{-- Edit User Modal Partial --}}
  @include('admin.partials.edit-user-modal')

  <!-- Add User Modal -->
  <div class="modal-overlay" id="addUserModal">
    <div class="modal-content">
      <div class="modal-header">
        <h3><i class="fas fa-plus-circle"></i> Tambah User Baru</h3>
        <button class="modal-close" onclick="closeAddModal()">&times;</button>
      </div>
      <form action="{{ route('admin.users.store') }}" method="POST">
        @csrf
        @include('admin.partials.forms.user-form', ['prefix' => 'add', 'showPassword' => true])
        <div class="modal-footer">
          <button type="button" class="btn-cancel" onclick="closeAddModal()">Batal</button>
          <button type="submit" class="btn-save">Simpan</button>
        </div>
      </form>
    </div>
  </div>
@endsection

@section('scripts')
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <!-- SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  
  <script>
    const usersData = {
      stats: {
        customer: {{ $stats['customer'] }},
        staff: {{ $stats['staff'] }},
        admin: {{ $stats['admin'] }},
        active: {{ $stats['active'] }},
        suspended: {{ $stats['suspended'] }}
      }
    };
  </script>
  <script src="{{ asset('js/admin/users.js') }}"></script>
@endsection
