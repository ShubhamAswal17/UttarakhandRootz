@php
$containerNav = ($configData['contentLayout'] === 'compact') ? 'container-xxl' : 'container-fluid';
$navbarDetached = ($navbarDetached ?? '');
@endphp

@if(isset($navbarDetached) && $navbarDetached == 'navbar-detached')
<nav class="layout-navbar {{$containerNav}} navbar navbar-expand-xl {{$navbarDetached}} align-items-center bg-navbar-theme" id="layout-navbar">
@endif

@if(isset($navbarDetached) && $navbarDetached == '')
<nav class="layout-navbar navbar navbar-expand-xl align-items-center bg-navbar-theme" id="layout-navbar">
  <div class="{{$containerNav}}">
@endif

@if(isset($navbarFull))
<div class="navbar-brand app-brand demo d-none d-xl-flex py-0 me-4">
  <a href="{{ url('/') }}" class="app-brand-link gap-2">

    <span class="app-brand-logo demo">
      @include('_partials.macros',["height"=>20])
    </span>

    <span class="app-brand-text demo menu-text fw-bold">
      {{ config('variables.templateName') }}
    </span>

  </a>
</div>
@endif

@if(!isset($navbarHideToggle))
<div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0
{{ isset($menuHorizontal) ? ' d-xl-none ' : '' }}
{{ isset($contentNavbar) ? ' d-xl-none ' : '' }}">

  <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
    <i class="ti ti-menu-2 ti-sm"></i>
  </a>

</div>
@endif

<div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">

  @if($configData['hasCustomizer'] == true)

  <div class="navbar-nav align-items-center">

    <div class="nav-item dropdown-style-switcher dropdown me-2 me-xl-0">

      <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
        <i class='ti ti-md'></i>
      </a>

      <ul class="dropdown-menu dropdown-menu-start dropdown-styles">

        <li>
          <a class="dropdown-item" href="javascript:void(0);" data-theme="light">
            <span class="align-middle">
              <i class='ti ti-sun me-2'></i>Light
            </span>
          </a>
        </li>

        <li>
          <a class="dropdown-item" href="javascript:void(0);" data-theme="dark">
            <span class="align-middle">
              <i class="ti ti-moon me-2"></i>Dark
            </span>
          </a>
        </li>

        <li>
          <a class="dropdown-item" href="javascript:void(0);" data-theme="system">
            <span class="align-middle">
              <i class="ti ti-device-desktop me-2"></i>System
            </span>
          </a>
        </li>

      </ul>

    </div>

  </div>

  @endif

  <ul class="navbar-nav flex-row align-items-center ms-auto">

    <li class="nav-item navbar-dropdown dropdown-user dropdown">

      <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">

        <div class="avatar avatar-online">

          @if(Auth::check())

          <img src="{{ asset('assets/img/avatars/1.png') }}"
               alt="User"
               class="h-auto rounded-circle">

          @else

          <img src="{{ asset('assets/img/avatars/1.png') }}"
               alt="Guest"
               class="h-auto rounded-circle">

          @endif

        </div>

      </a>

      <ul class="dropdown-menu dropdown-menu-end">

        <li>

          <a class="dropdown-item" href="javascript:void(0);">

            <div class="d-flex">

              <div class="flex-shrink-0 me-3">

                <div class="avatar avatar-online">

                  <img src="{{ asset('assets/img/avatars/1.png') }}"
                       alt="User"
                       class="h-auto rounded-circle">

                </div>

              </div>

              <div class="flex-grow-1">

                <span class="fw-medium d-block">

                  @if(Auth::check())

                    {{ Auth::user()->name }}

                  @else

                    Guest User

                  @endif

                </span>

                <small class="text-muted">

                  @if(Auth::check() && Auth::user()->role == 'admin')

                    Admin

                  @else

                    Employee

                  @endif

                </small>

              </div>

            </div>

          </a>

        </li>

        <li>
          <div class="dropdown-divider"></div>
        </li>
        <li>

          <a class="dropdown-item" href="javascript:void(0);">

            <i class="ti ti-user me-2 ti-sm"></i>

            <span class="align-middle">
             Edit Profile
            
            </span>

          </a>

        </li>
        <li>
          <div class="dropdown-divider"></div>
        </li>
        @if(Auth::check())

        <li>

          <a class="dropdown-item" href="javascript:void(0);">

            <i class="ti ti-user-check me-2 ti-sm"></i>

            <span class="align-middle">
              {{ Auth::user()->email }}
            </span>

          </a>

        </li>
        

        <li>
          <div class="dropdown-divider"></div>
        </li>

        <li>

          <a class="dropdown-item"
             href="{{ route('logout') }}"
             onclick="event.preventDefault();
             document.getElementById('logout-form').submit();">

            <i class='ti ti-logout me-2'></i>

            <span class="align-middle">
              Logout
            </span>

          </a>

        </li>

        <form method="POST"
              id="logout-form"
              action="{{ route('logout') }}">

          @csrf

        </form>

        @else

        <li>

          <a class="dropdown-item"
             href="{{ route('auth-login') }}">

            <i class='ti ti-login me-2'></i>

            <span class="align-middle">
              Login
            </span>

          </a>

        </li>

        @endif

      </ul>

    </li>

  </ul>

</div>

@if(!isset($navbarDetached))
  </div>
@endif

</nav>