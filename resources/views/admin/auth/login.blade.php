<!DOCTYPE html>
<html data-bs-theme="light" lang="en-US" dir="ltr">

  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">


    <!-- ===============================================-->
    <!--    Document Title-->
    <!-- ===============================================-->
    <title>{{ config('app.name') }}</title>

    <!-- ===============================================-->
    <!--    Favicons-->
    <!-- ===============================================-->
    @php $favicon = \App\Models\Setting::where('key', 'favicon')->first()?->getFirstMediaUrl('logos'); @endphp
    @if ($favicon)
        <link rel="icon" href="{{ $favicon }}">
        <link rel="shortcut icon" href="{{ $favicon }}">
    @else
        <link rel="apple-touch-icon" sizes="180x180" href="{{asset('admin/assets/img/favicons/apple-touch-icon.png')}}">
        <link rel="icon" type="image/png" sizes="32x32" href="{{asset('admin/assets/img/favicons/favicon-32x32.png')}}">
        <link rel="icon" type="image/png" sizes="16x16" href="{{asset('admin/assets/img/favicons/favicon-16x16.png')}}">
        <link rel="shortcut icon" type="image/x-icon" href="{{asset('admin/assets/img/favicons/favicon.ico')}}">
    @endif
    <link rel="manifest" href="{{asset('admin/assets/img/favicons/manifest.json')}}">
    <meta name="msapplication-TileImage" content="{{asset('assets/img/favicons/mstile-150x150.png')}}">
    <meta name="theme-color" content="#ffffff">
    <script src="{{asset('admin/assets/js/config.js')}}"></script>
    <script src="{{ asset('admin/vendors/simplebar/simplebar.min.js')}}"></script>


    <!-- ===============================================-->
    <!--    Stylesheets-->
    <!-- ===============================================-->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="css?family=Open+Sans:300,400,500,600,700%7cPoppins:300,400,500,600,700,800,900&amp;display=swap" rel="stylesheet">
    <link href="{{ asset('admin/vendors/simplebar/simplebar.min.css')}}" rel="stylesheet">
    <link href="{{asset('admin/assets/css/theme-rtl.css')}}" rel="stylesheet" id="style-rtl">
    <link href="{{asset('admin/assets/css/theme.css')}}" rel="stylesheet" id="style-default">
    <link href="{{asset('admin/assets/css/user-rtl.css')}}" rel="stylesheet" id="user-style-rtl">
    <link href="{{asset('admin/assets/css/user.css')}}" rel="stylesheet" id="user-style-default">
    <script>
      var isRTL = JSON.parse(localStorage.getItem('isRTL'));
      if (isRTL) {
        var linkDefault = document.getElementById('style-default');
        var userLinkDefault = document.getElementById('user-style-default');
        linkDefault.setAttribute('disabled', true);
        userLinkDefault.setAttribute('disabled', true);
        document.querySelector('html').setAttribute('dir', 'rtl');
      } else {
        var linkRTL = document.getElementById('style-rtl');
        var userLinkRTL = document.getElementById('user-style-rtl');
        linkRTL.setAttribute('disabled', true);
        userLinkRTL.setAttribute('disabled', true);
      }
    </script>
  </head>


  <body>

    <!-- ===============================================-->
    <!--    Main Content-->
    <!-- ===============================================-->
    <main class="main" id="top">
      <div class="container-fluid">
        <script>
          var isFluid = JSON.parse(localStorage.getItem('isFluid'));
          if (isFluid) {
            var container = document.querySelector('[data-layout]');
            container.classList.remove('container');
            container.classList.add('container-fluid');
          }
        </script>

        @php
          $loginTitle = \App\Models\Setting::where('key','login_title')->first();

          $loginImage = \App\Models\Setting::where('key','login_image')->first();


          if($loginImage && $loginImage->getFirstMediaUrl('logos')){
            $loginBgUrl = $loginImage->getFirstMediaUrl('logos');
          } else {
            $loginBgUrl = asset('admin/assets/img/generic/14.jpg');
          }
        @endphp
        <div class="row min-vh-100 bg-100">
          <div class="col-6 d-none d-lg-block position-relative">
            <div class="bg-holder" style="background-image: url('{{ $loginBgUrl }}'); background-position: 50% 20%;">
            </div>

          </div>
          <div class="col-sm-10 col-md-6 px-sm-0 align-self-center mx-auto py-5">
            <div class="row justify-content-center g-0">
              <div class="col-lg-9 col-xl-8 col-xxl-6">
                <div class="card">
                  <div class="card-header bg-circle-shape bg-shape text-center p-2"><a class="font-sans-serif fw-bolder fs-5 z-1 position-relative link-light" href="#" data-bs-theme="light">{{ $loginTitle->value??env('APP_NAME')}}</a></div>
                  <div class="card-body p-4">
                    <div class="row flex-between-center">
                      <div class="col-auto">
                        <h3>Login</h3>
                      </div>
                      <div class="col-auto fs-10 text-600">
                        </div>
                    </div>
                    <form method="POST" action="{{route('admin.login')}}">
                            @csrf
                      <div class="mb-3">
                        <label class="form-label" for="split-login-email">Email address</label>
                        <input class="form-control" id="split-login-email" name="email" type="email">
                        <small class="text-danger">@error ('email') {{$message}} @enderror</small>
                      </div>
                      <div class="mb-3">
                        <div class="d-flex justify-content-between">
                          <label class="form-label" for="split-login-password" >Password</label>
                        </div>
                        <input class="form-control" id="split-login-password" name="password" type="password">
                        <small class="text-danger">@error ('password') {{$message}} @enderror</small>
                      </div>
                      <div class="row flex-between-center">
                        <div class="col-auto">
                          <div class="form-check mb-0">
                            <input class="form-check-input" type="checkbox" id="split-checkbox">
                            <label class="form-check-label mb-0" for="split-checkbox">Remember me</label>
                          </div>
                        </div>
                        
                      </div>
                      <div class="mb-3">
                        <button class="btn btn-primary d-block w-100 mt-3" type="submit" name="submit">Log in</button>
                      </div>
                    </form>
                   
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>
    <!-- ===============================================-->
    <!--    End of Main Content-->
    <!-- ===============================================-->


  

    <!-- ===============================================-->
    <!--    JavaScripts-->
    <!-- ===============================================-->
    <script src="{{ asset('admin/vendors/popper/popper.min.js')}}"></script>
    <script src="{{ asset('admin/vendors/bootstrap/bootstrap.min.js')}}"></script>
    <script src="{{ asset('admin/vendors/anchorjs/anchor.min.js')}}"></script>
    <script src="{{ asset('admin/vendors/is/is.min.js')}}"></script>
    <script src="{{ asset('admin/vendors/fontawesome/all.min.js')}}"></script>
    <script src="{{ asset('admin/vendors/lodash/lodash.min.js')}}"></script>
    <script src=""></script>
    <script src="{{ asset('admin/vendors/list.js/list.min.js')}}"></script>
    <script src="{{asset('admin/assets/js/theme.js')}}"></script>
