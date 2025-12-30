@extends('layouts.admin')

@section('title', 'Manajemen Operasional')

@section('styles')
  <link rel="stylesheet" href="{{ asset('css/admin/booking.css') }}">
  <link rel="stylesheet" href="{{ asset('css/admin/packages.css') }}">
  <link rel="stylesheet" href="{{ asset('css/staff/operational.css') }}">
@endsection

@section('content')
  <!-- Header -->
  <header class="content-header">
    <div class="header-left">
      <h1>Management Operational</h1>
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
    <!-- Page Title Section -->
    <div class="page-title-section">
      <h1 class="main-title">Jam Operasional</h1>
      <p class="subtitle">Studio: <span class="studio-name">{{ auth()->user()->studio->name ?? 'Studio' }}</span> | Tanggal: <span class="studio-name">{{ now()->format('d F Y') }}</span></p>
    </div>

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
    <div class="table-card">
      <form action="{{ route('staff.operational.update') }}" method="POST">
        @csrf
        <div class="table-responsive">
          <table class="data-table">
            <thead>
              <tr>
                <th>Hari</th>
                <th>Jam Buka</th>
                <th>Jam Tutup</th>
                <th>Status</th>
                <th>Aksi Cepat</th>
              </tr>
            </thead>
            <tbody>
              @foreach($operationalHours as $hour)
                <tr>
                  <td>
                    <div class="customer-info">
                      <span class="customer-name">{{ $hour->day_name }}</span>
                    </div>
                  </td>
                  <td>
                    <input type="text" name="hours[{{ $hour->id }}][opening_time]" 
                           value="{{ $hour->opening_time ? \Carbon\Carbon::parse($hour->opening_time)->format('H:i') : '09:00' }}"
                           class="form-control-24h" placeholder="09:00">
                  </td>
                  <td>
                    <input type="text" name="hours[{{ $hour->id }}][closing_time]" 
                           value="{{ $hour->closing_time ? \Carbon\Carbon::parse($hour->closing_time)->format('H:i') : '21:00' }}"
                           class="form-control-24h" placeholder="21:00">
                  </td>
                  <td>
                    <span class="status-badge {{ $hour->is_closed ? 'status-closed' : 'status-open' }}">
                      {{ $hour->is_closed ? 'Tutup' : 'Buka' }}
                    </span>
                  </td>
                  <td>
                    <div class="form-check">
                      <input type="checkbox" name="hours[{{ $hour->id }}][is_closed]" 
                             id="closed_{{ $hour->id }}" 
                             {{ $hour->is_closed ? 'checked' : '' }}>
                      <label for="closed_{{ $hour->id }}">Tandai Tutup</label>
                    </div>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>

        <div class="table-toolbar" style="justify-content: flex-end; padding: 20px;">
          <button type="submit" class="btn-save">
            <i class="fas fa-save"></i> Simpan Perubahan
          </button>
        </div>
      </form>
    </div>
  </main>
@endsection

@section('scripts')
  <script src="{{ asset('js/admin/operational.js') }}"></script>
@endsection