<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - Bilik Foto</title>
  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <!-- CSS -->
  <link rel="stylesheet" href="{{ asset('css/style.css') }}">
  <link rel="stylesheet" href="{{ asset('css/login.css') }}">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>

  <a href="{{ url('/') }}" class="back-home-btn"><i class="fas fa-arrow-left"></i> Back to Home</a>

  <div class="split-screen">
    <div class="left-pane">
      <img src="{{ asset('images/opsional.png') }}" alt="Studio Background">
    </div>
    <div class="right-pane">
      <div class="login-content">
        <h1>Login</h1>
        <p>Welcome back! Please login to your account</p>

        {{-- Success Message (e.g., after registration) --}}
        @if(session('success'))
          <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            {{ session('success') }}
          </div>
        @endif

        {{-- Error Messages --}}
        @if($errors->any())
          <div class="alert alert-error">
            <i class="fas fa-exclamation-circle"></i>
            @foreach($errors->all() as $error)
              <div>{{ $error }}</div>
            @endforeach
          </div>
        @endif

        <form action="/login" method="POST">
          @csrf
          <div class="form-group">
            <label for="login-email">Email</label>
            <input type="email" name="email" id="login-email" placeholder="Enter your email" required>
          </div>

          <div class="form-group">
            <label for="login-password">Password</label>
            <input type="password" name="password" id="login-password" placeholder="Enter your password" required>
          </div>

          <button type="submit" class="login-btn">Login</button>
        </form>
        <div class="register-link">
          <p>Belum punya akun? <a href="{{ route('register') }}">Daftar sekarang</a></p>
        </div>
      </div>
    </div>
  </div>

</body>

</html>