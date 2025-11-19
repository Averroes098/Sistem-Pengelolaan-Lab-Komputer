<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>@yield('title') - Staf Laboratory</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

    <!-- CSS Assets -->
    <link rel="stylesheet" href="{{ asset('vendors/iconfonts/mdi/css/materialdesignicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/css/vendor.bundle.base.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/css/vendor.bundle.addons.css') }}">
    <link rel="stylesheet" href="{{ asset('css/shared/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/demo_1/style.css') }}">

    <style>
        * { font-family: 'Poppins', sans-serif !important; }
    </style>

    <link rel="shortcut icon" href="{{ asset('images/favicon.ico') }}" />
</head>

<body>
<div class="container-scroller">

    {{-- NAVBAR UMUM --}}
    @include('partials._navbar')

    <div class="container-fluid page-body-wrapper">

        {{-- SIDEBAR KHUSUS STAF --}}
        @include('partials._sidebar-staf')

        <div class="main-panel">
            @yield('content')

            {{-- FOOTER --}}
            @include('partials._footer')
        </div>

    </div>
</div>

<!-- JS Vendor -->
<script src="{{ asset('vendors/js/vendor.bundle.base.js') }}"></script>
<script src="{{ asset('vendors/js/vendor.bundle.addons.js') }}"></script>

<!-- Theme JS -->
<script src="{{ asset('js/shared/off-canvas.js') }}"></script>
<script src="{{ asset('js/shared/misc.js') }}"></script>
<script src="{{ asset('js/demo_1/dashboard.js') }}"></script>

</body>
</html>
