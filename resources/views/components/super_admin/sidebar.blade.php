<!-- partial:partials/_sidebar.html -->
        <nav class="sidebar sidebar-offcanvas" id="sidebar">
          <ul class="nav">
            <li class="nav-item">
              <a class="nav-link" href="{{ route('super_admin.dashboard') }}">
                <i class="mdi mdi-home menu-icon"></i>
                <span class="menu-title">Dashboard</span>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="{{ route('super_admin.data_siswa') }}">
                <i class="mdi mdi-account menu-icon"></i>
                <span class="menu-title">Data Siswa</span>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="{{ route('super_admin.data_tagihan_siswa') }}">
                <i class="mdi mdi-cash menu-icon"></i>
                <span class="menu-title">Data Tagihan Siswa</span>
              </a>
            </li>
            <span class="mt-3 mx-3"><small><strong>Laporan</strong></small></span>
            <li class="nav-item">
              <a class="nav-link" href="{{ route('super_admin.laporan_tunggakan_siswa') }}">
                <i class="mdi mdi-file-document-alert menu-icon"></i>
                <span class="menu-title">Laporan Tunggakan Siswa</span>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="{{ route('super_admin.laporan_penerimaan') }}">
                <i class="mdi mdi-file-document-check menu-icon"></i>
                <span class="menu-title">Laporan Penerimaan</span>
              </a>
            </li>
            <span class="mt-3 mx-3"><small><strong>Data Umum</strong></small></span>
            <li class="nav-item">
              <a class="nav-link" href="{{ route('super_admin.data_kelas') }}">
                <i class="mdi mdi-book-variant menu-icon"></i>
                <span class="menu-title">Data Kelas</span>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="{{ route('super_admin.data_angkatan') }}">
                <i class="mdi mdi-calendar-account menu-icon"></i>
                <span class="menu-title">Data Angkatan</span>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="{{ route('super_admin.data_tahun_ajaran') }}">
                <i class="mdi mdi-calendar-clock menu-icon"></i>
                <span class="menu-title">Data Tahun Ajaran</span>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="{{ route('super_admin.data_admin') }}">
                <i class="mdi mdi-account-cog menu-icon"></i>
                <span class="menu-title">Data Admin</span>
              </a>
            </li>
          </ul>
        </nav>