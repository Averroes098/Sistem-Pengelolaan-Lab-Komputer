<nav class="sidebar sidebar-offcanvas" id="sidebar">
  <ul class="nav">
    <li class="nav-item nav-category">Main Menu</li>

    {{-- MENU STAF --}}
    @if(auth()->check() && auth()->user()->level == 'staf')
      {{-- Dashboard --}}
      <li class="nav-item">
        <a class="nav-link" href="{{ route('staf.dashboard') }}">
          <i class="menu-icon typcn typcn-home"></i>
          <span class="menu-title">Dashboard</span>
        </a>
      </li>

      {{-- Validasi Peminjaman --}}
      <li class="nav-item">
        <a class="nav-link" href="{{ route('staf.peminjaman') }}">
          <i class="menu-icon typcn typcn-tick"></i>
          <span class="menu-title">Validasi Peminjaman</span>
        </a>
      </li>

      {{-- Pengembalian --}}
      <li class="nav-item">
        <a class="nav-link" href="{{ route('staf.pengembalian') }}">
          <i class="menu-icon typcn typcn-refresh"></i>
          <span class="menu-title">Pengembalian</span>
        </a>
      </li>

      {{-- Laporan Peminjaman (opsional) --}}
      <li class="nav-item">
        <a class="nav-link" href="{{ route('staf.laporan.peminjaman') }}">
          <i class="menu-icon typcn typcn-document"></i>
          <span class="menu-title">Laporan Peminjaman</span>
        </a>
      </li>
    @endif

  </ul>
</nav>