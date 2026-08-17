<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title', 'Dashboard')</title>

  <link rel="shortcut icon" type="image/png" href="{{ asset('admin/assets/images/logos/logosma.png') }}" />
  <link rel="stylesheet" href="{{ asset('admin/assets/css/styles.min.css') }}" />

  <style>
    html,
    body {
      margin: 0;
      padding: 0;
      height: 100%;
      overflow-x: hidden;
    }

    * {
      box-sizing: border-box;
    }

    .app-header {
      position: fixed;
      top: 0;
      left: 270px !important;
      width: calc(100% - 270px);
      height: 64px;
      z-index: 999;
      background: #fff;
      border-bottom: 1px solid #edf0f5;
      box-shadow: 0 1px 4px rgba(0,0,0,0.03);
    }

    .left-sidebar {
      position: fixed;
      top: 0;
      left: 0;
      width: 270px;
      height: 100vh;
      z-index: 1000;
      background: #fff;
      border-right: 1px solid #edf0f5;
      overflow: hidden;
    }

    .sidebar-inner-wrap {
      position: relative;
      height: 100%;
      display: flex;
      flex-direction: column;
    }


    .brand-logo {
      padding: 16px 20px !important;
      min-height: auto !important;
      border-bottom: 1px solid #edf0f5;
      background: #fff;
    }

    .sidebar-nav {
      flex: 1;
      overflow-y: auto;
      overflow-x: hidden;
      padding: 16px !important;
      padding-bottom: 170px !important;
      padding-top: 18px !important;
    }

    .body-wrapper {
      margin-left: 270px !important;
      width: calc(100% - 270px) !important;
      min-height: 100vh;
      padding: 0 !important;
    }
    .body-wrapper-inner {
      padding: 24px 28px !important;
      background: #f6f7fb;
      min-height: 100vh;
      padding-left: 10px !important;
    }

    .app-header .navbar {
      height: 64px;
      min-height: 64px;
      margin: 0 !important;
      padding-top: 0 !important;
      padding-bottom: 0 !important;
      display: flex;
      align-items: center;
      justify-content: space-between;

    }

    .body-wrapper-inner {
      padding-top: 64px !important;
      padding-left: 0 !important;
      padding-right: 0 !important;
      background: #f6f7fb;
      min-height: 100vh;
    }

    .container-fluid {
      width: 100% !important;
      max-width: none !important;
      margin: 0 !important;
      padding: 24px 20px !important;
    }


    .notification-btn {
      width: 42px;
      height: 42px;
      border-radius: 12px;
      background: #fff;
      border: 1px solid #edf0f5;
      display: flex;
      align-items: center;
      justify-content: center;
      color: #1f2937;
    }

    .notification-btn:hover {
      background: #f3f6fb;
    }

    .notif-badge {
      position: absolute;
      top: -6px;
      right: -6px;
      background: #ff7058;
      color: #fff;
      font-size: 10px;
      min-width: 18px;
      height: 18px;
      border-radius: 999px;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .notification-dropdown {
      width: 320px;
      max-height: 360px;
      overflow-y: auto;
      border: none;
      border-radius: 16px;
      box-shadow: 0 12px 35px rgba(0,0,0,0.08);
      margin-top: 12px;
      right: 0 !important;
      left: auto !important;
      transform: none !important;
      z-index: 2000;
    }

  /* item notif */
    .notification-dropdown .dropdown-item {
      border-radius: 12px;
      padding: 12px 14px;
      transition: all 0.2s ease;
      white-space: normal;
    }

    .notification-dropdown .dropdown-item:hover {
      background-color: #f5f7ff;
    }

    .notification-dropdown .notif-title {
      font-size: 14px;
      font-weight: 600;
      color: #1f2937;
      margin-bottom: 4px;
    }

    .notification-dropdown .notif-text {
      font-size: 13px;
      color: #6b7280;
      line-height: 1.4;
    }

    .notification-dropdown .notif-time {
      font-size: 11px;
      color: #9ca3af;
    }

    .notification-dropdown .notif-unread {
      background: #eef4ff;
    }

    .app-header .dropdown-menu {
      position: absolute !important;
    }

    .navbar-nav .dropdown {
      position: relative;
    }

    .notification-btn {
      position: relative;
      z-index: 1001;
    }

    .notif-item {
      padding: 10px 12px;
      border-radius: 12px;
      background: #f8fafc;
    }

    .notif-item p {
      white-space: normal;
      line-height: 1.4;
    }

    .notif-link {
      display: block;
      border-radius: 12px;
      transition: 0.2s ease;
    }

    .notif-link:hover {
      background: #f3f6fb;
    }

    .card {
      width: 100%;
    }

    .app-sidebar-link {
      border-radius: 12px;
    }

    .app-sidebar-link.active {
      background-color: #0d6efd;
      color: #fff !important;
      border-radius: 12px;
    }

    .app-sidebar-link.active i {
      color: #fff !important;
    }

    .sidebar-footer-user {
      position: absolute;
      left: 16px;
      right: 16px;
      bottom: 16px;
      padding: 12px;
      border-radius: 14px;
      background: #fff;
      border: 1px solid #edf0f5;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.03);
      z-index: 20;
    }

    .user-dropdown {
      position: relative;
    }

    .user-info {
      display: flex;
      align-items: center;
      gap: 12px;
      cursor: pointer;
    }

    .user-avatar {
      width: 45px;
      height: 45px;
      border-radius: 50%;
      object-fit: cover;
      flex-shrink: 0;
    }

    .user-text {
      display: flex;
      flex-direction: column;
      min-width: 0;
    }

    .user-name {
      font-weight: 600;
      font-size: 14px;
      color: #2c3e50;
      line-height: 1.2;
    }

    .user-email {
      font-size: 12px;
      color: #9ca3af;
      line-height: 1.2;
      word-break: break-word;
    }

    .user-menu {
      position: absolute;
      bottom: calc(100% + 10px);
      left: 0;
      width: 220px;
      background: #fff;
      border: 1px solid #edf0f5;
      border-radius: 12px;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
      display: none;
      flex-direction: column;
      overflow: hidden;
      z-index: 1100;
    }

    .user-menu.show {
      display: flex;
      animation: fadeDropdown .2s ease;
    }

    @keyframes fadeDropdown {
      from {
        opacity: 0;
        transform: translateY(8px);
      }

      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .user-menu-item {
      padding: 10px 14px;
      font-size: 13px;
      text-decoration: none;
      color: #374151;
      display: block;
      transition: 0.2s;
      background: none;
      border: none;
      text-align: left;
      width: 100%;
    }

    .user-menu-item:hover {
      background: #f3f6fb;
    }

    .logout-btn {
      color: #ef4444;
    }

    .body-wrapper {
        overflow-x: hidden;
    }

    @media screen {
        .body-wrapper {
            overflow-x: hidden;
        }

        .container-fluid {
            overflow: hidden;
        }

        .card,
        .card-body {
            overflow: visible;
        }

        .table-responsive {
            width: 100%;
            overflow-x: auto !important;
            -webkit-overflow-scrolling: touch;
        }
    }


    .left-sidebar {
      top: 0 !important;
      height: 100vh !important;
      min-height: 100vh !important;
      padding-top: 0 !important;
      margin-top: 0 !important;
      position: fixed !important;
    }

    .sidebar-inner-wrap {
      height: 100vh !important;
      min-height: 100vh !important;
      padding-top: 0 !important;
      margin-top: 0 !important;
    }


    .page-wrapper {
      min-height: 100vh !important;
      padding-top: 0 !important;
      margin-top: 0 !important;
    }

    .sidebar-footer-user {
      padding: 10px !important;
    }

    .user-avatar {
      width: 40px !important;
      height: 40px !important;
    }

    @media print {

      .body-wrapper,
      .body-wrapper-inner,
      .container-fluid,
      .card,
      .card-body {
        overflow: visible !important;
        height: auto !important;
        max-height: none !important;
      }

      .body-wrapper{
        margin-left:0 !important;
        width:100% !important;
      }

      .app-header{
        display:none !important;
      }

  }

  /* =========================================
   RESPONSIVE TABLET & MOBILE
========================================= */
@media (max-width: 1199.98px) {

    /* Konten memakai seluruh lebar layar */
    .body-wrapper {
        margin-left: 0 !important;
        width: 100% !important;
        max-width: 100% !important;
    }

    /* Header juga memenuhi layar */
    .app-header {
        left: 0 !important;
        width: 100% !important;
    }

    /* Sidebar menjadi sidebar buka-tutup */
    .left-sidebar {
        left: -270px !important;
        width: 270px !important;
        transition: left 0.3s ease;
    }

    /*
       Template akan memberi class show-sidebar
       ketika tombol hamburger ditekan
    */
    #main-wrapper.show-sidebar .left-sidebar {
        left: 0 !important;
    }

    .body-wrapper-inner {
        width: 100% !important;
        margin-left: 0 !important;
    }

    .container-fluid,
    .main-content-wrap {
        width: 100% !important;
        max-width: 100% !important;
        margin: 0 !important;
        padding-left: 20px !important;
        padding-right: 20px !important;
    }
}


/* =========================================
   KHUSUS HP
========================================= */
@media (max-width: 575.98px) {

    .container-fluid,
    .main-content-wrap {
        padding-left: 14px !important;
        padding-right: 14px !important;
    }

    .app-header .navbar {
        padding-left: 14px !important;
        padding-right: 14px !important;
    }

    /* Dropdown notifikasi jangan keluar layar */
    .notification-dropdown {
        width: calc(100vw - 28px) !important;
        max-width: 320px !important;
        right: 0 !important;
    }

    /* Judul tidak terlalu besar di HP */
    h4 {
        font-size: 1.35rem;
    }

    h5 {
        font-size: 1rem;
    }

    /* Tabel tetap bisa digeser jika kolom banyak */
    .table-responsive {
        width: 100% !important;
        overflow-x: auto !important;
        -webkit-overflow-scrolling: touch;
    }
}

  </style>

  @yield('styles')
</head>

<body>
  <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
    data-sidebar-position="fixed" data-header-position="fixed">

    <aside class="left-sidebar">
      <div class="sidebar-inner-wrap">

        <div class="brand-logo d-flex align-items-center justify-content-between">
          <a href="{{ url('/dashboard') }}" class="text-nowrap logo-img d-flex align-items-center gap-2 text-decoration-none">
            <img src="{{ asset('admin/assets/images/logos/logosma.png') }}" alt="" width="40">
            <div>
              <div class="fw-bold text-dark" style="font-size: 14px;">SMAS Mathla'ul Anwar</div>
              <small class="text-muted">Sistem Monitoring Kehadiran</small>
            </div>
          </a>

          <div class="close-btn d-xl-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
            <i class="ti ti-x fs-6"></i>
          </div>
        </div>

        <nav class="sidebar-nav">
  <ul id="sidebarnav" class="pt-2">

    {{-- ADMIN --}}
    @if(auth()->check() && auth()->user()->role === 'admin')
      <li class="sidebar-item">
          <a class="sidebar-link {{ request()->is('dashboard') ? 'active' : '' }}" href="{{ url('/dashboard') }}">
            <i class="ti ti-layout-dashboard"></i>
            <span>Dashboard</span>
          </a>
      </li>

        <li class="sidebar-item mb-2">
            <a class="sidebar-link app-sidebar-link {{ request()->is('siswa*') ? 'active' : '' }}"
               href="{{ route('siswa.index') }}">
                <i class="ti ti-users"></i>
                <span class="hide-menu">Data Siswa</span>
            </a>
        </li>

        <li class="sidebar-item mb-2">
            <a class="sidebar-link app-sidebar-link {{ request()->is('absensi*') ? 'active' : '' }}"
               href="{{ route('absensi-harian.index') }}">
                <i class="ti ti-notebook"></i>
                <span class="hide-menu">Absensi Siswa</span>
            </a>
        </li>

        <li class="sidebar-item mb-2">
            <a class="sidebar-link app-sidebar-link {{ request()->is('notifikasi*') ? 'active' : '' }}"
               href="{{ route('notifikasi.index') }}">
                <i class="ti ti-bell"></i>
                <span class="hide-menu">Notifikasi</span>
            </a>
        </li>
        <li class="sidebar-item mb-2">
            <a class="sidebar-link app-sidebar-link" href="{{ route('guru.index') }}">
                <i class="ti ti-user"></i>
                <span class="hide-menu">Data Guru</span>
            </a>
        </li>

        <li class="sidebar-item mb-2">
            <a class="sidebar-link app-sidebar-link" href="{{ route('kelas.index') }}">
                <i class="ti ti-school"></i>
                <span class="hide-menu">Data Kelas</span>
            </a>
        </li>

        <li class="sidebar-item mb-2">
            <a class="sidebar-link app-sidebar-link" href="{{ route('mata-pelajaran.index') }}">
                <i class="ti ti-book"></i>
                <span class="hide-menu">Mata Pelajaran</span>
            </a>
        </li>

        <li class="sidebar-item mb-2">
            <a class="sidebar-link app-sidebar-link" href="{{ route('jadwal-pelajaran.index') }}">
                <i class="ti ti-calendar"></i>
                <span class="hide-menu">Jadwal Pelajaran</span>
            </a>
        </li>

        <li class="sidebar-item mb-2">
            <a class="sidebar-link app-sidebar-link {{ request()->routeIs('laporan.absensi-harian') ? 'active' : '' }}"
              href="{{ route('laporan.absensi-harian') }}">
                <i class="ti ti-printer"></i>
                <span class="hide-menu">Cetak Rekap</span>
            </a>
        </li>

        <li class="sidebar-item mb-2">
            <a class="sidebar-link app-sidebar-link {{ request()->routeIs('user.ganti-password') ? 'active' : '' }}"
              href="{{ route('user.ganti-password') }}">
                <i class="ti ti-lock"></i>
                <span class="hide-menu">Ganti Password</span>
            </a>
        </li>
    @endif


    {{-- ORANG TUA/WALI --}}
    @if(auth()->check() && auth()->user()->role === 'orang_tua')
        <li class="sidebar-item">
            <a class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                href="{{ route('dashboard') }}">
                  <i class="ti ti-layout-dashboard"></i>
                  <span>Dashboard</span>
            </a>
        </li>

        <li class="sidebar-item mb-2">
            <a class="sidebar-link app-sidebar-link
                {{ request()->routeIs('orang-tua.absensi-mapel') ? 'active' : '' }}"
              href="{{ route('orang-tua.absensi-mapel') }}">
                <i class="ti ti-notebook"></i>
                <span class="hide-menu">Absensi Per Mapel</span>
            </a>
        </li>

        <li class="sidebar-item mb-2">
            <a class="sidebar-link app-sidebar-link
                {{ request()->routeIs('orang-tua.jadwal') ? 'active' : '' }}"
              href="{{ route('orang-tua.jadwal') }}">
                <i class="ti ti-calendar"></i>
                <span class="hide-menu">Jadwal Pelajaran</span>
            </a>
        </li>

        <li class="sidebar-item mb-2">
            <a class="sidebar-link app-sidebar-link
                {{ request()->routeIs('orang-tua.profil-anak') ? 'active' : '' }}"
              href="{{ route('orang-tua.profil-anak') }}">
                <i class="ti ti-user"></i>
                <span class="hide-menu">Profil Anak</span>
            </a>
        </li>

        <li class="sidebar-item mb-2">
            <a class="sidebar-link app-sidebar-link
                {{ request()->routeIs('notifikasi.index') ? 'active' : '' }}"
              href="{{ route('notifikasi.index') }}">
                <i class="ti ti-bell"></i>
                <span class="hide-menu">Notifikasi</span>
            </a>
        </li>

        <li class="sidebar-item mb-2">
            <a class="sidebar-link app-sidebar-link
                {{ request()->routeIs('user.ganti-password') ? 'active' : '' }}"
              href="{{ route('user.ganti-password') }}">
                <i class="ti ti-lock"></i>
                <span class="hide-menu">Ganti Password</span>
            </a>
        </li>
      @endif


    {{-- GURU --}}
    @if(auth()->check() && auth()->user()->role === 'guru')
        <li class="sidebar-item">
            <a class="sidebar-link {{ request()->is('dashboard') ? 'active' : '' }}" href="{{ url('/dashboard') }}">
                <i class="ti ti-layout-dashboard"></i>
                <span>Dashboard</span>
            </a>
        </li>

        <li class="sidebar-item mb-2">
            <a class="sidebar-link app-sidebar-link {{ request()->routeIs('guru.jadwal-saya') ? 'active' : '' }}"
                href="{{ route('guru.jadwal-saya') }}">
                  <i class="ti ti-calendar"></i>
                  <span class="hide-menu">Jadwal Saya</span>
            </a>
        </li>

        <li class="sidebar-item mb-2">
            <a class="sidebar-link app-sidebar-link {{ request()->routeIs('guru.absensi-mapel.rekap') ? 'active' : '' }}"
               href="{{ route('guru.absensi-mapel.rekap') }}">
                <i class="ti ti-notebook"></i>
                <span class="hide-menu">Rekap Absensi</span>
            </a>
        </li>
        <li class="sidebar-item mb-2">
            <a class="sidebar-link app-sidebar-link {{ request()->routeIs('user.ganti-password') ? 'active' : '' }}"
                href="{{ route('user.ganti-password') }}">
                  <i class="ti ti-lock"></i>
                  <span class="hide-menu">Ganti Password</span>
            </a>
        </li>
    @endif


    {{-- WALI KELAS --}}
    @if(auth()->check() && auth()->user()->role === 'wali_kelas')
        <li class="sidebar-item">
            <a class="sidebar-link {{ request()->is('dashboard') ? 'active' : '' }}" href="{{ url('/dashboard') }}">
                <i class="ti ti-layout-dashboard"></i>
                <span>Dashboard</span>
            </a>
        </li>

        <li class="sidebar-item mb-2">
            <a class="sidebar-link app-sidebar-link {{ request()->routeIs('wali-kelas.monitor-absensi-harian') ? 'active' : '' }}"
               href="{{ route('wali-kelas.monitor-absensi-harian') }}">
                <i class="ti ti-clipboard-list"></i>
                <span class="hide-menu">Monitor Kehadiran Harian</span>
            </a>
        </li>

        <li class="sidebar-item mb-2">
            <a class="sidebar-link app-sidebar-link {{ request()->routeIs('wali-kelas.siswa-alpha') ? 'active' : '' }}"
               href="{{ route('wali-kelas.siswa-alpha') }}">
                <i class="ti ti-alert-triangle"></i>
                <span class="hide-menu">Siswa Alpha</span>
            </a>
        </li>

        <li class="sidebar-item mb-2">
            <a class="sidebar-link app-sidebar-link {{ request()->routeIs('wali-kelas.rekap-kehadiran') ? 'active' : '' }}"
               href="{{ route('wali-kelas.rekap-kehadiran') }}">
                <i class="ti ti-chart-bar"></i>
                <span class="hide-menu">Rekap Kehadiran</span>
            </a>
        </li>

        <li class="sidebar-item mb-2">
            <a class="sidebar-link app-sidebar-link {{ request()->routeIs('notifikasi.index') ? 'active' : '' }}"
               href="{{ route('notifikasi.index') }}">
                <i class="ti ti-speakerphone"></i>
                <span class="hide-menu">Notifikasi</span>
            </a>
        </li>

        <li class="sidebar-item mb-2">
            <a class="sidebar-link app-sidebar-link {{ request()->routeIs('user.ganti-password') ? 'active' : '' }}"
              href="{{ route('user.ganti-password') }}">
                <i class="ti ti-lock"></i>
                <span class="hide-menu">Ganti Password</span>
            </a>
        </li>
    @endif


    {{-- LOGOUT --}}
    <li class="sidebar-item mt-4">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="sidebar-link text-danger border-0 bg-transparent w-100 text-start">
                <i class="ti ti-logout"></i>
                <span class="hide-menu">Keluar</span>
            </button>
        </form>
    </li>

  </ul>
</nav>


       <div class="sidebar-footer-user">
          <a href="{{ auth()->user()->role === 'orang_tua'
                  ? route('orang-tua.profil-anak')
                  : route('profile.show') }}"
            class="user-info text-decoration-none">

              <img
                  src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name ?? 'User') }}&background=E5E7EB&color=374151"
                  class="user-avatar" alt="User Avatar">

              <div class="user-text">
                  <div class="user-name">{{ auth()->user()->name ?? 'User' }}</div>
                  <div class="user-email">{{ auth()->user()->email ?? '-' }}</div>
              </div>
          </a>
      </div>


      </div>
    </aside>

    <div class="body-wrapper">
        <header class="app-header" style="margin-bottom: 0; padding-bottom: 0;">
        <nav class="navbar navbar-expand-lg navbar-light bg-white px-4">
          <ul class="navbar-nav">
            <li class="nav-item d-block d-xl-none">
              <a class="nav-link sidebartoggler" id="headerCollapse" href="javascript:void(0)">
                <i class="ti ti-menu-2"></i>
              </a>
            </li>
          </ul>

          <div class="navbar-collapse justify-content-end px-0">
              <ul class="navbar-nav flex-row align-items-center gap-2">


                  <li class="nav-item dropdown me-3">
                      <a class="nav-link notification-btn position-relative" href="#" role="button" data-bs-toggle="dropdown">
                          <i class="ti ti-bell fs-5"></i>

                          @if(($notifCount ?? 0) > 0)
                            <span class="notif-badge">
                                {{ $notifCount }}
                            </span>
                          @endif
                      </a>

                      <div class="dropdown-menu notification-dropdown p-3">
                          <h6 class="dropdown-header fw-bold">Notifikasi</h6>

                         @forelse(($notifikasi ?? [])->take(3) as $item)
                            <a href="{{ route('notifikasi.open', $item->id) }}"
                                class="dropdown-item text-decoration-none py-2 {{ !$item->sudah_dibaca ? 'notif-unread' : '' }}">

                                <div class="d-flex justify-content-between align-items-start gap-2">
                                    <strong class="{{ $item->sudah_dibaca ? 'fw-normal text-dark' : 'fw-bold text-dark' }}">
                                        {{ $item->judul }}
                                    </strong>

                                    @if(!$item->sudah_dibaca)
                                          <span class="badge bg-danger rounded-pill">Baru</span>
                                    @endif
                                </div>

                                <small class="text-muted d-block mt-1">
                                    {{ \Illuminate\Support\Str::limit($item->isi, 70) }}
                                </small>

                                <small class="notif-time d-block mt-1">
                                    {{ $item->created_at->timezone('Asia/Jakarta')->format('d M Y, H:i') }}
                                </small>
                            </a>
                        @empty
                            <div class="text-muted small px-2 py-2">Belum ada notifikasi</div>
                        @endforelse

                        <div class="mt-2 pt-2 border-top text-center">
                            <a href="{{ route('notifikasi.index') }}" class="text-primary text-decoration-none fw-semibold">
                                Lihat semua notifikasi
                            </a>
                        </div>
                      </div>
                  </li>
              </ul>
