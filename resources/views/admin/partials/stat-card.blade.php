{{--
Stat Card Component

Usage:
@include('admin.partials.stat-card', [
'icon' => 'fas fa-building',
'value' => '10',
'label' => 'Total Studio'
])
--}}

<div class="stat-card">
  <div class="stat-icon">
    <i class="{{ $icon }}"></i>
  </div>
  <div class="stat-info">
    <h3>{{ $value }}</h3>
    <p>{{ $label }}</p>
  </div>
</div>