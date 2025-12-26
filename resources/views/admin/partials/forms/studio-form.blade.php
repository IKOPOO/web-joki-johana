{{--
Studio Form Fields Partial
Usage: @include('admin.partials.forms.studio-form', ['prefix' => 'add', 'statusOptions' => [...]])
--}}
@include('admin.partials.form-fields.input', [
  'id' => $prefix . '_name',
  'name' => 'name',
  'label' => 'Nama Studio',
  'type' => 'text',
  'required' => true,
  'placeholder' => 'Contoh: Studio A'
])

@include('admin.partials.form-fields.textarea', [
  'id' => $prefix . '_address',
  'name' => 'address',
  'label' => 'Alamat',
  'required' => true,
  'placeholder' => 'Alamat lengkap studio...'
])

@include('admin.partials.form-fields.input', [
  'id' => $prefix . '_city',
  'name' => 'city',
  'label' => 'Kota',
  'type' => 'text',
  'required' => true,
  'placeholder' => 'Contoh: Jakarta'
])

<div class="form-group">
  <label for="{{ $prefix }}_status">Status</label>
  <select id="{{ $prefix }}_status" name="status" required>
    <option value="active">Active</option>
    <option value="inactive">Inactive</option>
  </select>
</div>
