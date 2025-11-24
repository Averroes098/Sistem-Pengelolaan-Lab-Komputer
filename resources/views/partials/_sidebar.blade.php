<nav class="sidebar sidebar-offcanvas" id="sidebar">
  <ul class="nav">
    <li class="nav-item nav-category">Main Menu</li>

    {{-- MENU ADMIN --}}
    @if(auth()->check() && auth()->user()->level == 'admin')
    <li class="nav-item">
      <a class="nav-link" href="{{ route('admin.dashboard') }}">
        <i class="menu-icon typcn typcn-document-text"></i>
        <span class="menu-title">Dashboard</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="{{ route('admin.laboratorium.index') }}">
        <i class="menu-icon typcn typcn-document-text"></i>
        <span class="menu-title">Laboratorium</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="{{ route('admin.peminjaman.index') }}">
        <i class="menu-icon typcn typcn-document-text"></i>
        <span class="menu-title">Peminjaman</span>
      </a>
    </li>
    @endif

    {{-- MENU STAF --}}
    @if(auth()->check() && auth()->user()->level == 'staf')
    <li class="nav-item">
      <a class="nav-link" href="{{ route('staf.dashboard') }}">
        <i class="menu-icon typcn typcn-home"></i>
        <span class="menu-title">Dashboard</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="{{ route('staf.peminjaman') }}">
        <i class="menu-icon typcn typcn-clipboard"></i>
        <span class="menu-title">Validasi Peminjaman</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="{{ route('staf.pengembalian') }}">
        <i class="menu-icon typcn typcn-backspace"></i>
        <span class="menu-title">Proses Pengembalian</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="{{ route('staf.kerusakan') }}">
        <i class="menu-icon typcn typcn-warning"></i>
        <span class="menu-title">Catat Kerusakan</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="{{ route('staf.sop') }}">
        <i class="menu-icon typcn typcn-upload"></i>
        <span class="menu-title">Upload SOP</span>
      </a>
    </li>
    @endif

    {{-- MENU KADEP --}}
    @if(auth()->check() && auth()->user()->level == 'kadep')
    <li class="nav-item">
      <a class="nav-link" href="{{ route('kadep.dashboard') }}">
        <i class="menu-icon typcn typcn-document-text"></i>
        <span class="menu-title">Dashboard</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="{{ route('kadep.peminjaman.index') }}">
        <i class="menu-icon typcn typcn-document-text"></i>
        <span class="menu-title">Peminjaman</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="{{ route('kadep.alat.index') }}">
        <i class="menu-icon typcn typcn-document-text"></i>
        <span class="menu-title">Alat</span>
      </a>
    </li>
    @endif

    {{-- MENU USER --}}
    @if(auth()->check() && auth()->user()->level == 'user')
    <li class="nav-item">
      <a class="nav-link" href="{{ route('dashboard') }}">
        <i class="menu-icon typcn typcn-document-text"></i>
        <span class="menu-title">Dashboard</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="{{ route('user.sop') }}">
        <i class="menu-icon typcn typcn-document-text"></i>
        <span class="menu-title">SOP Laboratorium</span>
      </a>
    </li>
    @endif

  </ul>
</nav>
