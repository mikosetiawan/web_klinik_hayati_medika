<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

<aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3 " id="sidenav-main">
  <div class="sidenav-header">
    <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
    <a class="navbar-brand m-0" href="{{ route('dashboard') }}" target="_blank">
      <img src="{{ asset('assets/img/logo_klinik.png') }}" class="navbar-brand-img h-100" alt="main_logo">
      <span class="ms-1 font-weight-bold">Klinik Hayati Medika</span>
    </a>
  </div>
  <hr class="horizontal dark mt-0">
  <div class="collapse navbar-collapse w-auto " id="sidenav-collapse-main">
    <ul class="navbar-nav">
      <!-- Dashboard (accessible to all roles) -->
      <li class="nav-item">
        <a class="nav-link {{ Request::routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
          <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
            <i class="bi bi-house-fill text-dark"></i>
          </div>
          <span class="nav-link-text ms-1">Dashboard</span>
        </a>
      </li>

      <!-- Pendaftaran & Pembayaran (accessible to admin and pasien) -->
      @if (Auth::check() && in_array(Auth::user()->role, ['admin', 'pasien']))
      <li class="nav-item">
        <a class="nav-link {{ Request::routeIs('pendaftaran-pasien.*') ? 'active' : '' }}" href="{{ route('pendaftaran-pasien.index') }}">
          <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
            <i class="bi bi-clipboard-plus-fill text-dark"></i>
          </div>
          <span class="nav-link-text ms-1">Pendaftaran & Pembayaran</span>
        </a>
      </li>
      @endif

      <!-- Pendaftaran & Pembayaran (accessible to admin and pasien) -->
      @if (Auth::check() && in_array(Auth::user()->role, ['admin', 'dokter']))
      <li class="nav-item">
        <a class="nav-link {{ Request::routeIs('hasil-diagnosa.*') ? 'active' : '' }}" href="{{ route('hasil-diagnosa.index') }}">
          <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
            <i class="bi bi-clipboard-plus-fill text-dark"></i>
          </div>
          <span class="nav-link-text ms-1">Form Diagnosa</span>
        </a>
      </li>
      @endif

      <!-- Kelola Dokter (accessible to admin only) -->
      @if (Auth::check() && Auth::user()->role === 'admin')
      <li class="nav-item">
        <a class="nav-link {{ Request::routeIs('kelola-dokter.*') ? 'active' : '' }}" href="{{ route('kelola-dokter.index') }}">
          <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
            <i class="bi bi-person-badge-fill text-dark"></i>
          </div>
          <span class="nav-link-text ms-1">Kelola Dokter</span>
        </a>
      </li>
      @endif


      <!-- Kelola Obat (accessible to admin only) -->
      @if (Auth::check() && Auth::user()->role === 'admin')
      <li class="nav-item">
        <a class="nav-link {{ Request::routeIs('kelola-obat.*') ? 'active' : '' }}" href="{{ route('kelola-obat.index') }}">
          <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
            <i class="bi bi-capsule text-dark"></i>
          </div>
          <span class="nav-link-text ms-1">Kelola Obat</span>
        </a>
      </li>
      @endif

      <!-- Kelola Diagnosa (accessible to admin and dokter) -->
      @if (Auth::check() && in_array(Auth::user()->role, ['admin']))
      <li class="nav-item">
        <a class="nav-link {{ Request::routeIs('kelola-diagnosa.*') ? 'active' : '' }}" href="{{ route('kelola-diagnosa.index') }}">
          <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
            <i class="bi bi-clipboard-pulse text-dark"></i>
          </div>
          <span class="nav-link-text ms-1">Kelola Diagnosa</span>
        </a>
      </li>
      @endif

      <!-- Kelola Users (accessible to admin only) -->
      @if (Auth::check() && Auth::user()->role === 'admin')
      <li class="nav-item">
        <a class="nav-link {{ Request::routeIs('kelola-users.*') ? 'active' : '' }}" href="{{ route('kelola-users.index') }}">
          <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
            <i class="bi bi-people-fill text-dark"></i>
          </div>
          <span class="nav-link-text ms-1">Kelola Users</span>
        </a>
      </li>
      @endif

      <!-- Report (accessible to admin only) -->
      @if (Auth::check() && Auth::user()->role === 'admin')
      <li class="nav-item">
        <a class="nav-link {{ Request::routeIs('report.*') ? 'active' : '' }}" href="{{ route('report.index') }}">
          <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
            <i class="bi bi-file-earmark-bar-graph-fill text-dark"></i>
          </div>
          <span class="nav-link-text ms-1">Report</span>
        </a>
      </li>
      @endif


      <!-- Report (accessible to admin only) -->
      @if (Auth::check() && Auth::user()->role === 'admin')
      <li class="nav-item">
        <a class="nav-link {{ Request::routeIs('medical-records.*') ? 'active' : '' }}" href="{{ route('report.medical-records') }}">
          <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
            <i class="bi bi-file-earmark-bar-graph-fill text-dark"></i>
          </div>
          <span class="nav-link-text ms-1">Report Rekam Medis</span> 
        </a>
      </li>
      @endif

      <!-- Setting Section -->
      <li class="nav-item mt-3">
        <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Setting</h6>
      </li>

      <!-- My Profile (accessible to all roles) -->
      <li class="nav-item">
        <a class="nav-link {{ Request::routeIs('profile.edit') ? 'active' : '' }}" href="{{ route('profile.edit') }}">
          <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
            <i class="bi bi-person-circle text-dark"></i>
          </div>
          <span class="nav-link-text ms-1">My Profile</span>
        </a>
      </li>

      <!-- Log Out (accessible to all roles) -->
      <li class="nav-item">
        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();">
            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
              <i class="bi bi-box-arrow-right text-dark"></i>
            </div>
            <span class="nav-link-text ms-1">{{ __('Log Out') }}</span>
          </a>
        </form>
      </li>
    </ul>
  </div>
  <div class="sidenav-footer mx-3 ">
    <div class="card card-background shadow-none card-background-mask-secondary" id="sidenavCard">
      <div class="full-background" style="background-image: url('{{ asset('assets/img/curved-images/white-curved.jpg') }}');"></div>
      <div class="card-body text-start p-3 w-100">
        <div class="icon icon-shape icon-sm bg-white shadow text-center mb-3 d-flex align-items-center justify-content-center border-radius-md">
          <i class="ni ni-diamond text-dark text-gradient text-lg top-0" aria-hidden="true" id="sidenavCardIcon"></i>
        </div>
        <div class="docs-info">
          <h6 class="text-white up mb-0">Need help?</h6>
          <p class="text-xs font-weight-bold">Please check our docs</p>
          <a href="#" target="_blank" class="btn btn-white btn-sm w-100 mb-0">Whatsapp</a>
        </div>
      </div>
    </div>
  </div>
</aside>