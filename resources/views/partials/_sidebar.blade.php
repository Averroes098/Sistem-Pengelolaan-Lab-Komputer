<nav class="sidebar sidebar-offcanvas" id="sidebar">
  <ul class="nav">
    <li class="nav-item nav-category">Main Menu</li>
    @if(auth()->user()->level == 'admin')
    <li class="nav-item">
      <a class="nav-link" href="{{ route('admin.dashboard') }}">
        <i class="menu-icon typcn typcn-document-text"></i>
        <span class="menu-title">Dashboard</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="{{ route('laboratorium.index') }}">
        <i class="menu-icon typcn typcn-document-text"></i>
        <span class="menu-title">Laboratorium</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="{{ route('peminjaman.index') }}">
        <i class="menu-icon typcn typcn-document-text"></i>
        <span class="menu-title">Peminjaman</span>
      </a>
    </li>
    @endif
  </ul>
</nav>