<!doctype html>

<html lang="en">
  <head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge"/>
    <title>{{ config('app.name', 'RUPBOT') }} | @yield('page-title')</title>
    <!-- CSS files -->
    <link href="{{ asset('assets/css/tabler.min.css') }}" rel="stylesheet"/>
    <link href="{{ asset('assets/css/tabler-vendors.min.css') }}" rel="stylesheet"/>
    @livewireStyles
     @stack('header')
  </head>
  <body data-bs-theme="dark">
    <div class="page">
      <!-- Sidebar -->
      <x-common.aside />
      <!-- Navbar -->
      <x-common.header />
      <div class="page-wrapper">
        <!-- Page header -->
        <div class="page-header d-print-none">
          <div class="container-xl">
            @yield('top-header')
          </div>
        </div>
        <!-- Page body -->
        <div class="page-body">
          <div class="container-xl">
            @yield('content')
          </div>
        </div>
      </div>
    </div>

    <script src="{{ asset('assets/js/tabler.min.js') }}" defer></script>
    @livewireScripts
    @stack('footer')
  </body>
</html>
