<nav class="sidebar sidebar-offcanvas" id="sidebar">
  <ul class="nav">
    <li class="nav-item nav-category">Main Menu</li>

    {{-- MENU ADMIN (Tetap) --}}
    @if(auth()->check() && auth()->user()->level == 'admin')
    {{-- ... (kode admin tetap sama) ... --}}
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

    {{-- MENU STAF (Tetap) --}}
    @if(auth()->check() && auth()->user()->level == 'staf')
    {{-- ... (kode staf tetap sama) ... --}}
    <li class="nav-item">
      <a class="nav-link" href="{{ route('staf.dashboard') }}">
        <i class="menu-icon typcn typcn-home"></i>
        <span class="menu-title">Dashboard</span>
      </a>
    </li>
    {{-- ... menu staf lainnya ... --}}
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
      {{-- UBAH LABEL DI SINI --}}
      <a class="nav-link" href="{{ route('kadep.alat.index') }}">
        <i class="menu-icon typcn typcn-document-text"></i>
        <span class="menu-title">Kerusakan Alat</span>
      </a>
    </li>
    @endif

  </ul>
</nav>