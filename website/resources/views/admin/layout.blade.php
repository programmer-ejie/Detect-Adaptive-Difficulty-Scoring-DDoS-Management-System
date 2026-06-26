<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>{{ config('app.name', 'DDoS Sentinel') }} | @yield('title', 'Admin')</title>
  <meta name="description" content="DDoS Sentinel admin panel">
  <meta name="author" content="DDoS Sentinel">
  <link rel="icon" href="{{ asset('template/admin/dist/assets/images/favicon.svg') }}" type="image/x-icon">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&display=swap" id="main-font-link">
  <link rel="stylesheet" href="{{ asset('template/admin/dist/assets/fonts/tabler-icons.min.css') }}">
  <link rel="stylesheet" href="{{ asset('template/admin/dist/assets/fonts/feather.css') }}">
  <link rel="stylesheet" href="{{ asset('template/admin/dist/assets/fonts/fontawesome.css') }}">
  <link rel="stylesheet" href="{{ asset('template/admin/dist/assets/fonts/material.css') }}">
  <link rel="stylesheet" href="{{ asset('template/admin/dist/assets/css/style.css') }}" id="main-style-link">
  <link rel="stylesheet" href="{{ asset('template/admin/dist/assets/css/style-preset.css') }}">
  <style>
    .pc-sidebar .navbar-content,
    .pc-sidebar .navbar-content .simplebar-content {
      display: flex;
      flex-direction: column;
      min-height: calc(100vh - 60px);
    }

    .pc-sidebar .pc-navbar {
      flex: 1 1 auto;
    }

    .sidebar-logout {
      padding: 0 24px 24px;
    }

    .m-header {
      padding: 18px 24px;
      border-bottom: 1px solid rgba(0, 0, 0, 0.08);
      background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
    }

    .b-brand {
      display: flex;
      align-items: center;
      gap: 12px;
      text-decoration: none;
      transition: all 0.3s ease;
      padding: 8px 12px;
      border-radius: 8px;
    }

    .b-brand:hover {
      background: rgba(24, 144, 255, 0.08);
      transform: translateX(2px);
    }

    .sidebar-brand-icon {
      width: 36px;
      height: 36px;
      background: linear-gradient(135deg, #1890ff 0%, #0050b3 100%);
      border-radius: 8px;
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      font-size: 18px;
      flex-shrink: 0;
      box-shadow: 0 4px 12px rgba(24, 144, 255, 0.3);
    }

    .sidebar-brand-text {
      display: flex;
      flex-direction: column;
      gap: 2px;
    }

    .sidebar-brand-text .brand-name {
      font-weight: 700;
      font-size: 14px;
      color: #1f2937;
      letter-spacing: -0.3px;
    }

    .sidebar-brand-text .brand-subtitle {
      font-size: 10px;
      color: #1890ff;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    .pc-item .pc-micon {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      width: 24px;
      height: 24px;
      font-size: 16px;
      margin-right: 8px;
      transition: all 0.3s ease;
      flex-shrink: 0;
    }

    .pc-item .pc-micon i {
      font-size: 16px;
      line-height: 1;
    }

    .pc-link {
      display: flex;
      align-items: center;
      transition: all 0.3s ease;
    }

    .pc-link:hover .pc-micon {
      transform: scale(1.1);
    }

    .pc-item.pc-caption {
      padding: 12px 24px 8px 24px;
      display: flex;
      align-items: center;
      justify-content: space-between;
    }

    .pc-item.pc-caption label {
      font-size: 11px;
      font-weight: 700;
      color: #9ca3af;
      text-transform: uppercase;
      letter-spacing: 1px;
      margin: 0;
      flex: 1;
    }

    .pc-item.pc-caption i {
      font-size: 14px;
      color: #d1d5db;
    }
  </style>
</head>
<body data-pc-preset="preset-1" data-pc-direction="ltr" data-pc-theme="light">
  @php($page = $page ?? 'dashboard')

  <div class="loader-bg">
    <div class="loader-track">
      <div class="loader-fill"></div>
    </div>
  </div>

  <nav class="pc-sidebar">
    <div class="navbar-wrapper">
      <div class="m-header">
        <a href="{{ route('admin.dashboard') }}" class="b-brand text-primary">
          <div class="sidebar-brand-icon">
            <i class="ti ti-shield"></i>
          </div>
          <div class="sidebar-brand-text">
            <span class="brand-name">DDoS Sentinel</span>
            <span class="brand-subtitle">Security</span>
          </div>
        </a>
      </div>
      <div class="navbar-content">
        <ul class="pc-navbar">
          <li class="pc-item pc-caption">
            <label>Main</label>
            <i class="ti ti-home"></i>
          </li>
          <li class="pc-item">
            <a href="{{ route('admin.dashboard') }}" class="pc-link {{ $page === 'dashboard' ? 'active' : '' }}">
              <span class="pc-micon"><i class="ti ti-dashboard"></i></span>
              <span class="pc-mtext">Dashboard</span>
            </a>
          </li>

          <li class="pc-item pc-caption">
            <label>Monitoring</label>
            <i class="ti ti-radar"></i>
          </li>
          <li class="pc-item">
            <a href="{{ route('admin.analysis') }}" class="pc-link {{ $page === 'analysis' ? 'active' : '' }}">
              <span class="pc-micon"><i class="ti ti-chart-line"></i></span>
              <span class="pc-mtext">Analysis</span>
            </a>
          </li>
          <li class="pc-item">
            <a href="{{ route('admin.alert') }}" class="pc-link {{ $page === 'alert' ? 'active' : '' }}">
              <span class="pc-micon"><i class="ti ti-bell-ringing"></i></span>
              <span class="pc-mtext">Alerts</span>
            </a>
          </li>

          <li class="pc-item pc-caption">
            <label>Defense</label>
            <i class="ti ti-shield"></i>
          </li>
          <li class="pc-item">
            <a href="{{ route('admin.mitigation') }}" class="pc-link {{ $page === 'mitigation' ? 'active' : '' }}">
              <span class="pc-micon"><i class="ti ti-shield-check"></i></span>
              <span class="pc-mtext">Mitigation</span>
            </a>
          </li>

          <li class="pc-item pc-caption">
            <label>Configuration</label>
            <i class="ti ti-adjustments"></i>
          </li>
          <li class="pc-item">
            <a href="{{ route('admin.settings') }}" class="pc-link {{ $page === 'settings' ? 'active' : '' }}">
              <span class="pc-micon"><i class="ti ti-settings"></i></span>
              <span class="pc-mtext">Settings</span>
            </a>
          </li>

        </ul>
        <div class="sidebar-logout mt-auto">
          <div class="pc-caption">
            <label>Session</label>
            <i class="ti ti-power"></i>
          </div>
          <a href="{{ url('/#login') }}" class="btn btn-danger w-100">
            <i class="ti ti-logout me-2"></i>Logout
          </a>
        </div>
      </div>
    </div>
  </nav>

  <header class="pc-header">
    <div class="header-wrapper">
      <div class="me-auto pc-mob-drp">
        <ul class="list-unstyled">
          <li class="pc-h-item pc-sidebar-collapse">
            <a href="#" class="pc-head-link ms-0" id="sidebar-hide">
              <i class="ti ti-menu-2"></i>
            </a>
          </li>
          <li class="pc-h-item pc-sidebar-popup">
            <a href="#" class="pc-head-link ms-0" id="mobile-collapse">
              <i class="ti ti-menu-2"></i>
            </a>
          </li>
          <li class="dropdown pc-h-item d-inline-flex d-md-none">
            <a class="pc-head-link dropdown-toggle arrow-none m-0" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
              <i class="ti ti-search"></i>
            </a>
            <div class="dropdown-menu pc-h-dropdown drp-search">
              <form class="px-3">
                <div class="form-group mb-0 d-flex align-items-center">
                  <i data-feather="search"></i>
                  <input type="search" class="form-control border-0 shadow-none" placeholder="Search here. . .">
                </div>
              </form>
            </div>
          </li>
          <li class="pc-h-item d-none d-md-inline-flex">
            <form class="header-search">
              <i data-feather="search" class="icon-search"></i>
              <input type="search" class="form-control" placeholder="Search here. . .">
            </form>
          </li>
        </ul>
      </div>
      <div class="ms-auto">
        <ul class="list-unstyled">
          <li class="dropdown pc-h-item">
            <a class="pc-head-link dropdown-toggle arrow-none me-0" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
              <i class="ti ti-bell-ringing"></i>
            </a>
            <div class="dropdown-menu dropdown-notification dropdown-menu-end pc-h-dropdown">
              <div class="dropdown-header d-flex align-items-center justify-content-between">
                <h5 class="m-0">Alerts</h5>
                <a href="#!" class="pc-head-link bg-transparent"><i class="ti ti-x text-danger"></i></a>
              </div>
              <div class="dropdown-divider"></div>
              <div class="dropdown-header px-0 text-wrap header-notification-scroll position-relative" style="max-height: calc(100vh - 215px)">
                <div class="list-group list-group-flush w-100">
                  <a class="list-group-item list-group-item-action">
                    <div class="d-flex">
                      <div class="flex-shrink-0">
                        <img src="{{ asset('template/admin/dist/assets/images/user/avatar-1.jpg') }}" alt="user-image" class="user-avtar">
                      </div>
                      <div class="flex-grow-1 ms-1">
                        <span class="float-end text-muted">3:00 AM</span>
                        <p class="text-body mb-1"><b>System Alert</b> attack pattern updated.</p>
                        <span class="text-muted">2 min ago</span>
                      </div>
                    </div>
                  </a>
                  <a class="list-group-item list-group-item-action">
                    <div class="d-flex">
                      <div class="flex-shrink-0">
                        <img src="{{ asset('template/admin/dist/assets/images/user/avatar-2.jpg') }}" alt="user-image" class="user-avtar">
                      </div>
                      <div class="flex-grow-1 ms-1">
                        <span class="float-end text-muted">6:00 PM</span>
                        <p class="text-body mb-1"><b>Mitigation Bot</b> block list synced.</p>
                        <span class="text-muted">5 August</span>
                      </div>
                    </div>
                  </a>
                </div>
              </div>
              <div class="dropdown-divider"></div>
              <div class="text-center py-2">
                <a href="{{ route('admin.alert') }}" class="link-primary">View all</a>
              </div>
            </div>
          </li>
          <li class="dropdown pc-h-item header-user-profile">
            <a class="pc-head-link dropdown-toggle arrow-none me-0" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false" data-bs-auto-close="outside" aria-expanded="false">
              <img src="{{ asset('template/admin/dist/assets/images/user/avatar-2.jpg') }}" alt="user-image" class="user-avtar">
              <span>Admin</span>
            </a>
            <div class="dropdown-menu dropdown-user-profile dropdown-menu-end pc-h-dropdown">
              <div class="dropdown-header">
                <div class="d-flex mb-1">
                  <div class="flex-shrink-0">
                    <img src="{{ asset('template/admin/dist/assets/images/user/avatar-2.jpg') }}" alt="user-image" class="user-avtar wid-35">
                  </div>
                  <div class="flex-grow-1 ms-3">
                    <h6 class="mb-1">Admin</h6>
                    <span>DDoS Sentinel Operator</span>
                  </div>
                </div>
              </div>
              <a href="{{ route('admin.dashboard') }}" class="dropdown-item"><i class="ti ti-dashboard"></i><span>Dashboard</span></a>
              <a href="{{ route('admin.analysis') }}" class="dropdown-item"><i class="ti ti-chart-line"></i><span>Analysis</span></a>
              <a href="{{ route('admin.settings') }}" class="dropdown-item"><i class="ti ti-settings"></i><span>Settings</span></a>
              <a href="{{ url('/#login') }}" class="dropdown-item"><i class="ti ti-power"></i><span>Logout</span></a>
            </div>
          </li>
        </ul>
      </div>
    </div>
  </header>

  <main class="pc-container">
    <div class="pc-content">
      <div class="page-header">
        <div class="page-block">
          <div class="row align-items-center">
            <div class="col-md-12">
              <div class="page-header-title">
                <h5 class="m-b-10">@yield('page-title', 'Dashboard')</h5>
              </div>
              <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item" aria-current="page">@yield('page-title', 'Dashboard')</li>
              </ul>
            </div>
          </div>
        </div>
      </div>

      @yield('content')
    </div>
  </main>

  <script src="{{ asset('template/admin/dist/assets/js/plugins/popper.min.js') }}"></script>
  <script src="{{ asset('template/admin/dist/assets/js/plugins/simplebar.min.js') }}"></script>
  <script src="{{ asset('template/admin/dist/assets/js/plugins/bootstrap.min.js') }}"></script>
  <script src="{{ asset('template/admin/dist/assets/js/fonts/custom-font.js') }}"></script>
  <script src="{{ asset('template/admin/dist/assets/js/pcoded.js') }}"></script>
  <script src="{{ asset('template/admin/dist/assets/js/plugins/feather.min.js') }}"></script>
  <script src="{{ asset('template/admin/dist/assets/js/plugins/apexcharts.min.js') }}"></script>
  <script>
    if (window.feather) {
      feather.replace();
    }
  </script>
  @stack('scripts')
</body>
</html>
