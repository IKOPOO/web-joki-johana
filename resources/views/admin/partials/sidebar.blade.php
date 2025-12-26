<aside class="sidebar">
  <div class="sidebar-header">
    <h2><i class="fas fa-camera-retro"></i> Lensia</h2>
  </div>

  <nav class="sidebar-nav">
    <ul>
      @if(auth()->user()->role === 'LENSIA_ADMIN')
        <li>
          <a href="/dashboard" class="{{ request()->is('dashboard') ? 'active' : '' }}">
            <i class="fas fa-home"></i>
            <span>Dashboard</span>
          </a>
        </li>
        <li>
          <a href="/admin/studios" class="{{ request()->is('admin/studios') ? 'active' : '' }}">
            <i class="fas fa-building"></i>
            <span>Studio</span>
          </a>
        </li>
      @endif

      <li>
        <a href="/admin/bookings" class="{{ request()->is('admin/bookings*') ? 'active' : '' }}">
          <i class="fas fa-calendar-check"></i>
          <span>Booking</span>
        </a>
      </li>

      {{-- Common Menus or Specific Staff adjusted menus --}}
      @if(auth()->user()->role === 'STUDIO_STAF')
        <li>
          <a href="{{ route('admin.packages.index', ['studio' => auth()->user()->studio_id]) }}"
            class="{{ request()->routeIs('admin.packages.*') ? 'active' : '' }}">
            <i class="fas fa-box"></i>
            <span>Package</span>
          </a>
        </li>
      @endif

      @if(auth()->user()->role === 'LENSIA_ADMIN')
        <li>
          <a href="/admin/users" class="{{ request()->is('admin/users*') ? 'active' : '' }}">
            <i class="fas fa-users"></i>
            <span>User</span>
          </a>
        </li>
      @endif
    </ul>
  </nav>

  <div class="sidebar-footer">
    <form action="/logout" method="POST">
      @csrf
      <button type="submit" class="logout-btn">
        <i class="fas fa-sign-out-alt"></i>
        <span>Logout</span>
      </button>
    </form>
  </div>
</aside>