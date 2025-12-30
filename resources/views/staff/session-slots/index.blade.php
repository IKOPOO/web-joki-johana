@extends('layouts.admin')

@section('title', 'Slot Sesi')

@section('styles')
  <link rel="stylesheet" href="{{ asset('css/admin/booking.css') }}">
  <link rel="stylesheet" href="{{ asset('css/admin/booking.css') }}">
  <link rel="stylesheet" href="{{ asset('css/admin/packages.css') }}">
  <link rel="stylesheet" href="{{ asset('css/admin/session-slots.css') }}">
@endsection

@section('content')
  <!-- Header -->
  <header class="content-header">
    <div class="header-left">
      <h1>Slot Sesi Management</h1>
    </div>
    <div class="header-right">
      <div class="user-info">
        <div class="user-avatar">
          <i class="fas fa-user-circle"></i>
        </div>
        <div class="user-details">
          <span class="user-name">{{ session('user_name', 'User') }}</span>
          <span class="user-role">{{ ucfirst(session('user_role', 'staff')) }}</span>
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

    <!-- Page Title Section -->
    <div class="page-title-section" id="slots-table">
      <h1 class="main-title">Daftar Slot Sesi</h1>
      <p class="subtitle">Studio: <span class="studio-name">{{ auth()->user()->studio->name ?? 'Studio' }}</span> |
        Tanggal: <span class="studio-name">{{ \Carbon\Carbon::parse($date)->format('d F Y') }}</span></p>
    </div>

    <div class="table-card">
      <!-- Table Toolbar -->
      <div class="table-toolbar">
        <div class="toolbar-left">
          <button class="btn-generate" onclick="openGenerateModal()">
            <i class="fas fa-magic"></i> Generate Slot Masal
          </button>
          <button class="btn-add" onclick="openAddModal()">
            <i class="fas fa-plus"></i> Tambah Slot Manual
          </button>
          <button class="btn-delete"
            style="background: #dc3545; color: white; border: none; padding: 10px 20px; border-radius: 10px; font-weight: 600; cursor: pointer; margin-left: 10px;"
            onclick="openResetModal()">
            <i class="fas fa-trash-alt"></i> Reset Slot
          </button>
        </div>
        <div class="toolbar-right">
          <form action="{{ route('staff.session-slots.index') }}" method="GET" style="margin: 0;">
            <input type="date" name="date" value="{{ $date }}" onchange="this.form.submit()" class="toolbar-date-input"
              title="Filter Tanggal">
          </form>
        </div>
      </div>

      <!-- Data Table -->
      <div class="table-responsive">
        <table class="data-table">
          <thead>
            <tr>
              <th>No</th>
              <th>Jam Mulai</th>
              <th>Jam Selesai</th>
              <th>Status</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse($sessionSlots as $index => $slot)
              <tr>
                <td>{{ $index + 1 }}</td>
                <td>
                  <div class="customer-info">
                    <span class="customer-name">{{ \Carbon\Carbon::parse($slot->start_time)->format('H:i') }}</span>
                  </div>
                </td>
                <td>
                  <div class="customer-info">
                    <span class="customer-name">{{ \Carbon\Carbon::parse($slot->end_time)->format('H:i') }}</span>
                  </div>
                </td>
                <td>
                  <span class="status-badge {{ $slot->is_active ? 'status-active' : 'status-inactive' }}">
                    {{ $slot->is_active ? 'Aktif' : 'Tidak Aktif' }}
                  </span>
                </td>
                <td>
                  <div class="action-buttons">
                    <form action="{{ route('staff.session-slots.toggle', $slot) }}" method="POST" style="display: inline;">
                      @csrf
                      <button type="submit" class="btn-action" title="{{ $slot->is_active ? 'Nonaktifkan' : 'Aktifkan' }}"
                        style="background: {{ $slot->is_active ? '#dc3545' : '#28a745' }}; color: white;">
                        <i class="fas fa-power-off"></i>
                      </button>
                    </form>
                    <button class="btn-action btn-edit" title="Edit" onclick="openEditModal({{ json_encode($slot) }})">
                      <i class="fas fa-edit"></i>
                    </button>
                    <form id="delete-form-{{ $slot->id }}" action="{{ route('staff.session-slots.destroy', $slot) }}"
                      method="POST" style="display: inline;">
                      @csrf
                      @method('DELETE')
                      <button type="button" class="btn-action btn-delete" title="Hapus"
                        onclick="confirmDelete({{ $slot->id }})">
                        <i class="fas fa-trash"></i>
                      </button>
                    </form>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="5" style="text-align: center; padding: 2rem;">Belum ada slot sesi untuk tanggal ini.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </main>

  <!-- Generate Modal -->
  <div class="modal-overlay" id="generateModal">
    <div class="modal-content">
      <div class="modal-header">
        <h3><i class="fas fa-magic"></i> Generate Slot Sesi Masal</h3>
        <button class="modal-close" onclick="closeGenerateModal()">&times;</button>
      </div>
      <form action="{{ route('staff.session-slots.generate') }}" method="POST">
        @csrf
        <div class="form-group">
          <label for="gen_date">Tanggal</label>
          <input type="date" name="date" id="gen_date" value="{{ $date }}" required>
        </div>
        <div class="form-row">
          <div class="form-group">
            <label for="gen_start_time">Jam Mulai</label>
            <input type="text" name="start_time" id="gen_start_time" placeholder="10:00" required>
          </div>
          <div class="form-group">
            <label for="gen_end_time">Jam Akhir</label>
            <input type="text" name="end_time" id="gen_end_time" placeholder="15:00" required>
          </div>
        </div>
        <div class="form-group">
          <label for="gen_duration">Durasi per Sesi (Menit)</label>
          <input type="number" name="duration" id="gen_duration" placeholder="30" value="30" required>
          <small style="color: #666;">Contoh: 30 untuk sesi 30 menit.</small>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn-cancel" onclick="closeGenerateModal()">Batal</button>
          <button type="submit" class="btn-save">Generate Sekarang</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Reset Slot Modal -->
  <div class="modal-overlay" id="resetModal">
    <div class="modal-content">
      <div class="modal-header">
        <h3><i class="fas fa-trash-alt" style="color: #dc3545;"></i> Reset / Hapus Slot Sesi</h3>
        <button class="modal-close" onclick="closeResetModal()">&times;</button>
      </div>
      <form id="resetForm" action="{{ route('staff.session-slots.reset') }}" method="POST">
        @csrf
        <div class="modal-body">
          <div class="alert alert-error" style="margin-bottom: 15px;">
            <i class="fas fa-exclamation-triangle"></i>
            PERINGATAN: Tindakan ini permanen. Slot yang dihapus tidak dapat dikembalikan.
          </div>
          <div class="form-group">
            <label for="reset_start_date">Dari Tanggal</label>
            <input type="date" name="start_date" id="reset_start_date" value="{{ date('Y-m-d') }}" required>
          </div>
          <div class="form-group">
            <label for="reset_end_date">Sampai Tanggal</label>
            <input type="date" name="end_date" id="reset_end_date" value="{{ date('Y-m-d') }}" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn-cancel" onclick="closeResetModal()">Batal</button>
          <button type="submit" class="btn-save" style="background: #dc3545; color: white;">Hapus Slot</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Add Slot Modal -->
  <div class="modal-overlay" id="addModal">
    <div class="modal-content">
      <div class="modal-header">
        <h3><i class="fas fa-plus-circle"></i> Tambah Slot Sesi Manual</h3>
        <button class="modal-close" onclick="closeAddModal()">&times;</button>
      </div>
      <form action="{{ route('staff.session-slots.store') }}" method="POST">
        @csrf
        <div class="form-group">
          <label for="add_date">Tanggal</label>
          <input type="date" name="date" id="add_date" value="{{ $date }}" required>
        </div>
        <div class="form-row">
          <div class="form-group">
            <label for="start_time">Jam Mulai</label>
            <input type="text" name="start_time" id="start_time" placeholder="09:00" required>
          </div>
          <div class="form-group">
            <label for="end_time">Jam Selesai</label>
            <input type="text" name="end_time" id="end_time" placeholder="10:00" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn-cancel" onclick="closeAddModal()">Batal</button>
          <button type="submit" class="btn-save">Simpan</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Edit Slot Modal -->
  <div class="modal-overlay" id="editModal">
    <div class="modal-content">
      <div class="modal-header">
        <h3><i class="fas fa-edit"></i> Edit Slot Sesi</h3>
        <button class="modal-close" onclick="closeEditModal()">&times;</button>
      </div>
      <form id="editForm" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
          <label for="edit_date">Tanggal</label>
          <input type="date" name="date" id="edit_date" required>
        </div>
        <div class="form-row">
          <div class="form-group">
            <label for="edit_start_time">Jam Mulai</label>
            <input type="text" name="start_time" id="edit_start_time" placeholder="09:00" required>
          </div>
          <div class="form-group">
            <label for="edit_end_time">Jam Selesai</label>
            <input type="text" name="end_time" id="edit_end_time" placeholder="10:00" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn-cancel" onclick="closeEditModal()">Batal</button>
          <button type="submit" class="btn-save">Simpan Perubahan</button>
        </div>
      </form>
    </div>
  </div>
@endsection

@section('scripts')
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="{{ asset('js/admin/session-slots.js') }}"></script>
@endsection