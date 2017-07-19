<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @if( Auth::check() )
    <meta name="token" content="{{ auth()->user()->api_token }}">
    @endif

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app">
        @include('partials.nav')

        @yield('content')
        @if( Auth::check() )
        <new-build></new-build>
        <new-exemption-from-match></new-exemption-from-match>
        <reset-password></reset-password>
        <client-password-reset-request></client-password-reset-request>
        <flash message="{{ session('flash') }}"></flash>
        @endif
    </div>


    <!-- Scripts -->
    <script src="http://{{ config('app.echo_host') }}/socket.io/socket.io.js"></script>
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
