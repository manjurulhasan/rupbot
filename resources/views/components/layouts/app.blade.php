<!doctype html>

<html lang="en">
  <head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge"/>
    <title>{{ config('app.name', 'RUPBOT') }} | @yield('page-title')</title>
    <link rel="shortcut icon" href="{{asset('assets/img/selise_logo.png')}}">
    <!-- CSS files -->
    <link href="{{ asset('assets/css/tabler.min.css') }}" rel="stylesheet"/>
    <link href="{{ asset('assets/css/tabler-vendors.min.css') }}" rel="stylesheet"/>
    <link href="{{ asset('assets/tabler-icon/tabler-icons.min.css') }}" rel="stylesheet"/>
      <link href="{{ asset('assets/flatpickr/flatpickr.min.css') }}" rel="stylesheet" type="text/css" />
      <link href="{{ asset('assets/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
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
          @yield('header')
        <!-- Page body -->
          @yield('content')
      </div>
    </div>
    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/tabler.min.js') }}" defer></script>
    <script src="{{ asset('assets/flatpickr/flatpickr.min.js') }}"></script>
    <script src="{{ asset('assets/sweetalert2/sweetalert2.min.js') }}"></script>
    @livewireScripts
    @stack('footer')
    @include('components.layouts._alert-script')
  </body>
</html>
