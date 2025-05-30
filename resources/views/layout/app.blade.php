<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') | {{ env('APP_NAME') }}</title>
    <meta name="keywords" content="@yield('keywords')">
    <meta name="description" content="@yield('description')">
    @livewireStyles
    <link rel="stylesheet" href="{{ asset('assets/css/styles.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/portfolio.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/rent.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/modal.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/up.css') }}">
    <link rel="icon" href="{{ asset('assets/image/icon.svg') }}" type="image/svg+xml">
    <script src="https://unpkg.com/imask"></script>
</head>
<body>
    @include('partials.header')
    <main>
        <div class="upward" onclick="scrollTopTop()">
            <img class="upward__img" src="{{ asset('assets/image/charup.svg') }}" alt="">
        </div>
        @livewire('rent-modal')
        @yield('content')
    </main>
    @include('partials.footer')
    @livewireScripts
    <script src="{{ asset('assets/js/script.js') }}"></script>
</body>
</html>
