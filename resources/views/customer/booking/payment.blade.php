@extends('layouts.app', ['hideNavbar' => true, 'hideFooter' => true])

@section('content')
  <div class="payment-wrapper">
    <div style="max-width: 800px; margin: 0 auto 20px auto;">
      <a href="{{ route('customer.reservations.index') }}" class="btn-back-custom">
        <i class="fas fa-arrow-left"></i> Kembali
      </a>
    </div>

    <div class="payment-container">

      <div class="payment-header">
        <h2>Konfirmasi Pembayaran</h2>
        <p>Silakan periksa kembali detail reservasi Anda sebelum melakukan pembayaran.</p>
      </div>

      <div class="details-grid">
        <!-- Studio Info -->
        <div class="detail-card">
          <h3><i class="fas fa-camera"></i> Informasi Studio</h3>
          <div class="info-row">
            <label>Nama Studio</label>
            <span>{{ $booking->studio->name }}</span>
          </div>
          <div class="info-row">
            <label>Lokasi</label>
            <span>{{ $booking->studio->location_name ?? 'Kota Semarang' }}</span>
          </div>
          <div class="info-row">
            <label>Alamat</label>
            <span>{{ $booking->studio->address }}</span>
          </div>
        </div>

        <!-- Booker Info -->
        <div class="detail-card">
          <h3><i class="fas fa-user"></i> Data Pemesan</h3>
          <div class="info-row">
            <label>Nama Lengkap</label>
            <span>{{ Auth::user()->name }}</span>
          </div>
          <div class="info-row">
            <label>Email</label>
            <span>{{ Auth::user()->email }}</span>
          </div>
          <div class="info-row">
            <label>No. HP</label>
            <span>{{ Auth::user()->phone ?? '-' }}</span>
          </div>
          <div class="info-row">
            <label>Catatan</label>
            <span>{{ $booking->note ?? '-' }}</span>
          </div>
        </div>

        <!-- Session Info -->
        <div class="detail-card full-width">
          <h3><i class="fas fa-calendar-alt"></i> Detail Sesi</h3>
          <div class="session-info-grid">
            <div class="info-item">
              <label>Tanggal</label>
              <div class="val">{{ \Carbon\Carbon::parse($booking->booking_datetime)->isoFormat('dddd, D MMMM Y') }}</div>
            </div>
            <div class="info-item">
              <label>Jam</label>
              <div class="val">{{ \Carbon\Carbon::parse($booking->booking_datetime)->format('H:i') }} WIB</div>
            </div>
            <div class="info-item">
              <label>Paket</label>
              <div class="val">{{ $booking->package->name }}</div>
            </div>
          </div>
        </div>
      </div>

      <!-- Payment Summary -->
      <div class="payment-summary">
        <div class="total-row">
          <span>Subtotal</span>
          <span>Rp {{ number_format($booking->total_price, 0, ',', '.') }}</span>
        </div>

        <form action="{{ route('customer.booking.pay', $booking->id) }}" method="POST" enctype="multipart/form-data">
          @csrf

          <div class="payment-method-group" style="margin-bottom: 30px;">
            <label style="margin-bottom: 15px; display:block; font-size:1rem;">Pilih Metode Pembayaran</label>
            <div class="method-cards">
              <!-- QRIS -->
              <label class="method-card">
                <input type="radio" name="payment_method" value="qris" required onchange="showPaymentDetail('qris')">
                <div class="card-content">
                  <i class="fas fa-qrcode"></i>
                  <span>QRIS</span>
                </div>
              </label>
              <!-- Transfer Bank -->
              <label class="method-card">
                <input type="radio" name="payment_method" value="transfer_bank" onchange="showPaymentDetail('bank')">
                <div class="card-content">
                  <i class="fas fa-university"></i>
                  <span>Transfer Bank</span>
                </div>
              </label>
              <!-- E-Wallet -->
              <label class="method-card">
                <input type="radio" name="payment_method" value="ewallet" onchange="showPaymentDetail('ewallet')">
                <div class="card-content">
                  <i class="fas fa-wallet"></i>
                  <span>E-Wallet</span>
                </div>
              </label>
              <!-- Cash -->
              <label class="method-card">
                <input type="radio" name="payment_method" value="bayar_ditempat" onchange="showPaymentDetail('cash')">
                <div class="card-content">
                  <i class="fas fa-money-bill-wave"></i>
                  <span>Cash</span>
                </div>
              </label>
            </div>
          </div>

          <!-- Dynamic Payment Details -->
          <div id="payment-details-container" style="margin-bottom: 30px; display: none;">
            <!-- QRIS Detail -->
            <div id="detail-qris" class="payment-instruction" style="display:none; text-align:center;">
              <p style="margin-bottom:10px;">Scan QRIS di bawah ini untuk membayar:</p>
              <img src="{{ asset('images/qris.png') }}" alt="QRIS Code"
                style="max-width:250px; border-radius:10px; border:1px solid #ddd;">
            </div>

            <!-- Bank Detail -->
            <div id="detail-bank" class="payment-instruction" style="display:none;">
              <div class="bank-list">
                <div class="bank-item">
                  <div class="bank-logo">BCA</div>
                  <div class="bank-info">
                    <div class="acc-num">123 456 7890</div>
                    <div class="acc-name">a.n. Studio Foto</div>
                  </div>
                  <button type="button" class="btn-copy" onclick="copyText('1234567890')"><i
                      class="far fa-copy"></i></button>
                </div>
                <div class="bank-item">
                  <div class="bank-logo">BRI</div>
                  <div class="bank-info">
                    <div class="acc-num">0987 6543 2100</div>
                    <div class="acc-name">a.n. Studio Foto</div>
                  </div>
                  <button type="button" class="btn-copy" onclick="copyText('098765432100')"><i
                      class="far fa-copy"></i></button>
                </div>
              </div>
            </div>

            <!-- E-Wallet Detail -->
            <div id="detail-ewallet" class="payment-instruction" style="display:none;">
              <div class="bank-list">
                <div class="bank-item">
                  <div class="bank-logo">GOPAY</div>
                  <div class="bank-info">
                    <div class="acc-num">0812 3456 7890</div>
                    <div class="acc-name">a.n. Studio Foto</div>
                  </div>
                  <button type="button" class="btn-copy" onclick="copyText('081234567890')"><i
                      class="far fa-copy"></i></button>
                </div>
                <div class="bank-item">
                  <div class="bank-logo">DANA</div>
                  <div class="bank-info">
                    <div class="acc-num">0812 3456 7890</div>
                    <div class="acc-name">a.n. Studio Foto</div>
                  </div>
                  <button type="button" class="btn-copy" onclick="copyText('081234567890')"><i
                      class="far fa-copy"></i></button>
                </div>
              </div>
            </div>

            <!-- Cash Detail -->
            <div id="detail-cash" class="payment-instruction" style="display:none;">
              <div
                style="background:#fffdf5; border:1px solid #ffeeba; padding:15px; border-radius:8px; text-align:center;">
                <i class="fas fa-info-circle" style="color:#FEC72E; font-size:1.2rem; margin-bottom:10px;"></i>
                <p style="margin:0; font-size:0.95rem; color:#555;">Silakan lakukan pembayaran tunai langsung di kasir
                  studio sebelum sesi dimulai.</p>
              </div>
            </div>
          </div>

          <div class="payment-group">
            <label>Upload Bukti Pembayaran</label>
            <div class="upload-area" onclick="document.getElementById('fileInput').click()">
              <input type="file" name="payment_proof" id="fileInput" hidden accept="image/*"
                onchange="previewImage(event)">
              <div class="upload-placeholder" id="uploadPlaceholder">
                <i class="fas fa-cloud-upload-alt" style="font-size: 2rem; color: #ccc; margin-bottom: 10px;"></i>
                <p style="margin: 0; color: #666;">Klik untuk upload bukti</p>
                <small style="color: #999;">Format: JPG, PNG (Max 2MB)</small>
              </div>
              <img id="imagePreview" src="" alt="Preview"
                style="display: none; width: 100%; height: auto; border-radius: 8px;">
            </div>
            <!-- Remove input if image cleared? For now just required -->
          </div>

          <div class="action-buttons">
            <a href="{{ route('customer.booking.create', $booking->studio_id) }}" class="btn-back">Kembali</a>
            <button type="submit" class="btn-pay">Bayar Sekarang</button>
          </div>
        </form>
      </div>

    </div>
  </div>

  @push('styles')
    <link rel="stylesheet" href="{{ asset('css/customer/booking/payment.css') }}">
  @endpush

  @push('scripts')
    <script src="{{ asset('js/customer/booking/payment.js') }}"></script>
  @endpush
@endsection