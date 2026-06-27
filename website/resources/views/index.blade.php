<!DOCTYPE html>
<html class="no-js" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="x-ua-compatible" content="ie=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>DDoS Sentinel | Detection &amp; Mitigation</title>
    <meta
      name="description"
      content="DDoS Sentinel detects, analyzes, and mitigates Distributed Denial of Service attacks using router logs and flow-based traffic analysis."
    />
    <link
      rel="shortcut icon"
      type="image/x-icon"
      href="{{ asset('template/landing_page/assets/img/favicon.png') }}"
    />
    <link
      rel="stylesheet"
      href="{{ asset('template/landing_page/assets/css/bootstrap.min.css') }}"
    />
    <link
      rel="stylesheet"
      href="{{ asset('template/landing_page/assets/css/lineicons.css') }}"
    />
    <link
      rel="stylesheet"
      href="{{ asset('template/landing_page/assets/css/animate.css') }}"
    />
    <link
      rel="stylesheet"
      href="{{ asset('template/landing_page/assets/css/main.css') }}"
    />
    <style>
      body {
        font-size: 16px;
        line-height: 1.6;
        color: #4b5563;
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
      }
      h1, h2, h3, h4, h5, h6 {
        font-weight: 700;
        color: #162447;
        line-height: 1.3;
      }
      h1 { font-size: 42px; }
      h2 { font-size: 30px; }
      h3 { font-size: 20px; }
      p { font-size: 16px; color: #6b7280; }

      .hero-content h1 {
        font-size: 44px;
        line-height: 1.2;
        margin-bottom: 20px;
      }
      .hero-content p {
        font-size: 18px;
        line-height: 1.7;
        margin-bottom: 28px;
        color: #6b7280;
      }
      .section-title h2 {
        font-size: 32px;
        margin-bottom: 16px;
      }
      .section-title p {
        font-size: 17px;
        color: #6b7280;
        max-width: 640px;
        margin: 0 auto;
      }
      .single-feature h3 {
        font-size: 18px;
        margin-bottom: 10px;
      }
      .single-feature p {
        font-size: 15px;
        line-height: 1.6;
        color: #6b7280;
      }
      .about-content p {
        font-size: 16px;
        line-height: 1.7;
      }
      .about-content ul li {
        font-size: 15px;
        padding: 6px 0;
      }
      .footer-widget h3 {
        font-size: 18px;
        color: #fff;
        margin-bottom: 20px;
      }
      .footer-widget .links li a {
        font-size: 14px;
        color: rgba(255,255,255,0.7);
      }
      .footer-widget p.desc {
        font-size: 15px;
        color: rgba(255,255,255,0.7);
        line-height: 1.7;
      }
      .navbar-brand span {
        font-size: 18px;
      }
      .navbar-nav .nav-item a {
        font-size: 14px;
        font-weight: 500;
      }
      .login-nav-btn {
        font-size: 14px;
        padding: 8px 22px;
      }
      .main-btn {
        font-size: 15px;
        padding: 12px 32px;
        font-weight: 600;
        border-radius: 999px;
      }
      .border-btn {
        font-size: 15px;
        padding: 12px 32px;
      }
      .login-panel label {
        font-size: 14px;
      }
      .login-panel input {
        font-size: 15px;
        height: 48px;
      }
      .login-submit-btn {
        font-size: 15px;
        padding: 12px 32px;
      }
      .scroll-bottom {
        font-size: 28px;
      }
      .stat-label {
        font-size: 11px;
        letter-spacing: 0.5px;
      }
      .stat-value {
        font-size: 26px;
      }

      @media (max-width: 768px) {
        h1 { font-size: 32px; }
        h2 { font-size: 26px; }
        h3 { font-size: 18px; }
        .hero-content h1 { font-size: 32px; }
        .hero-content p { font-size: 16px; }
        .section-title h2 { font-size: 26px; }
        .section-title p { font-size: 15px; }
        .navbar-brand span { font-size: 16px; }
        .main-btn { font-size: 14px; padding: 10px 24px; }
        .login-panel input { font-size: 14px; height: 44px; }
        .stat-value { font-size: 22px; }
      }
      @media (max-width: 576px) {
        h1 { font-size: 28px; }
        h2 { font-size: 22px; }
        .hero-content h1 { font-size: 28px; }
        .hero-content p { font-size: 15px; }
        .section-title h2 { font-size: 22px; }
        .single-feature h3 { font-size: 16px; }
        .single-feature p { font-size: 14px; }
        .stat-value { font-size: 20px; }
      }
    </style>
  </head>
  <body>
    <div class="preloader">
      <div class="loader">
        <div class="spinner">
          <div class="spinner-container">
            <div class="spinner-rotator">
              <div class="spinner-left">
                <div class="spinner-circle"></div>
              </div>
              <div class="spinner-right">
                <div class="spinner-circle"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <header class="header">
      <div class="navbar-area">
        <div class="container">
          <div class="row align-items-center">
            <div class="col-lg-12">
              <nav class="navbar navbar-expand-lg">
                <a class="navbar-brand" href="#home">
                  <span style="display:inline-flex;align-items:center;gap:10px;font-weight:700;font-size:18px;color:#162447;">
                    <span style="width:38px;height:38px;border-radius:999px;background:#5864FF;color:#fff;display:inline-flex;align-items:center;justify-content:center;box-shadow:0 8px 20px rgba(88,100,255,.22);">
                      <i class="lni lni-shield" style="font-size:18px;"></i>
                    </span>
                    <span>DDoS Sentinel</span>
                  </span>
                </a>
                <button
                  class="navbar-toggler"
                  type="button"
                  data-bs-toggle="collapse"
                  data-bs-target="#navbarSupportedContent"
                  aria-controls="navbarSupportedContent"
                  aria-expanded="false"
                  aria-label="Toggle navigation"
                >
                  <span class="toggler-icon"></span>
                  <span class="toggler-icon"></span>
                  <span class="toggler-icon"></span>
                </button>
                <div
                  class="collapse navbar-collapse sub-menu-bar"
                  id="navbarSupportedContent"
                >
                  <ul id="nav" class="navbar-nav ms-auto">
                    <li class="nav-item">
                      <a class="page-scroll active" href="#home">Home</a>
                    </li>
                    <li class="nav-item">
                      <a class="page-scroll" href="#features">Features</a>
                    </li>
                    <li class="nav-item">
                      <a class="page-scroll" href="#about">About</a>
                    </li>
                    <li class="nav-item">
                      <a class="page-scroll" href="#why">How It Works</a>
                    </li>
                    <li class="nav-item">
                      <a class="page-scroll" href="#contact">Contact</a>
                    </li>
                    <li class="nav-item login-nav-item">
                      <a
                        class="page-scroll login-nav-btn"
                        href="#login"
                      >
                        Log In
                      </a>
                    </li>
                  </ul>
                </div>
              </nav>
            </div>
          </div>
        </div>
      </div>
    </header>

    <section id="home" class="hero-section">
      <div class="container">
        <div class="row align-items-center position-relative">
          <div class="col-lg-6">
            <div class="hero-content">
              <h1 class="wow fadeInUp" data-wow-delay=".4s">
                Real-Time DDoS Detection and Mitigation
              </h1>
              <p class="wow fadeInUp" data-wow-delay=".6s">
                DDoS Sentinel monitors router logs and flow records in real
                time, identifies suspicious traffic patterns with machine
                learning, and triggers mitigation before services are
                overwhelmed.
              </p>
              <a
                href="#about"
                class="main-btn border-btn btn-hover wow fadeInUp"
                data-wow-delay=".7s"
              >
                Learn More
              </a>
              <a href="#features" class="scroll-bottom">
                <i class="lni lni-arrow-down"></i>
              </a>
            </div>
          </div>
          <div class="col-lg-6">
            <div class="hero-img wow fadeInUp" data-wow-delay=".5s">
              <img
                src="{{ asset('template/landing_page/assets/img/hero/hero-img.png') }}"
                alt="Network security illustration"
              />
            </div>
          </div>
        </div>
      </div>
    </section>

    <section id="features" class="feature-section pt-120">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-12">
            <div class="section-title text-center mb-60">
              <h2 class="mb-20 wow fadeInUp" data-wow-delay=".2s">
                Detection Layers
              </h2>
              <p class="wow fadeInUp" data-wow-delay=".4s">
                The system uses multiple signals to recognize attacks faster
                and reduce false alarms.
              </p>
            </div>
          </div>
          <div class="col-lg-4 col-md-8 col-sm-10">
            <div class="single-feature">
              <div class="icon">
                <i class="lni lni-network"></i>
              </div>
              <div class="content">
                <h3>Flow-Based Analysis</h3>
                <p>
                  Analyzes summarized traffic flows to detect abnormal spikes,
                  protocol misuse, and early signs of volumetric attacks.
                </p>
              </div>
            </div>
          </div>
          <div class="col-lg-4 col-md-8 col-sm-10">
            <div class="single-feature">
              <div class="icon">
                <i class="lni lni-clipboard"></i>
              </div>
              <div class="content">
                <h3>Router Log Intelligence</h3>
                <p>
                  Reads router and device logs to surface overload warnings,
                  packet drops, and other contextual signs of network stress.
                </p>
              </div>
            </div>
          </div>
          <div class="col-lg-4 col-md-8 col-sm-10">
            <div class="single-feature">
              <div class="icon">
                <i class="lni lni-shield"></i>
              </div>
              <div class="content">
                <h3>Adaptive Response</h3>
                <p>
                  Triggers rate limiting, blacklisting, and ACL updates when an
                  attack is confirmed.
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section id="about" class="about-section pt-150">
      <div class="container">
        <div class="row align-items-center">
          <div class="col-xl-6 col-lg-6">
            <div class="about-img">
              <img
                src="{{ asset('template/landing_page/assets/img/about/about-1.png') }}"
                alt="About illustration"
                class="w-100"
              />
              <img
                src="{{ asset('template/landing_page/assets/img/about/about-left-shape.svg') }}"
                alt=""
                class="shape shape-1"
              />
              <img
                src="{{ asset('template/landing_page/assets/img/about/left-dots.svg') }}"
                alt=""
                class="shape shape-2"
              />
            </div>
          </div>
          <div class="col-xl-6 col-lg-6">
            <div class="about-content">
              <div class="section-title mb-30">
                <h2 class="mb-25 wow fadeInUp" data-wow-delay=".2s">
                  About DDoS Sentinel
                </h2>
                <p class="wow fadeInUp" data-wow-delay=".4s">
                  DDoS Sentinel is an intelligent defense platform designed to
                  detect and mitigate Distributed Denial of Service attacks by
                  combining router log analysis with flow-based traffic
                  analysis. It uses machine learning to recognize suspicious
                  behavior in real time, then applies automated response
                  actions to protect legitimate users.
                </p>
              </div>
              <ul>
                <li>Real-time detection from logs and traffic flows</li>
                <li>Machine learning-based anomaly classification</li>
                <li>Automatic mitigation for faster incident response</li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section id="why" class="about-section pt-150">
      <div class="container">
        <div class="row align-items-center">
          <div class="col-xl-6 col-lg-6 order-lg-2">
            <div class="about-img-2">
              <img
                src="{{ asset('template/landing_page/assets/img/about/about-2.png') }}"
                alt="Workflow illustration"
                class="w-100"
              />
              <img
                src="{{ asset('template/landing_page/assets/img/about/about-right-shape.svg') }}"
                alt=""
                class="shape shape-1"
              />
              <img
                src="{{ asset('template/landing_page/assets/img/about/right-dots.svg') }}"
                alt=""
                class="shape shape-2"
              />
            </div>
          </div>
          <div class="col-xl-6 col-lg-6 order-lg-1">
            <div class="about-content">
              <div class="section-title mb-30">
                <h2 class="mb-25 wow fadeInUp" data-wow-delay=".2s">
                  Detection Workflow
                </h2>
                <p class="wow fadeInUp" data-wow-delay=".4s">
                  The workflow follows a monitor, detect, and respond cycle.
                  Data is collected from routers and traffic flows, evaluated
                  by trained models, and then translated into immediate
                  mitigation actions when abnormal activity appears.
                </p>
              </div>
              <ul>
                <li>Collect router logs and flow-based traffic records</li>
                <li>Analyze patterns with trained ML models</li>
                <li>Apply mitigation actions when attack behavior is confirmed</li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section id="contact" class="about-section pt-150">
      <div class="container">
        <div class="row align-items-center">
          <div class="col-xl-6 col-lg-6">
            <div class="about-img">
              <img
                src="{{ asset('template/landing_page/assets/img/about/about-1.png') }}"
                alt="Author illustration"
                class="w-100"
              />
              <img
                src="{{ asset('template/landing_page/assets/img/about/about-left-shape.svg') }}"
                alt=""
                class="shape shape-1"
              />
              <img
                src="{{ asset('template/landing_page/assets/img/about/left-dots.svg') }}"
                alt=""
                class="shape shape-2"
              />
            </div>
          </div>
          <div class="col-xl-6 col-lg-6">
            <div class="about-content">
              <div class="section-title mb-30">
                <h2 class="mb-25 wow fadeInUp" data-wow-delay=".2s">
                  Author Information
                </h2>
                <p class="wow fadeInUp" data-wow-delay=".4s">
                  This page is tied to the paper authored by Keano Nikko L. Sy.
                  It reflects the research focus, contact details, and the
                  system title used in the proposal.
                </p>
              </div>
              <ul>
                <li>Keano Nikko L. Sy</li>
                <li>State University of Northern Negros</li>
                <li>keanosy0319@gmail.com</li>
              </ul>
              <a
                href="mailto:keanosy0319@gmail.com"
                class="main-btn border-btn btn-hover wow fadeInUp"
                data-wow-delay=".6s"
              >
                Email the Author
              </a>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section id="login" class="about-section login-section pt-120">
      <div class="container">
        <div class="row align-items-center">
          <div class="col-xl-6 col-lg-6 order-lg-1">
            <div class="about-content">
              <div class="section-title mb-30">
                <h2 class="mb-25 wow fadeInUp" data-wow-delay=".2s">
                  Admin Login
                </h2>
                <p class="wow fadeInUp" data-wow-delay=".4s">
                  Sign in to access the dashboard, review detection alerts, and
                  manage the analysis and mitigation sections of the system.
                </p>
              </div>
              <div class="login-panel" style="background:rgba(88,100,255,.08);border:1px solid rgba(88,100,255,.14);border-radius:28px;padding:24px;box-shadow:0 18px 40px rgba(88,100,255,.08);">
                <form action="{{ route('admin.dashboard') }}" method="GET">
                  <div style="display:grid;gap:16px;">
                    <div>
                      <label for="login-email" class="mb-2" style="font-weight:600;color:#162447;font-size:14px;">Email Address</label>
                      <input
                        id="login-email"
                        type="email"
                        name="email"
                        placeholder="admin@example.com"
                        class="form-control"
                        style="height:48px;border-radius:999px;border:1px solid rgba(88,100,255,.18);padding:0 18px;background:#fff;color:#162447;font-size:15px;"
                      />
                    </div>
                    <div>
                      <label for="login-password" class="mb-2" style="font-weight:600;color:#162447;font-size:14px;">Password</label>
                      <input
                        id="login-password"
                        type="password"
                        name="password"
                        placeholder="Enter your password"
                        class="form-control"
                        style="height:48px;border-radius:999px;border:1px solid rgba(88,100,255,.18);padding:0 18px;background:#fff;color:#162447;font-size:15px;"
                      />
                    </div>
                    <button type="submit" class="main-btn login-submit-btn" style="width:100%;border-radius:999px;padding:12px 32px;font-size:15px;font-weight:600;background:#5864FF;color:#fff;border:none;cursor:pointer;transition:all 0.3s;">
                      Log In
                    </button>
                  </div>
                </form>
              </div>
            </div>
          </div>
          <div class="col-xl-6 col-lg-6 order-lg-2">
            <div class="about-img-2">
              <img
                src="{{ asset('template/landing_page/assets/img/about/about-2.png') }}"
                alt="Login illustration"
                class="w-100"
              />
              <img
                src="{{ asset('template/landing_page/assets/img/about/about-right-shape.svg') }}"
                alt=""
                class="shape shape-1"
              />
              <img
                src="{{ asset('template/landing_page/assets/img/about/right-dots.svg') }}"
                alt=""
                class="shape shape-2"
              />
            </div>
          </div>
        </div>
      </div>
    </section>

    <footer class="footer">
      <div class="container">
        <div class="widget-wrapper">
          <div class="row">
            <div class="col-xl-4 col-lg-4 col-md-6">
              <div class="footer-widget">
                <div class="logo mb-30">
                  <a href="#home">
                    <span style="display:inline-flex;align-items:center;gap:10px;font-weight:700;font-size:18px;color:#fff;">
                      <span style="width:38px;height:38px;border-radius:999px;background:#fff;color:#5864FF;display:inline-flex;align-items:center;justify-content:center;">
                        <i class="lni lni-shield" style="font-size:18px;"></i>
                      </span>
                      <span>DDoS Sentinel</span>
                    </span>
                  </a>
                </div>
                <p class="desc mb-30 text-white" style="font-size:15px;color:rgba(255,255,255,0.7);line-height:1.7;">
                  Intelligent DDoS detection and mitigation using router logs,
                  flow analysis, and adaptive response logic.
                </p>
              </div>
            </div>
            <div class="col-xl-2 col-lg-2 col-md-6">
              <div class="footer-widget">
                <h3>Sections</h3>
                <ul class="links">
                  <li><a href="#home">Home</a></li>
                  <li><a href="#features">Features</a></li>
                  <li><a href="#about">About</a></li>
                  <li><a href="#why">How It Works</a></li>
                  <li><a href="#login">Log In</a></li>
                </ul>
              </div>
            </div>
            <div class="col-xl-3 col-lg-3 col-md-6">
              <div class="footer-widget">
                <h3>Focus</h3>
                <ul class="links">
                  <li><a href="#features">Detection</a></li>
                  <li><a href="#features">Mitigation</a></li>
                  <li><a href="#about">Router Logs</a></li>
                  <li><a href="#about">Flow Analysis</a></li>
                </ul>
              </div>
            </div>
            <div class="col-xl-3 col-lg-3 col-md-6">
              <div class="footer-widget">
                <h3>Project</h3>
                <ul class="links">
                  <li><a href="#contact">Landing Page</a></li>
                  <li><a href="#contact">Frontend First</a></li>
                  <li><a href="#contact">Prototype Mode</a></li>
                  <li><a href="#contact">Laravel Blade</a></li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>
    </footer>

    <a href="#" class="scroll-top btn-hover">
      <i class="lni lni-chevron-up"></i>
    </a>

    <script src="{{ asset('template/landing_page/assets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('template/landing_page/assets/js/wow.min.js') }}"></script>
    <script src="{{ asset('template/landing_page/assets/js/main.js') }}"></script>
  </body>
</html>