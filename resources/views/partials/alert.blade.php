@if(session('alert'))
  <div class="alert alert-{{ session('alert-type', 'info') }}">
    <div class="container">
      {{ session('alert') }}
    </div>
  </div>
@endif