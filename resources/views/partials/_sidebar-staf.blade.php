<nav class="sidebar sidebar-offcanvas" id="sidebar">
  <ul class="nav">

    <li class="nav-item nav-category">Menu Staf</li>

    <li class="nav-item">
      <a class="nav-link" href="{{ route('staf.dashboard') }}">
        <i class="mdi mdi-view-dashboard menu-icon"></i>
        <span class="menu-title">Dashboard</span>
      </a>
    </li>

    <li class="nav-item">
      <a class="nav-link" href="{{ route('staf.peminjaman') }}">
        <i class="mdi mdi-check-all menu-icon"></i>
        <span class="menu-title">Validasi Peminjaman</span>
      </a>
    </li>

    <li class="nav-item">
      <a class="nav-link" href="{{ route('staf.pengembalian') }}">
        <i class="mdi mdi-autorenew menu-icon"></i>
        <span class="menu-title">Pengembalian Alat</span>
      </a>
    </li>

    <li class="nav-item">
      <a class="nav-link" href="{{ route('staf.kerusakan') }}">
        <i class="mdi mdi-alert menu-icon"></i>
        <span class="menu-title">Catat Kerusakan</span>
      </a>
    </li>

    <li class="nav-item">
      <a class="nav-link" href="{{ route('staf.sop') }}">
        <i class="mdi mdi-file-upload menu-icon"></i>
        <span class="menu-title">Upload SOP</span>
      </a>
    </li>

  </ul>
</nav>