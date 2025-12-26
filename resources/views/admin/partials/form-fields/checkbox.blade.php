{{--
Form Checkbox Field Partial
Usage:
@include('admin.partials.form-fields.checkbox', [
'id' => 'add_is_active',
'name' => 'is_active',
'label' => 'Aktif',
'checked' => true
])
--}}
<div class="form-group form-check">
  <label class="checkbox-label">
    <input type="checkbox" id="{{ $id }}" name="{{ $name }}" value="1" @if(isset($checked) && $checked) checked @endif>
    <span>{{ $label }}</span>
  </label>
</div>