</div>
        </nav>
      </header>

      <div class="body-wrapper-inner">
        <div class="container-fluid main-content-wrap">
          @yield('content')
        </div>
      </div>
    </div>
  </div>

  <script src="{{ asset('admin/assets/libs/jquery/dist/jquery.min.js') }}"></script>
  <script src="{{ asset('admin/assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('admin/assets/js/sidebarmenu.js') }}"></script>
  <script src="{{ asset('admin/assets/js/app.min.js') }}"></script>
  <script src="{{ asset('admin/assets/libs/simplebar/dist/simplebar.js') }}"></script>
  <script src="{{ asset('admin/assets/libs/apexcharts/dist/apexcharts.min.js') }}"></script>
  <script src="https://cdn.jsdelivr.net/npm/iconify-icon@1.0.8/dist/iconify-icon.min.js"></script>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const userToggle = document.getElementById('userToggle');
      const userMenu = document.getElementById('userMenu');

      if (userToggle && userMenu) {
        userToggle.addEventListener('click', function (e) {
          e.stopPropagation();
          userMenu.classList.toggle('show');
        });

        document.addEventListener('click', function (e) {
          if (!userToggle.contains(e.target) && !userMenu.contains(e.target)) {
            userMenu.classList.remove('show');
          }
        });
      }
    });
  </script>

  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  @if(session('success'))
  <script>
  Swal.fire({
      icon: 'success',
      title: 'Berhasil',
      text: @json(session('success')),
      timer: 2000,
      showConfirmButton: false
  });
  </script>
  @endif

  @if(session('error'))
  <script>
  Swal.fire({
      icon: 'error',
      title: 'Gagal',
      text: @json(session('error'))
  });
  </script>
  @endif

  @yield('scripts')
</body>

</html>