{{--
Chart Card Component

Usage:
@include('admin.partials.chart-card', [
'title' => 'Chart Title',
'subtitle' => 'Chart description',
'chartId' => 'uniqueChartId',
'type' => 'normal' // Options: 'normal', 'wide', 'full', 'pie'
])
--}}

@php
  $cardClass = 'chart-card';
  $bodyClass = 'chart-body';

  if (isset($type)) {
    if ($type === 'wide') {
      $cardClass .= ' chart-wide';
    } elseif ($type === 'full') {
      $cardClass .= ' chart-full';
    } elseif ($type === 'pie') {
      $bodyClass .= ' chart-body-pie';
    }
  }
@endphp

<div class="{{ $cardClass }}">
  <div class="chart-header">
    <h3>{{ $title }}</h3>
    <span class="chart-subtitle">{{ $subtitle }}</span>
  </div>
  <div class="{{ $bodyClass }}">
    <canvas id="{{ $chartId }}"></canvas>
  </div>
</div>