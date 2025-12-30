<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login & Register - Bilik Foto</title>
  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <!-- CSS -->
  <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>

<body>

  <a href="{{ url('/') }}" class="back-home-btn"><i class="fas fa-arrow-left"></i> Back to Home</a>

  <div
    class="container {{ (isset($isRegister) && $isRegister) || old('form_type') == 'register' ? 'right-panel-active' : '' }}"
    id="container">

    <!-- Sign Up Container -->
    <div class="form-container sign-up-container">
      <form action="{{ route('register') }}" method="POST">
        @csrf
        <input type="hidden" name="form_type" value="register">
        <h1>Buat Akun</h1>
        <span>Gunakan email Anda untuk registrasi</span>

        @if($errors->any() && old('form_type') == 'register')
          <div class="alert alert-error">
            <i class="fas fa-exclamation-circle"></i>
            <div>
              @foreach($errors->all() as $error)
                <div>{{ $error }}</div>
              @endforeach
            </div>
          </div>
        @endif

        <input type="text" name="nama" placeholder="Nama Lengkap" value="{{ old('nama') }}" required />
        <input type="email" name="email" placeholder="Email" value="{{ old('email') }}" required />
        <input type="text" name="no_hp" placeholder="No Handphone" value="{{ old('no_hp') }}" required />
        <input type="password" name="password" placeholder="Password" required />
        <button type="submit">Daftar</button>
      </form>
    </div>

    <!-- Sign In Container -->
    <div class="form-container sign-in-container">
      <form action="{{ route('login.post') }}" method="POST">
        @csrf
        <input type="hidden" name="form_type" value="login">
        <h1>Masuk</h1>
        <span>gunakan akun anda</span>

        @if(session('success'))
          <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            <span>{{ session('success') }}</span>
          </div>
        @endif

        @if($errors->any() && old('form_type') == 'login')
          <div class="alert alert-error">
            <i class="fas fa-exclamation-circle"></i>
            <div>
              @foreach($errors->all() as $error)
                <div>{{ $error }}</div>
              @endforeach
            </div>
          </div>
        @endif

        <input type="email" name="email" placeholder="Email" value="{{ old('email') }}" required />
        <input type="password" name="password" placeholder="Password" required />
        <!-- <a href="#">Lupa password Anda?</a> -->
        <button type="submit">Masuk</button>
      </form>
    </div>

    <!-- Overlay Container -->
    <div class="overlay-container">
      <div class="overlay">
        <div class="overlay-panel overlay-left">
          <h1>Selamat Datang Kembali!</h1>
          <p>Untuk tetap terhubung dengan kami, silakan login dengan info pribadi Anda</p>
          <button class="ghost" id="signIn">Masuk</button>
        </div>
        <div class="overlay-panel overlay-right">
          <h1>Halo, Teman!</h1>
          <p>Masukkan detail pribadi Anda dan mulailah perjalanan bersama kami</p>
          <button class="ghost" id="signUp">Daftar Sekarang</button>
        </div>
      </div>
    </div>
  </div>

  <script>
    const signUpButton = document.getElementById('signUp');
    const signInButton = document.getElementById('signIn');
    const container = document.getElementById('container');

    signUpButton.addEventListener('click', () => {
      container.classList.add("right-panel-active");
    });

    signInButton.addEventListener('click', () => {
      container.classList.remove("right-panel-active");
    });
  </script>

</body>

</html>