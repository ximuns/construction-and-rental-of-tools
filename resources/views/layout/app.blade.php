<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>СТРОЙЛАЙН</title>
    @livewireStyles
    <link rel="stylesheet" href="{{ asset('assets/css/styles.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/portfolio.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/rent.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/modal.css') }}">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="https://unpkg.com/imask"></script>
</head>
<body>
    @include('partials.header')
    <main>
        @livewire('rent-modal')
        @yield('content')
    </main>
    @include('partials.footer')
    @livewireScripts
    <script src="{{ asset('assets/js/script.js') }}"></script>
    <script>
        AOS.init();
    </script>
</body>
</html>
