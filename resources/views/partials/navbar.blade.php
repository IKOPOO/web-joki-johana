<div class="nav">
  <div class="container">
    <div class="logo">
      <a href="{{ url('/') }}"><img src="{{ asset('images/lensia.svg') }}" alt="Logo"></a>
    </div>
    <ul class="navmenu">
      <li><a
          href="{{ Request::is('/') || Request::routeIs('customer.booking.index') ? '#aboutme' : (Auth::check() ? route('customer.booking.index') . '#aboutme' : url('/#aboutme')) }}">ABOUT</a>
      </li>
      @auth
        <!-- Logged in: Link to Studio Selection -->
        <li><a
            href="{{ Request::routeIs('customer.booking.index') ? '#studios' : route('customer.booking.index') . '#studios' }}">STUDIO</a>
        </li>
      @endauth

      @auth
        <li><a
            href="{{ Request::routeIs('customer.booking.index') ? '#testimoni' : route('customer.booking.index') . '#testimoni' }}">TESTIMONIALS</a>
        </li>
        <li><a
            href="{{ Request::routeIs('customer.booking.index') ? '#bagiancontact' : route('customer.booking.index') . '#bagiancontact' }}">CONTACT</a>
        </li>
      @else
        <li><a href="{{ Request::is('/') ? '#testimoni' : url('/#testimoni') }}">TESTIMONIALS</a></li>
        <li><a href="{{ Request::is('/') ? '#bagiancontact' : url('/#bagiancontact') }}">CONTACT</a></li>
      @endauth
      @auth
        <li><a href="{{ route('customer.reservations.index') }}">YOUR RESERVATION</a></li>
      @endauth
    </ul>
    <div class="auth" style="display:flex; align-items: center; gap: 15px;">
      @auth
        <a href="{{ route('customer.profile') }}"
          style="color: #FEC72E; font-size: 2rem; text-decoration: none; transition: transform 0.2s;" title="Profil Saya">
          <i class="fas fa-user-circle"></i>
        </a>
      @else
        <a href="{{ route('login') }}"><button id="login">Masuk</button></a>
        <a href="{{ route('register') }}"><button id="regist">Daftar</button></a>
      @endauth
    </div>
  </div>
</div>

<style>
  .auth i:hover {
    transform: scale(1.1);
    color: #e5b329;
  }
</style>