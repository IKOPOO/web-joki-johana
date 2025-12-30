<aside class="sidebar">
  <div class="sidebar-header">
    <h2><i class="fas fa-camera-retro"></i> Lensia</h2>
  </div>

  <nav class="sidebar-nav">
    <ul>
      {{-- MENU LENSIA_ADMIN --}}
      @if(auth()->user()->role === 'LENSIA_ADMIN')
        <li>
          <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="fas fa-home"></i>
            <span>Dashboard</span>
          </a>
        </li>
        <li>
          <a href="{{ route('admin.studios.index') }}"
            class="{{ request()->routeIs('admin.studios.*') ? 'active' : '' }}">
            <i class="fas fa-building"></i>
            <span>Studio</span>
          </a>
        </li>
        <!-- Shared Menus for Admin Order -->
        <li>
          <a href="{{ route('admin.bookings.index') }}"
            class="{{ request()->routeIs('admin.bookings.*') ? 'active' : '' }}">
            <i class="fas fa-calendar-check"></i>
            <span>Booking</span>
          </a>
        </li>
        <li>
          <a href="{{ route('admin.bookings.verification') }}"
            class="{{ request()->routeIs('admin.bookings.verification') ? 'active' : '' }}">
            <i class="fas fa-check-double"></i>
            <span>Verifikasi Pembayaran</span>
          </a>
        </li>
        <li>
          <a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
            <i class="fas fa-users"></i>
            <span>User</span>
          </a>
        </li>
      @endif

      {{-- MENU STUDIO_STAF --}}
      @if(auth()->user()->role === 'STUDIO_STAF')
        {{-- 1. Dashboard --}}
        <li>
          <a href="{{ route('staff.dashboard') }}" class="{{ request()->routeIs('staff.dashboard') ? 'active' : '' }}">
            <i class="fas fa-home"></i>
            <span>Dashboard</span>
          </a>
        </li>

        {{-- 2. Jam Operasional --}}
        <li>
          <a href="{{ route('staff.operational.index') }}"
            class="{{ request()->routeIs('staff.operational.*') ? 'active' : '' }}">
            <i class="fas fa-clock"></i>
            <span>Jam Operasional</span>
          </a>
        </li>

        {{-- 3. Slot Sesi --}}
        <li>
          <a href="{{ route('staff.session-slots.index') }}"
            class="{{ request()->routeIs('staff.session-slots.*') ? 'active' : '' }}">
            <i class="fas fa-calendar-alt"></i>
            <!-- Changed icon slightly for variety if desired, or keep calendar-check -->
            <span>Slot Sesi</span>
          </a>
        </li>

        {{-- 4. Reservasi (Renamed from Booking) --}}
        <li>
          <a href="{{ route('admin.bookings.index') }}"
            class="{{ request()->routeIs('admin.bookings.*') ? 'active' : '' }}">
            <i class="fas fa-calendar-check"></i>
            <span>Reservasi</span>
          </a>
        </li>

        {{-- 5. Verifikasi --}}
        <li>
          <a href="{{ route('admin.bookings.verification') }}"
            class="{{ request()->routeIs('admin.bookings.verification') ? 'active' : '' }}">
            <i class="fas fa-check-double"></i>
            <span>Verifikasi</span>
          </a>
        </li>

        {{-- 6. Package --}}
        <li>
          <a href="{{ route('admin.packages.index', ['studio' => auth()->user()->studio_id]) }}"
            class="{{ request()->routeIs('admin.packages.*') ? 'active' : '' }}">
            <i class="fas fa-box"></i>
            <span>Package</span>
          </a>
        </li>

        {{-- 7. Preview Studio --}}
        <li>
          <a href="{{ route('staff.studio.preview') }}"
            class="{{ request()->routeIs('staff.studio.preview') ? 'active' : '' }}">
            <i class="fas fa-globe"></i>
            <span>Preview Studio</span>
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