@if (session()->has('success'))
<div class="position-fixed top-0 end-0 p-3" style="z-index: 5">
  <div class="toast fade" id="liveToast" role="alert" aria-live="assertive" aria-atomic="true">
    <div class="toast-header bg-success text-white">
      <strong class="me-auto">Success</strong>
      <small>Just now</small>
      <div data-bs-theme="dark">
        <button class="btn-close" type="button" data-bs-dismiss="toast" aria-label="Close"></button>
      </div>
    </div>
    <div class="toast-body">{{ session('success') }}</div>
  </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    var toastEl = document.getElementById('liveToast');
    var toast = new bootstrap.Toast(toastEl);
    toast.show(); // ✅ automatically show toast
});
</script>
@endif
@if (session()->has('error'))
<div style="position: fixed; top: 1rem; right: 1rem; z-index: 2000;">
  <div class="toast fade" id="liveToast" role="alert" aria-live="assertive" aria-atomic="true">
    <div class="toast-header bg-danger text-white">
      <strong class="me-auto">Success</strong>
      <small>Just now</small>
      <div data-bs-theme="dark">
        <button class="btn-close" type="button" data-bs-dismiss="toast" aria-label="Close"></button>
      </div>
    </div>
    <div class="toast-body">{{ session('error') }}</div>
  </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    var toastEl = document.getElementById('liveToast');
    var toast = new bootstrap.Toast(toastEl);
    toast.show(); // ✅ automatically show toast
});
</script>
@endif
  </body>

</html>