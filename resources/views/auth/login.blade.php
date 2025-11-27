<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Login - Aplikasi Peminjaman Laboratorium</title>

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
    <link rel="stylesheet" href="{{ asset('css/demo_1/style.css') }}">
    <link rel="shortcut icon" href="{{ asset('images/favicon.ico') }}" />
  </head>

  <body>
    <div class="container-scroller">
      <div class="container-fluid page-body-wrapper full-page-wrapper">
        <div class="content-wrapper d-flex align-items-center auth register-bg-1 theme-one">
          <div class="row w-100">
            <div class="col-lg-4 mx-auto">
              <h2 class="text-center mb-4">Aplikasi Peminjaman Laboratorium</h2>

              {{-- ✅ Pesan sukses --}}
              @if (session('success'))
                  <div class="alert alert-success">
                      {{ session('success') }}
                  </div>
              @endif

              {{-- ⚠️ Pesan error umum (misal login gagal) --}}
              @if (session('error'))
                  <div class="alert alert-danger">
                      {{ session('error') }}
                  </div>
              @endif

              {{-- ⚠️ Validasi form --}}
              @if ($errors->any())
                  <div class="alert alert-danger">
                      <ul class="mb-0">
                          @foreach ($errors->all() as $error)
                              <li>{{ $error }}</li>
                          @endforeach
                      </ul>
                  </div>
              @endif
              
              <div class="auto-form-wrapper">
                <form action="{{ route('login.authenticate') }}" method="POST" class="forms-sample mb-3">
                  @csrf
                  <h4 class="form-title text-bold text-center mb-4">Sign In</h4>

                  <div class="form-group">
                    <label for="email">Email</label>
                    <input 
                      type="email" 
                      class="form-control" 
                      id="email" 
                      name="email" 
                      value="{{ old('email') }}" 
                      placeholder="Masukkan email" 
                      required>
                  </div>

                  <div class="form-group">
                    <label for="password">Password</label>
                    <input 
                      type="password" 
                      class="form-control" 
                      id="password" 
                      name="password" 
                      placeholder="Masukkan password" 
                      required>
                  </div>

                  <div class="form-group">
                    <div class="form-check">
                      <label class="form-check-label">
                        <input type="checkbox" class="form-check-input" name="remember" id="remember"> Remember me </label>
                    </div>
                  </div>
                  
                  <div class="mt-4 d-flex justify-content-between">
                    <button type="submit" class="btn btn-success">Login</button>
                    <a href="{{ url('/') }}" class="btn btn-light">Cancel</a>
                  </div>
                </form>

                <div class="form-group mt-3 text-center">
                  <p>Belum punya akun? 
                    <a href="{{ route('register.index') }}">Daftar di sini</a>
                  </p>
                </div>
              </div>

            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- plugins:js -->
    <script src="{{ asset('vendors/js/vendor.bundle.base.js') }}"></script>
    <script src="{{ asset('vendors/js/vendor.bundle.addons.js') }}"></script>
    <script src="{{ asset('js/shared/off-canvas.js') }}"></script>
    <script src="{{ asset('js/shared/misc.js') }}"></script>
    <script src="{{ asset('js/shared/jquery.cookie.js') }}" type="text/javascript"></script>
  </body>
</html>
