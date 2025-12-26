{{--
Form Textarea Field Partial
Usage:
@include('admin.partials.form-fields.textarea', [
'id' => 'add_description',
'name' => 'description',
'label' => 'Deskripsi',
'required' => false,
'placeholder' => 'Masukkan deskripsi...',
'value' => $oldValue ?? '',
'rows' => 3
])
--}}
<div class="form-group">
  <label for="{{ $id }}">{{ $label }}</label>
  <textarea id="{{ $id }}" name="{{ $name }}" @if(isset($required) && $required) required @endif
    @if(isset($placeholder)) placeholder="{{ $placeholder }}" @endif @if(isset($rows)) rows="{{ $rows }}"
    @endif>{{ $value ?? '' }}</textarea>
</div>