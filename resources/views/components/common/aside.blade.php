
 <!-- new aside -->
<aside class="navbar navbar-vertical navbar-expand-lg" data-bs-theme="dark">
    <div class="container-fluid">
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#sidebar-menu" aria-controls="sidebar-menu" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <h1 class="navbar-brand navbar-brand-autodark">
        <a href=".">
          <img src="{{asset('assets/img/logo.png')}}" width="110" height="52" alt="rupbot" class="navbar-brand-image">
        </a>
      </h1>
      <div class="navbar-nav flex-row d-lg-none">
        <div class="nav-item dropdown">
          <a href="#" class="nav-link d-flex lh-1 text-reset p-0" data-bs-toggle="dropdown" aria-label="Open user menu">
            <span class="avatar avatar-sm" style="background-image: url({{asset('assets/img/logo.png')}})"></span>
            <div class="d-none d-xl-block ps-2">
              <div>{{ auth()?->user()?->name }}</div>
              <div class="mt-1 small text-muted">RUPBOT</div>
            </div>
          </a>
          <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
            <a href="#" class="dropdown-item">Profile</a>
            <div class="dropdown-divider"></div>
              <a href="{{ route('logout') }}" class="dropdown-item">Logout</a>
          </div>
        </div>
      </div>
      <div class="collapse navbar-collapse" id="sidebar-menu">
        <ul class="navbar-nav pt-lg-3">
            <li class="nav-item {{ (request()->is('dashboard')) ? 'active' : '' }}">
                <a class="nav-link" href="{{route('dashboard')}}" >
                  <span class="nav-link-icon d-md-none d-lg-inline-block">
                       <i class="ti ti-home-2"></i>
                  </span>
                  <span class="nav-link-title">
                    Dashboard
                  </span>
                </a>
            </li>
            <li class="nav-item {{ (request()->is('sites*')) ? 'active' : '' }}">
                <a class="nav-link" href="{{route('sites')}}" >
                  <span class="nav-link-icon d-md-none d-lg-inline-block">
                    <i class="ti ti-apps"></i>
                  </span>
                    <span class="nav-link-title">
                    Manage Site
                  </span>
                </a>
            </li>
            <li class="nav-item {{ (request()->is('logs*')) ? 'active' : '' }}">
                <a class="nav-link" href="{{route('logs')}}" >
                  <span class="nav-link-icon d-md-none d-lg-inline-block">
                     <i class="ti ti-file"></i>
                  </span>
                    <span class="nav-link-title">
                    Logs
                  </span>
                </a>
            </li>

        </ul>
      </div>
    </div>
  </aside>
