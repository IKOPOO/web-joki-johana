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

  <!-- <link rel="stylesheet" href="{{ asset('css/style.min.css') }}"> -->

  <!-- Font Poppins -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

  <!-- Flatpickr -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/material_orange.css">

  <!-- Stack Styles -->
  @stack('styles')

</head>

<body>

  <!-- NAVBAR -->
  @if((!isset($hideNavbar) || !$hideNavbar) && !request()->has('preview'))
    @include('partials.navbar')
  @elseif(request()->has('preview'))
    <a href="javascript:void(0)" onclick="window.close()"
      style="position: fixed; top: 20px; left: 20px; z-index: 9999; background: white; padding: 10px 20px; border-radius: 50px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); text-decoration: none; color: #333; font-weight: 600; display: flex; align-items: center; gap: 8px; transition: all 0.3s ease;">
      <i class="fas fa-arrow-left"></i> Kembali (Tutup Preview)
    </a>
  @endif

  <!-- ALERT -->
  @include('partials.alert')

  <!-- KONTEN UTAMA MASUK DI SINI -->
  @yield('content')

  <!-- FOOTER -->
  @if((!isset($hideFooter) || !$hideFooter) && !request()->has('preview'))
    @include('partials.footer')
  @endif

  <!-- Panggil Script JS -->
  <script src="{{ asset('js/script.js') }}"></script>
  <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

  <!-- Stack Scripts -->
  @stack('scripts')
</body>

</html>