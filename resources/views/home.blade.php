@extends('layouts.app')

@section('content')
  <header>
    <!-- Background Hero -->
    <div class="hero-nav"
      style="background-image: linear-gradient(rgba(73, 73, 73, 0.07)), url('{{ asset('images/bgweb.png') }}')">
      <div class="container hero-desc">
        <h1 style="text-transform: capitalize; font-size: 2.2rem; font-weight: 800; line-height: 1.2;">
          is a photo studio, where instead of a <br> photographer, a remote control is in your hands <br>
          is a photo studio
        </h1>
        <div class="book">
          <a href="{{ route('customer.booking.index') }}">
            <button id="book">Pesan Sekarang</button>
          </a>
          <a id="vector" href="{{ route('customer.booking.index') }}">
            <i class="fa-solid fa-arrow-right"></i>
          </a>
        </div>
      </div>
    </div>
  </header>

  <section class="why-us-section" id="aboutme">
    <div class="container">
      <div class="about-content">
        <!-- Left Column: Images (Triangle Layout) -->
        <div class="images-container">
          <img src="{{ asset('images/image-3.svg') }}" class="image" alt="About 1">
          <img src="{{ asset('images/image-4.svg') }}" class="image" alt="About 2">
          <img src="{{ asset('images/image-5.svg') }}" class="image" alt="About 3">
        </div>

        <!-- Right Column: Text -->
        <div class="about-text">
          <h2>Why us?<br>What makes BilikFoto so special?</h2>
          <p style="text-align: justify;">
            Di BilikFoto, kami percaya setiap momen layak diabadikan dengan sempurna. Itulah mengapa kami menghadirkan
            pengalaman yang berbeda: mulai dari teknologi kamera canggih, pencahayaan profesional, hingga desain studio
            yang
            stylish dan nyaman. Selain itu, Anda memiliki kendali penuh atas setiap foto yang diambil, menjadikan setiap
            hasilnya unik dan personal. Dengan suasana yang mendukung kreativitas dan privasi.
            BilikFoto lebih dari sekadar studio, kami adalah tempat di mana cerita Anda diabadikan dengan keindahan yang
            tak
            tertandingi.
          </p>
        </div>
      </div>
    </div>
  </section>




  <div id="testimoni">
    <section class="testimonials-section">
      <div class="container">
        <h2 class="title">Testimonials</h2>
        <div class="testimonials-container">
          <div class="testimonials-carousel">
            <div class="testimonial">
              <img src="{{ asset('images/testi1.svg') }}" alt="Testimonial 1">
              <h3>Winda & Ayu</h3>
              <p>Pengalaman yang luar biasa! Tim di BilikFoto Studio sangat profesional dan ramah. Saya merasa sangat
                nyaman
                selama sesi foto dan hasilnya luar biasa! Terima kasih BilikFoto! – Winda & Ayu</p>
            </div>
            <div class="testimonial">
              <img src="{{ asset('images/testi2.svg') }}" alt="Testimonial 2">
              <h3>Rizal & Citra</h3>
              <p>Pengalaman yang luar biasa! Tim di BilikFoto Studio sangat profesional dan ramah. Saya merasa sangat
                nyaman
                selama sesi foto dan hasilnya luar biasa! Terima kasih BilikFoto! – Rizal & Citra</p>
            </div>
            <div class="testimonial">
              <img src="{{ asset('images/testi3.svg') }}" alt="Testimonial 3">
              <h3>Aliyah & Mutia</h3>
              <p>Pengalaman yang luar biasa! Tim di BilikFoto Studio sangat profesional dan ramah. Saya merasa sangat
                nyaman
                selama sesi foto dan hasilnya luar biasa! Terima kasih BilikFoto! – Aliyah & Mutia</p>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>

  <section class="contact-section" id="bagiancontact">
    <div class="container">
      <h2 class="title" style="text-align:center">Contact Us</h2>
      <div class="contact-container">
        <div class="contact-info">
          <h3>Hubungi Kami</h3>
          <p>
            Ada yang ingin ditanyakan atau disampaikan?
            Kami di sini untuk Anda!
            <br>
            Mulai dari informasi layanan, panduan pemesanan, hingga cerita seru tentang pengalaman Anda di bilikfoto.
            Jangan
            sungkan untuk menghubungi kami.
            <br>
            Tim kami siap memberikan solusi terbaik dengan senyuman!
          </p>

          <form class="contact-form">
            <h4>Send us a message</h4>
            <input type="text" placeholder="Nama Lengkap">
            <input type="email" placeholder="Alamat Email">
            <input type="tel" placeholder="No Handphone">
            <textarea placeholder="Pesan"></textarea>
            <button type="submit">Send</button>
          </form>
        </div>
        <div class="contact-map">
          <!-- Map embed -->
          <iframe
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3960.0502188514292!2d110.3791018743102!3d-7.003369368593513!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e708b1c32fbb1b9%3A0x38e30aff77868931!2sJl.%20Abdulrahman%20Saleh%20No.570%2C%20Manyaran%2C%20Kec.%20Semarang%20Barat%2C%20Kota%20Semarang%2C%20Jawa%20Tengah%2050147!5e0!3m2!1sid!2sid!4v1736080266670!5m2!1sid!2sid"
            width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"
            referrerpolicy="no-referrer-when-downgrade"></iframe>

          <ul class="contact-details-list">
            <li>📞 +62 877-8324-0504</li>
            <li>📍 Jl. Abdulrahman Saleh No.570, Manyaran, Kota Semarang</li>
            <li>📧 fotobilik@gmail.com</li>
          </ul>
        </div>
      </div>
    </div>
  </section>

  <!-- MODAL POPUPS (Login, Register, Booking) -->
  <!-- Sebaiknya modal juga dipisah jadi partials/modals.blade.php biar bersih -->
  @include('partials.modals')

@endsection