<!DOCTYPE html>
<html class="loading" lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-textdirection="ltr">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }} - Checkout</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="shortcut icon" type="image/x-icon" href="/favicon.ico">
    <link rel="stylesheet" href="{{ asset('/css/member/app.css') }}">
    @stack('styles')
</head>

<body>
  <div class="container">
    @include('components.alert')
    @yield('content')
  </div>
  <script src="{{ asset('/js/member/app.js') }}"></script>
  @stack('scripts')
</body>
</html>