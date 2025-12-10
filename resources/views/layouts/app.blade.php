<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Laravel App') }}</title>

    {{-- Tabler CSS --}}
    <link href="{{ asset('assets/css/tabler.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/tabler-vendors.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/demo.min.css') }}" rel="stylesheet" />

    {{-- Tambahan CSS (opsional) --}}
    @stack('styles')
</head>

<body>

    {{-- Content --}}
    @yield('content')

    {{-- JS Core --}}
    <script src="{{ asset('assets/js/tabler.min.js') }}"></script>
    <script src="{{ asset('assets/js/demo.min.js') }}"></script>

    {{-- Extra Script (optional) --}}
    @stack('scripts')

</body>

</html>
