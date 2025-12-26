{{--
Booking Form Fields Partial
Usage: @include('admin.partials.forms.booking-form', ['prefix' => 'add', 'users' => $users, 'studios' => $studios])
--}}
<div class="form-group">
  <label for="{{ $prefix }}_user_id">Customer</label>
  <select id="{{ $prefix }}_user_id" name="user_id" required>
    <option value="">Pilih Customer</option>
    @foreach($users as $user)
      <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->phone ?? $user->email }})</option>
    @endforeach
  </select>
</div>

<div class="form-group">
  <label for="{{ $prefix }}_studio_id">Studio</label>
  <select id="{{ $prefix }}_studio_id" name="studio_id" required onchange="loadPackages(this.value, '{{ $prefix }}')">
    <option value="">Pilih Studio</option>
    @foreach($studios as $studio)
      <option value="{{ $studio->id }}">{{ $studio->name }}</option>
    @endforeach
  </select>
</div>

<div class="form-group">
  <label for="{{ $prefix }}_package_id">Paket</label>
  <select id="{{ $prefix }}_package_id" name="package_id" required>
    <option value="">Pilih Studio dulu</option>
  </select>
</div>

@include('admin.partials.form-fields.input', [
    'id' => $prefix . '_booking_date',
    'name' => 'booking_date',
    'label' => 'Tanggal',
    'type' => 'date',
    'required' => true
])

<div class="form-group">
  <label for="{{ $prefix }}_booking_time">Waktu</label>
  <input type="time" id="{{ $prefix }}_booking_time" name="booking_time" required step="60">
</div>

@include('admin.partials.form-fields.textarea', [
    'id' => $prefix . '_note',
    'name' => 'note',
    'label' => 'Catatan (Opsional)',
    'required' => false,
    'placeholder' => 'Catatan tambahan...'
])

<div class="form-group">
  <label for="{{ $prefix }}_status">Status</label>
  <select id="{{ $prefix }}_status" name="status" required>
    <option value="PENDING">Pending</option>
    <option value="CONFIRMED">Confirmed</option>
    <option value="DONE">Done</option>
    <option value="CANCELLED">Cancelled</option>
  </select>
</div>

<div class="form-group">
  <label for="{{ $prefix }}_payment_status">Status Pembayaran</label>
  <select id="{{ $prefix }}_payment_status" name="payment_status" required>
    <option value="UNPAID">Belum Bayar</option>
    <option value="PAID">Sudah Bayar</option>
  </select>
</div>
