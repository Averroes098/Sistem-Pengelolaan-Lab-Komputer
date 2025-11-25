<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>@yield('title') - Peminjaman Laboratorium</title>

    <!-- Google Fonts: Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- plugins:css -->
    <link rel="stylesheet" href="{{ asset('vendors/iconfonts/mdi/css/materialdesignicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/iconfonts/ionicons/dist/css/ionicons.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/iconfonts/flag-icon-css/css/flag-icon.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/css/vendor.bundle.base.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/css/vendor.bundle.addons.css') }}">
    <!-- endinject -->

    <!-- inject:css -->
    <link rel="stylesheet" href="{{ asset('css/shared/style.css') }}">
    <!-- endinject -->

    <!-- Layout styles -->
    <link rel="stylesheet" href="{{ asset('css/demo_1/style.css') }}">
    <!-- End Layout styles -->

    <!-- Global Font Override: Poppins -->
    <style>
        * {
            font-family: 'Poppins', sans-serif !important;
        }
    </style>

    <link rel="shortcut icon" href="{{ asset('images/favicon.ico') }}" />
  </head>
  <body>
    <div class="container-scroller">
      @include('partials._navbar')

      <div class="container-fluid page-body-wrapper">
        @include('partials._sidebar')

        <div class="main-panel">
          @yield('content')
          @include('partials._footer')
        </div>

      </div>
    </div>

    <!-- plugins:js -->
    <script src="{{ asset('vendors/js/vendor.bundle.base.js') }}"></script>
    <script src="{{ asset('vendors/js/vendor.bundle.addons.js') }}"></script>
    <!-- endinject -->

    <!-- inject:js -->
    <script src="{{ asset('js/shared/off-canvas.js') }}"></script>
    <script src="{{ asset('js/shared/misc.js') }}"></script>
    <!-- endinject -->

    <!-- Custom js for this page-->
    <script src="{{ asset('js/demo_1/dashboard.js') }}"></script>
    <!-- End custom js for this page-->
    <script src="{{ asset('js/shared/jquery.cookie.js') }}" type="text/javascript"></script>
    @stack('scripts')
  </body>
</html>