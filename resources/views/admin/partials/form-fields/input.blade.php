{{--
Form Input Field Partial
Usage:
@include('admin.partials.form-fields.input', [
'id' => 'add_name',
'name' => 'name',
'label' => 'Nama',
'type' => 'text', // text, email, number, datetime-local, etc.
'required' => true,
'placeholder' => 'Masukkan nama...',
'value' => $oldValue ?? ''
])
--}}
<div class="form-group">
  <label for="{{ $id }}">{{ $label }}</label>
  <input type="{{ $type ?? 'text' }}" id="{{ $id }}" name="{{ $name }}" @if(isset($required) && $required) required
  @endif @if(isset($placeholder)) placeholder="{{ $placeholder }}" @endif @if(isset($value)) value="{{ $value }}"
    @endif @if(isset($min)) min="{{ $min }}" @endif @if(isset($max)) max="{{ $max }}" @endif @if(isset($step))
    step="{{ $step }}" @endif @if(isset($onchange)) onchange="{{ $onchange }}" @endif>
</div>