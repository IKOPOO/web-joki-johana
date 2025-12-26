{{--
User Form Fields Partial
Usage: @include('admin.partials.forms.user-form', ['prefix' => 'add', 'showPassword' => true])
--}}
@include('admin.partials.form-fields.input', [
  'id' => $prefix . '_name',
  'name' => 'name',
  'label' => 'Nama Lengkap',
  'type' => 'text',
  'required' => true,
  'placeholder' => 'Masukkan nama lengkap'
])

@include('admin.partials.form-fields.input', [
  'id' => $prefix . '_email',
  'name' => 'email',
  'label' => 'Email',
  'type' => 'email',
  'required' => true,
  'placeholder' => 'contoh@email.com'
])

@include('admin.partials.form-fields.input', [
  'id' => $prefix . '_phone',
  'name' => 'phone',
  'label' => 'No HP',
  'type' => 'text',
  'required' => true,
  'placeholder' => '08123456789'
])

@if(isset($showPassword) && $showPassword)
  @include('admin.partials.form-fields.input', [
    'id' => $prefix . '_password',
    'name' => 'password',
    'label' => 'Password',
    'type' => 'password',
    'required' => true,
    'placeholder' => 'Minimal 6 karakter'
  ])
@endif

<div class="form-group">
  <label for="{{ $prefix }}_role">Role</label>
  <select id="{{ $prefix }}_role" name="role" required onchange="toggleStudioField('{{ $prefix }}')">
    <option value="CUSTOMER">Customer</option>
    <option value="STUDIO_STAF">Studio Staff</option>
    <option value="LENSIA_ADMIN">Admin</option>
  </select>
</div>

<div class="form-group" id="{{ $prefix }}_studio_container" style="display: none;">
  <label for="{{ $prefix }}_studio_id">Studio (Wajib untuk Staff)</label>
  <select id="{{ $prefix }}_studio_id" name="studio_id">
    <option value="">Pilih Studio</option>
    @foreach($studios as $studio)
      <option value="{{ $studio->id }}">{{ $studio->name }}</option>
    @endforeach
  </select>
</div>

<div class="form-group">
  <label for="{{ $prefix }}_status">Status</label>
  <select id="{{ $prefix }}_status" name="status" required>
    <option value="ACTIVE">Active</option>
    <option value="SUSPENDED">Suspended</option>
  </select>
</div>
