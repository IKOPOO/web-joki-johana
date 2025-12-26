<div class="nav">
  <div class="container">
    <div class="logo">
      <a href="{{ url('/') }}"><img src="{{ asset('images/logo.png') }}" alt="Logo"></a>
    </div>
    <ul class="navmenu">
      <li><a href="#aboutme">ABOUT</a></li>
      <li><a href="#price">PRICES</a></li>
      <li><a href="#testimoni">TESTIMONIALS</a></li>
      <li><a href="#bagiancontact">CONTACT</a></li>
    </ul>
    <div class="auth" style="display:flex">
      @if(session('user_id'))
        <span>Welcome, {{ session('user_name') }}</span>
        <a href="{{ url('/logout') }}"><button id="logout">Logout</button></a>
      @else
        <a href="{{ route('login') }}"><button id="login">Masuk</button></a>
        <a href="{{ route('register') }}"><button id="regist">Daftar</button></a>
      @endif
    </div>
  </div>
</div>