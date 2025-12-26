<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register - Bilik Foto</title>
  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <!-- CSS -->
  <link rel="stylesheet" href="{{ asset('css/style.css') }}">
  <link rel="stylesheet" href="{{ asset('css/register.css') }}">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>

  <a href="{{ url('/') }}" class="back-home-btn"><i class="fas fa-arrow-left"></i> Back to Home</a>

  <div class="register-page">
    <div class="register-card">
      <h1>Register</h1>
      <p>Create your account to get started!</p>

      <form action="{{ route('register') }}" method="POST">
        @csrf

        <div class="form-group">
          <label for="register-nama">Nama</label>
          <input type="text" id="register-nama" name="nama" placeholder="Enter your name" required>
        </div>

        <div class="form-group">
          <label for="register-email">Email</label>
          <input type="email" id="register-email" name="email" placeholder="Enter your email" required>
        </div>

        <div class="form-group">
          <label for="register-hp">Nomor HP</label>
          <input type="text" id="register-hp" name="no_hp" placeholder="Enter your phone number" required>
        </div>

        <div class="form-group">
          <label for="register-password">Password</label>
          <input type="password" id="register-password" name="password" placeholder="Create a password" required>
        </div>

        <button type="submit" class="register-btn">Register</button>
      </form>

      <div class="login-link">
        <p>Sudah punya akun? <a href="{{ route('login') }}">Masuk</a></p>
      </div>
    </div>
  </div>

</body>

</html>