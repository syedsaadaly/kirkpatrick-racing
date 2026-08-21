@include('admin.layouts.partials.header')
@include('account.layouts.partials.sidebar')

<div class="content">

    <nav class="navbar navbar-light navbar-glass navbar-top navbar-expand">
        <button class="btn navbar-toggler-humburger-icon navbar-toggler me-1 me-sm-3" type="button"
            data-bs-toggle="collapse" data-bs-target="#navbarVerticalCollapse"
            aria-controls="navbarVerticalCollapse" aria-expanded="false" aria-label="Toggle Navigation">
            <span class="navbar-toggle-icon"><span class="toggle-line"></span></span>
        </button>
        <div class="navbar-nav align-items-center ms-auto">
            <div class="nav-item">
                <a class="nav-link" href="{{ url('/') }}" title="Back to site">
                    <i class="fas fa-globe me-1" style="font-size: 1.5rem;"></i>
                </a>
            </div>
            <div class="nav-item">
                <span class="navbar-text text-600 small me-3">
                    @php $accountAvatar = auth()->user()->getFirstMediaUrl('profile'); @endphp
                    @if ($accountAvatar)
                        <img src="{{ $accountAvatar }}" class="rounded-circle me-1" style="width:36px; height:36px; object-fit:cover;" alt="">
                    @else
                        <i class="fas fa-user-circle me-1" style="font-size: 1.5rem;"></i>
                    @endif
                </span>
            </div>
            
        </div>
    </nav>

    @yield('content')

    <footer class="footer">
        <div class="row g-0 justify-content-between fs-10 mt-4 mb-3">
            <div class="col-12 col-sm-auto text-center">
                <p class="mb-0 text-600">All Rights Reserved &copy; {{ date('Y') }}</p>
            </div>
        </div>
    </footer>
</div>

@include('admin.layouts.partials.footer')
