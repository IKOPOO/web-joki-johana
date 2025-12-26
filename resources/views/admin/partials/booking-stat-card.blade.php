{{--
Booking Stat Card Partial
--}}
<div class="booking-stat-card {{ $modifier }}">
  <div class="stat-top">
    <div class="stat-main">
      <div class="stat-icon-wrapper {{ $iconModifier }}">
        <i class="{{ $icon }}"></i>
      </div>
      <div class="stat-text">
        <h3 class="stat-value">{{ $value }}</h3>
        <p class="stat-label">{{ $label }}</p>
      </div>
    </div>
    @if(isset($incomeLabel) && isset($incomeValue))
      <div class="stat-income-badge">
        <span class="income-label">{{ $incomeLabel }}</span>
        <span class="income-value">Rp {{ number_format($incomeValue, 0, ',', '.') }}</span>
      </div>
    @endif
  </div>
</div>