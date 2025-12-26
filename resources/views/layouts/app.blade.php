<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Bilik Foto</title>

  <!-- Panggil CSS -->
  <link rel="stylesheet" href="{{ asset('css/navbar.css') }}">
  <link rel="stylesheet" href="{{ asset('css/style.css') }}">
  <link rel="stylesheet" href="{{ asset('css/footer.css') }}">
  <link rel="stylesheet" href="{{ asset('css/login.css') }}">
  <!-- <link rel="stylesheet" href="{{ asset('css/style.min.css') }}"> -->

  <!-- Font Poppins -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

</head>

<body>

  <!-- NAVBAR -->
  @include('partials.navbar')

  <!-- ALERT -->
  @include('partials.alert')

  <!-- KONTEN UTAMA MASUK DI SINI -->
  @yield('content')

  <!-- FOOTER -->
  @include('partials.footer')

  <!-- Panggil Script JS -->
  <script src="{{ asset('js/script.js') }}"></script>
</body>

</html>