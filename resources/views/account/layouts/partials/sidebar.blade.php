<nav class="navbar navbar-light navbar-vertical navbar-expand-xl navbar-vibrant">
    <script>
        var navbarStyle = localStorage.getItem("navbarStyle");
        if (navbarStyle && navbarStyle !== 'transparent') {
            document.querySelector('.navbar-vertical').classList.add(`navbar-${navbarStyle}`);
        }
    </script>
    <div class="d-flex align-items-center">
        <div class="toggle-icon-wrapper">
            <button class="btn navbar-toggler-humburger-icon navbar-vertical-toggle"
                data-bs-toggle="tooltip" data-bs-placement="left" title="Toggle Navigation">
                <span class="navbar-toggle-icon"><span class="toggle-line"></span></span>
            </button>
        </div>
        <a class="navbar-brand" href="{{ url('/') }}">
            <div class="d-flex align-items-center py-3">
                <img class="me-2" src="{{ asset('front/images/logo.png') }}" alt="" width="40">
                <span class="font-sans-serif">My Account</span>
            </div>
        </a>
    </div>
    <div class="collapse navbar-collapse" id="navbarVerticalCollapse">
        <div class="navbar-vertical-content scrollbar"
            style="background-color: {{ \App\Models\Setting::getValue('sidebar_bg_color', '#2c7be5') }} !important;">
            <ul class="navbar-nav flex-column mb-3" id="navbarVerticalNav">

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('account.dashboard') ? 'active' : '' }}"
                        href="{{ route('account.dashboard') }}">
                        <div class="d-flex align-items-center">
                            <span class="nav-link-icon"><span class="fas fa-chart-pie"></span></span>
                            <span class="nav-link-text ps-1">Dashboard</span>
                        </div>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('account.profile*') ? 'active' : '' }}"
                        href="{{ route('account.profile') }}">
                        <div class="d-flex align-items-center">
                            <span class="nav-link-icon"><span class="fas fa-user"></span></span>
                            <span class="nav-link-text ps-1">My Profile</span>
                        </div>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('account.orders*') ? 'active' : '' }}"
                        href="{{ route('account.orders') }}">
                        <div class="d-flex align-items-center">
                            <span class="nav-link-icon"><span class="fas fa-shopping-bag"></span></span>
                            <span class="nav-link-text ps-1">My Orders</span>
                        </div>
                    </a>
                </li>

              

                <li class="nav-item">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="nav-link w-100 text-start border-0 bg-transparent">
                            <div class="d-flex align-items-center">
                                <span class="nav-link-icon"><span class="fas fa-sign-out-alt"></span></span>
                                <span class="nav-link-text ps-1">Logout</span>
                            </div>
                        </button>
                    </form>
                </li>

            </ul>
        </div>
    </div>
</nav>
