<!-- partial:partials/_sidebar.html -->
        <nav class="sidebar sidebar-offcanvas" id="sidebar">
          <ul class="nav">
            <li class="nav-item">
              <a class="nav-link" href="{{ route('siswa.dashboard') }}">
                <i class="mdi mdi-home menu-icon"></i>
                <span class="menu-title">Dashboard</span>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="{{ route('siswa.profil.index') }}">
                <i class="mdi mdi-account menu-icon"></i>
                <span class="menu-title">Profil</span>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="{{ route('siswa.tagihan.index') }}">
                <i class="mdi mdi-cash menu-icon"></i>
                <span class="menu-title">Tagihan</span>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="{{ route('siswa.pembayaran.index') }}">
                <i class="mdi mdi-history menu-icon"></i>
                <span class="menu-title">Riwayat Pembayaran</span>
              </a>
            </li>
          </ul>
        </nav>