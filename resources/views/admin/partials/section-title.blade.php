{{--
Section Title Component

Usage:
@include('admin.partials.section-title', [
'icon' => 'fas fa-chart-bar',
'title' => 'Section Title'
])
--}}

<div class="section-title">
  <h2><i class="{{ $icon }}"></i> {{ $title }}</h2>
</div>