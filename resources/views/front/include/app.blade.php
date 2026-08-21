<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <link rel="stylesheet" href="{{ asset('front/css/all.min.css') }}" />

    <link rel="stylesheet" href="{{ asset('front/css/custom.min.css') }}" />

    <link rel="stylesheet" href="{{ asset('front/css/responsive.css') }}" />

    @php $favicon = \App\Models\Setting::where('key', 'favicon')->first()?->getFirstMediaUrl('logos'); @endphp
    @if ($favicon)
        <link rel="icon" href="{{ $favicon }}">
        <link rel="shortcut icon" href="{{ $favicon }}">
    @else
        <link rel="icon" href="{{ asset('favicon.ico') }}">
    @endif

    <title>@yield('title', 'Kirkpatrick Racing')</title>

</head>

<body class="@yield('bodyClass')">
    <div class="mouse-cursor cursor-outer"></div>
    <div class="mouse-cursor cursor-inner"></div>

    @include('front.include.header')

    @if (session('success') || session('error'))
        <div class="container mt-3">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
        </div>
    @endif

    @yield('content')

    @include('front.include.footer')

    <script src="{{ asset('front/js/all.min.js') }}"></script>

    <script src="{{ asset('front/js/custom.min.js') }}"></script>

    @stack('scripts')

</body>

</html>
