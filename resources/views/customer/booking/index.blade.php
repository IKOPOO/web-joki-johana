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
          <a href="#studios">
            <button id="book">Pilih Studio</button>
          </a>
          <a id="vector" href="#studios">
            <i class="fa-solid fa-arrow-right"></i>
          </a>
        </div>
      </div>
    </div>
  </header>

  <!-- ABOUT SECTION -->
  <section class="booking-about-section" id="aboutme">
    <div class="booking-images-container">
      <img src="{{ asset('images/kapal2.svg') }}" alt="Model 2" class="booking-image">
      <img src="{{ asset('images/photostrip3.svg') }}" alt="Model 3" class="booking-image">
    </div>
    <div class="booking-text-container">
      <h2>Why us?<br>What is Lensia?</h2>
      <p>
        Lensia merupakan salah satu reservasi service studio photo. Disini kami
        menyediakan sistem yang realtime untuk jadwal reservasi, pembayaran digital,
        dan laporan hasil pengelolahan.
      </p>
    </div>
  </section>

  <!-- STUDIO SELECTION SECTION (Replaces Price Section) -->
  <section class="studios-section" id="studios" style="padding: 80px 0; background: #fff;">
    <div class="container">
      <div class="section-header" style="text-align: center; margin-bottom: 50px;">
        <h2 class="title" style="font-size: 2.5rem; color: #424242; margin-bottom: 10px;">Pilih Studio</h2>
        <p style="color: #666; font-size: 1.1rem; max-width: 600px; margin: 0 auto;">Pilih lokasi studio terdekat atau
          favorit Anda untuk memulai sesi foto yang tak terlupakan.</p>
      </div>

      <div class="studio-grid"
        style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 30px;">
        @forelse($studios as $studio)
          <div class="studio-card"
            style="background: white; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.08); overflow: hidden; transition: all 0.3s ease; border: 1px solid #f0f0f0; display: flex; flex-direction: column; height: 100%;">
            <div class="studio-img"
              style="height: 220px; background: #fdf9f1; display: flex; align-items: center; justify-content: center; position: relative; flex-shrink: 0;">
              <i class="fas fa-camera" style="font-size: 4rem; color: #FEC72E; opacity: 0.5;"></i>
              <div class="studio-overlay"
                style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.2); opacity: 0; transition: opacity 0.3s;">
              </div>
            </div>
            <div class="studio-info" style="padding: 25px; display: flex; flex-direction: column; flex-grow: 1;">
              <h3 style="font-size: 1.4rem; color: #424242; margin-bottom: 10px; font-weight: 700;">{{ $studio->name }}</h3>
              <p
                style="color: #666; font-size: 0.95rem; margin-bottom: 20px; line-height: 1.5; display: flex; align-items: flex-start;">
                <i class="fas fa-map-marker-alt"
                  style="color: #FEC72E; margin-right: 10px; margin-top: 4px; flex-shrink: 0;"></i>
                {{ $studio->address ?? 'Lokasi strategis di pusat kota' }}
              </p>
              <a href="{{ route('customer.booking.create', $studio->id) }}" class="btn-book-studio"
                style="display: block; text-align: center; background: #FEC72E; color: #424242; padding: 14px; border-radius: 12px; font-weight: 600; text-decoration: none; transition: all 0.2s; box-shadow: 0 4px 15px rgba(254, 199, 46, 0.3); margin-top: auto;">
                Booking di Sini <i class="fas fa-arrow-right" style="margin-left: 5px;"></i>
              </a>
            </div>
          </div>
        @empty
          <div class="empty-state"
            style="grid-column: 1/-1; text-align: center; padding: 40px; background: #f9f9f9; border-radius: 15px;">
            <p style="color: #999;">Belum ada studio yang tersedia saat ini.</p>
          </div>
        @endforelse
      </div>
    </div>
  </section>



  @include('partials.testimonials')

  @include('partials.contact')

  @include('partials.modals')

  @push('styles')
    <link rel="stylesheet" href="{{ asset('css/customer/booking/index.css') }}">
  @endpush
@endsection