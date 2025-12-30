<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <title>Booking Confirmation - {{ $booking->id }}</title>
  <link rel="stylesheet" href="{{ asset('css/customer/booking/print.css') }}">
</head>

<body>
  @php
    $statusColors = [
      'PENDING' => '#ffc107',
      'CONFIRMED' => '#4caf50',
      'CANCELLED' => '#f44336',
      'COMPLETED' => '#2196f3'
    ];
    $paymentColors = [
      'UNPAID' => '#ff9800',
      'PAID' => '#4caf50',
      'PENDING' => '#ff9800'
    ];

    // Slot timings logic based on SessionSlot if available
    $startTime = $booking->sessionSlot ? \Carbon\Carbon::parse($booking->sessionSlot->start_time)->format('H:i') : '-';
    $endTime = $booking->sessionSlot ? \Carbon\Carbon::parse($booking->sessionSlot->end_time)->format('H:i') : '-';
  @endphp

  <div class="container">
    <div class="header">
      <h1>🎉 BOOKING CONFIRMATION</h1>
      <p>Lensia Photo Studio</p>
      <div class="booking-id">Booking #{{ str_pad($booking->id, 6, '0', STR_PAD_LEFT) }}</div>
    </div>

    <div class="status-badges">
      <span class="badge" style="background-color: {{ $statusColors[$booking->status] ?? '#999' }};">
        {{ $booking->status }}
      </span>
      <span class="badge" style="background-color: {{ $paymentColors[$booking->payment_status] ?? '#999' }};">
        💳 {{ $booking->payment_status }}
      </span>
    </div>

    <div class="content">
      <!-- Studio Info -->
      <div class="section">
        <div class="section-title">📍 STUDIO INFORMATION</div>
        <div class="info-row">
          <div class="info-label">Studio Name</div>
          <div class="info-value">{{ $booking->studio->name }}</div>
        </div>
        <div class="info-row">
          <div class="info-label">Location</div>
          <div class="info-value">{{ $booking->studio->address }}</div>
        </div>
      </div>

      <!-- Customer Info -->
      <div class="section">
        <div class="section-title">👤 CUSTOMER INFORMATION</div>
        <div class="info-row">
          <div class="info-label">Name</div>
          <div class="info-value">{{ $booking->user->name }}</div>
        </div>
        <div class="info-row">
          <div class="info-label">Email</div>
          <div class="info-value">{{ $booking->user->email }}</div>
        </div>
        <div class="info-row">
          <div class="info-label">Phone</div>
          <div class="info-value">{{ $booking->user->phone ?? '-' }}</div>
        </div>
      </div>

      <!-- Booking Details -->
      <div class="section">
        <div class="section-title">📸 BOOKING DETAILS</div>
        <div class="info-row">
          <div class="info-label">Package</div>
          <div class="info-value">{{ $booking->package->name }}</div>
        </div>
        <div class="info-row">
          <div class="info-label">Session Date</div>
          <div class="info-value">{{ \Carbon\Carbon::parse($booking->booking_datetime)->isoFormat('dddd, D MMMM Y') }}
          </div>
        </div>
        <div class="info-row">
          <div class="info-label">Session Time</div>
          <div class="info-value">
            {{ \Carbon\Carbon::parse($booking->booking_datetime)->format('H:i') }} WIB
          </div>
        </div>
        <div class="info-row">
          <div class="info-label">Booking Date</div>
          <div class="info-value">{{ $booking->created_at->isoFormat('D MMMM Y, H:i') }} WIB</div>
        </div>

        @if($booking->note)
          <div class="notes">
            <strong>📝 Notes:</strong> {{ $booking->note }}
          </div>
        @endif
      </div>

      <!-- Payment Info -->
      <div class="section">
        <div class="section-title">💰 PAYMENT INFORMATION</div>
        <div class="price-highlight">
          <h3>Rp {{ number_format($booking->total_price, 0, ',', '.') }}</h3>
          <small style="color: #666;">Total Amount</small>
        </div>
        <div class="info-row">
          <div class="info-label">Payment Status</div>
          <div class="info-value">{{ $booking->payment_status }}</div>
        </div>
        <div class="info-row">
          <div class="info-label">Payment Method</div>
          <div class="info-value" style="text-transform: uppercase;">{{ $booking->payment_method ?? '-' }}</div>
        </div>
      </div>

      <!-- Important Notes -->
      <div class="footer-notes">
        <h4>⚠️ Important Information</h4>
        <ul>
          <li>Please arrive <strong>10 minutes before</strong> your session time</li>
          <li>Bring this confirmation (printed or digital copy)</li>
          <li>Payment must be completed before the session begins</li>
          <li>For reschedule or cancellation, contact the studio directly</li>
          <li>Show this booking code at the studio reception</li>
        </ul>
      </div>

      <!-- Barcode -->
      <div class="barcode">
        <div style="font-size: 12px; color: #999; margin-bottom: 5px;">Booking Code</div>
        <div class="barcode-text">LENS{{ str_pad($booking->id, 6, '0', STR_PAD_LEFT) }}</div>
      </div>
    </div>

    <div class="buttons no-print">
      <button onclick="window.print()" class="btn btn-print">🖨️ Print / Save as PDF</button>
      <a href="{{ route('customer.reservations.index') }}" class="btn btn-back">← Back to My Bookings</a>
    </div>

    <div class="generated-time">
      Generated on {{ now()->isoFormat('D MMMM Y, H:i:s') }} WIB
    </div>
  </div>

  <script src="{{ asset('js/customer/booking/print.js') }}"></script>
</body>

</html>