{{--
Package Form Fields Partial
Usage: @include('admin.partials.forms.package-form', ['prefix' => 'add'])
--}}
@include('admin.partials.form-fields.input', [
  'id' => $prefix . '_name',
  'name' => 'name',
  'label' => 'Nama Package',
  'type' => 'text',
  'required' => true,
  'placeholder' => 'Contoh: Paket Gold'
])

@include('admin.partials.form-fields.textarea', [
  'id' => $prefix . '_description',
  'name' => 'description',
  'label' => 'Deskripsi',
  'required' => true,
  'placeholder' => 'Deskripsi package...'
])

@include('admin.partials.form-fields.input', [
  'id' => $prefix . '_duration',
  'name' => 'duration_minutes',
  'label' => 'Durasi (menit)',
  'type' => 'number',
  'required' => true,
  'placeholder' => '60',
  'min' => 1
])

@include('admin.partials.form-fields.input', [
  'id' => $prefix . '_price',
  'name' => 'price',
  'label' => 'Harga (Rp)',
  'type' => 'number',
  'required' => true,
  'placeholder' => '500000',
  'min' => 0
])

@include('admin.partials.form-fields.checkbox', [
  'id' => $prefix . '_is_active',
  'name' => 'is_active',
  'label' => 'Aktif',
  'checked' => $checked ?? true
])
