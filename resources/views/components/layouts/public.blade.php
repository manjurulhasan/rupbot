<!doctype html>

<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge"/>
    <title>{{ config('app.name', 'RUPBOT') }} | @yield('page-title')</title>
    <link rel="shortcut icon" href="{{asset('assets/img/logo.png')}}">
    <!-- CSS files -->
    <link href="{{ asset('assets/css/tabler.min.css') }}" rel="stylesheet"/>
    <link href="{{ asset('assets/css/tabler-vendors.min.css') }}" rel="stylesheet"/>
    @livewireStyles
    @stack('header')
</head>
<body data-bs-theme="dark">
<div class="page page-center">
    <div class="container container-tight py-4">
        @yield('content')
    </div>
</div>
<script src="{{ asset('assets/js/jquery.min.js') }}"></script>
<script src="{{ asset('assets/js/tabler.min.js') }}" defer></script>
@livewireScripts
@stack('footer')
</body>
</html>
