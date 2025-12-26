{{--
Form Select Field Partial
Usage:
@include('admin.partials.form-fields.select', [
'id' => 'add_studio_id',
'name' => 'studio_id',
'label' => 'Studio',
'required' => true,
'options' => $studios,
'optionValue' => 'id',
'optionLabel' => 'name',
'placeholder' => 'Pilih Studio',
'selected' => $selectedId ?? null,
'onchange' => 'loadPackages(this.value)'
])
--}}
<div class="form-group">
  <label for="{{ $id }}">{{ $label }}</label>
  <select id="{{ $id }}" name="{{ $name }}" @if(isset($required) && $required) required @endif @if(isset($onchange))
  onchange="{{ $onchange }}" @endif>
    @if(isset($placeholder))
      <option value="">{{ $placeholder }}</option>
    @endif
    @foreach($options as $option)
      <option value="{{ $option->{$optionValue} }}" @if(isset($selected) && $selected == $option->{$optionValue}) selected
      @endif>
        @if(isset($optionLabelCallback))
          {{ $optionLabelCallback($option) }}
        @else
          {{ $option->{$optionLabel} }}
        @endif
      </option>
    @endforeach
  </select>
</div>