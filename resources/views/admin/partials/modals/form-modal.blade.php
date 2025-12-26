{{--
Form Modal Wrapper Partial
Usage:
@include('admin.partials.modals.form-modal', [
'id' => 'addBookingModal',
'formId' => 'addBookingForm',
'title' => 'Tambah Booking Baru',
'icon' => 'fas fa-plus-circle',
'action' => route('admin.bookings.store'),
'method' => 'POST', // 'POST' for create, 'PUT' for update
'closeFunction' => 'closeAddModal',
'submitText' => 'Simpan',
'slot' => $formContent // The form fields content
])
--}}
<div class="modal-overlay" id="{{ $id }}">
  <div class="modal-content">
    <div class="modal-header">
      <h3><i class="{{ $icon ?? 'fas fa-edit' }}"></i> {{ $title }}</h3>
      <button class="modal-close" onclick="{{ $closeFunction }}()">&times;</button>
    </div>
    <form id="{{ $formId ?? '' }}" action="{{ $action ?? '' }}" method="POST">
      @csrf
      @if(isset($method) && strtoupper($method) === 'PUT')
        @method('PUT')
      @endif

      {{ $slot }}

      <div class="modal-footer">
        <button type="button" class="btn-cancel" onclick="{{ $closeFunction }}()">Batal</button>
        <button type="submit" class="btn-save">{{ $submitText ?? 'Simpan' }}</button>
      </div>
    </form>
  </div>
</div>