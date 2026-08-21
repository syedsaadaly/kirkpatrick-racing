<nav class="navbar navbar-light navbar-vertical navbar-expand-xl navbar-vibrant">
    <script>
        var navbarStyle = localStorage.getItem("navbarStyle");
        if (navbarStyle && navbarStyle !== 'transparent') {
            document.querySelector('.navbar-vertical').classList.add(`navbar-${navbarStyle}`);
        }
    </script>
    <div class="d-flex align-items-center">
        <div class="toggle-icon-wrapper">

            <button class="btn navbar-toggler-humburger-icon navbar-vertical-toggle" data-bs-toggle="tooltip"
                data-bs-placement="left" title="Toggle Navigation"><span class="navbar-toggle-icon"><span
                        class="toggle-line"></span></span></button>

        </div>
        <a class="navbar-brand" href="{{ route('admin.dashboard') }}">
            <div class="d-flex align-items-center py-3">@php
                $dashboardLogo = \App\Models\Setting::where('key', 'dashboard_logo')->first();
            @endphp
                @if ($dashboardLogo && $dashboardLogo->getFirstMediaUrl('logos'))
                    <img class="me-2" src="{{ $dashboardLogo->getFirstMediaUrl('logos') }}" alt="Logo"
                        width="40">
                @else
                    <img class="me-2" src="{{ asset('admin/assets/img/icons/spot-illustrations/falcon.png') }}"
                        alt="" width="40">
                @endif
                <span
                    class="font-sans-serif ">{{ \App\Models\Setting::getValue('dashboard_title', 'Dashboard') }}</span>
            </div>
        </a>
    </div>
    <div class="collapse navbar-collapse" id="navbarVerticalCollapse">
        <div class="navbar-vertical-content scrollbar"
            style="background-color: {{ \App\Models\Setting::getValue('sidebar_bg_color', '#2c7be5') }} !important;">
            <ul class="navbar-nav flex-column mb-3" id="navbarVerticalNav">
                {{-- Dashboard --}}
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('admin/dashboard*') ? 'active' : '' }}"
                        href="{{ route('admin.dashboard') }}" role="button">
                        <div class="d-flex align-items-center"><span class="nav-link-icon"><span
                                    class="fas fa-chart-pie"></span></span><span
                                class="nav-link-text ps-1">Dashboard</span>
                        </div>
                    </a>
                </li>

                {{-- Page Builder --}}
                @if (Auth::user()?->hasRole('developer'))
                    <li class="nav-item">
                        <div class="row navbar-vertical-label-wrapper mt-3 mb-2">
                            <div class="col-auto navbar-vertical-label">Page Builder
                            </div>
                            <div class="col ps-0">
                                <hr class="mb-0 navbar-vertical-divider">
                            </div>
                        </div>
                        <!-- parent pages-->
                        <a class="nav-link dropdown-indicator " href="#email" role="button" data-bs-toggle="collapse"
                            aria-expanded="false" aria-controls="email">
                            <div class="d-flex align-items-center"><span class="nav-link-icon"><span
                                        class="fas fa-envelope-open"></span></span><span class="nav-link-text ps-1">Page
                                    Builder</span>
                            </div>
                        </a>
                        <ul class="nav collapse " id="email">
                            <li class="nav-item">
                                <a class="nav-link " href="{{ route('admin.cms-builder.pages.index') }}">
                                    <div class="d-flex align-items-center"><span class="nav-link-text ps-1">Pages</span>
                                    </div>
                                </a>
                            </li>
                            <li class="nav-item"><a class="nav-link" href="app/email/email-detail.html">
                                    <div class="d-flex align-items-center"><span class="nav-link-text ps-1">Email
                                            detail</span>
                                    </div>
                                </a>
                            </li>
                            <li class="nav-item"><a class="nav-link" href="app/email/compose.html">
                                    <div class="d-flex align-items-center"><span
                                            class="nav-link-text ps-1">Compose</span>
                                    </div>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif

                {{-- Website CMS --}}
                <li class="nav-item">
                    <div class="row navbar-vertical-label-wrapper mt-3 mb-2">
                        <div class="col-auto navbar-vertical-label">Website CMS
                        </div>
                        <div class="col ps-0">
                            <hr class="mb-0 navbar-vertical-divider">
                        </div>
                    </div>

                    <a class="nav-link dropdown-indicator
                    {{ request()->is('admin/cms-builder/cms-pages/*/view') ? 'active' : '' }}"
                        href="#cms-builder" role="button" data-bs-toggle="collapse" aria-expanded="false"
                        aria-controls="cms-builder">
                        <div class="d-flex align-items-center">
                            <span class="nav-link-icon"><span class="fas fa-file-alt"></span></span>
                            <span class="nav-link-text ps-1">Website Pages</span>
                        </div>
                    </a>
                    <ul class="nav collapse {{ request()->is('admin/cms-builder/cms-pages/*/view') ? 'show' : '' }}"
                        id="cms-builder">
                        @forelse ($pages as $cmsPage)
                            <li class="nav-item">
                                <a class="nav-link {{ request()->is('admin/cms-builder/cms-pages/' . $cmsPage->id . '/view') ? 'active' : '' }}"
                                    href="{{ route('admin.cms-builder.pages.listable-sections.index', $cmsPage->id) }}">
                                    <div class="d-flex align-items-center">
                                        <span class="nav-link-text ps-1">{{ $cmsPage->name }}</span>
                                    </div>
                                </a>
                            </li>
                        @empty
                            <li class="nav-item">
                                <a class="nav-link {{ request()->is('admin/cms-builder/pages*') && !request()->is('admin/cms-builder/pages/*/view') ? 'active' : '' }}"
                                    href="{{ route('admin.cms-builder.pages.index') }}">
                                    <div class="d-flex align-items-center">
                                        <span class="nav-link-text ps-1">Manage Pages</span>
                                    </div>
                                </a>
                            </li>
                        @endforelse
                    </ul>
                </li>

                {{-- E-Commerce --}}
                <li class="nav-item">
                    <ul class="navbar-nav flex-column mb-3" id="navbarVerticalNav">
                        <li class="nav-item">
                            <div class="row navbar-vertical-label-wrapper mt-3 mb-2">
                                <div class="col-auto navbar-vertical-label">Ecommerce</div>
                                <div class="col ps-0">
                                    <hr class="mb-0 navbar-vertical-divider">
                                </div>
                            </div>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link dropdown-indicator {{ request()->is('admin/categories*') ? 'active' : '' }}"
                                href="#categories" role="button" data-bs-toggle="collapse"
                                aria-expanded="{{ request()->is('admin/categories*') ? 'true' : 'false' }}"
                                aria-controls="categories">
                                <div class="d-flex align-items-center">
                                    <span class="nav-link-icon"><span class="fas fa-list"></span></span>
                                    <span class="nav-link-text ps-1">Categories</span>
                                </div>
                            </a>
                            <ul class="nav collapse {{ request()->is('admin/categories*') ? 'show' : '' }}"
                                id="categories">
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->is('admin/categories/all*') ? 'active' : '' }}"
                                        href="{{ route('admin.categories.index') }}">
                                        <div class="d-flex align-items-center">
                                            <span class="nav-link-text ps-1">All Categories</span>
                                        </div>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->is('admin/categories/create*') ? 'active' : '' }}"
                                        href="{{ route('admin.categories.create') }}">
                                        <div class="d-flex align-items-center">
                                            <span class="nav-link-text ps-1">Create New Category</span>
                                        </div>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link dropdown-indicator {{ request()->is('admin/variations*') ? 'active' : '' }}"
                                href="#variations" role="button" data-bs-toggle="collapse"
                                aria-expanded="{{ request()->is('admin/variations*') ? 'true' : 'false' }}"
                                aria-controls="variations">
                                <div class="d-flex align-items-center">
                                    <span class="nav-link-icon"><span class="fas fa-palette"></span></span>
                                    <span class="nav-link-text ps-1">Variations</span>
                                </div>
                            </a>
                            <ul class="nav collapse {{ request()->is('admin/variations*') ? 'show' : '' }}"
                                id="variations">
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->is('admin/variations') || request()->is('admin/variations/all*') ? 'active' : '' }}"
                                        href="{{ route('admin.variations.index') }}">
                                        <div class="d-flex align-items-center">
                                            <span class="nav-link-text ps-1">All Variations</span>
                                        </div>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->is('admin/variations/create*') ? 'active' : '' }}"
                                        href="{{ route('admin.variations.create') }}">
                                        <div class="d-flex align-items-center">
                                            <span class="nav-link-text ps-1">Create New Variation</span>
                                        </div>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        @if (Auth::user()?->hasRole('developer'))
                        <li class="nav-item">
                            <a class="nav-link dropdown-indicator {{ request()->is('admin/unit*') ? 'active' : '' }}"
                                href="#unit_collapse" role="button" data-bs-toggle="collapse"
                                aria-expanded="{{ request()->is('admin/unit*') ? 'true' : 'false' }}"
                                aria-controls="unit_collapse">
                                <div class="d-flex align-items-center">
                                    <span class="nav-link-icon"><span class="fas fa-balance-scale"></span></span>
                                    <span class="nav-link-text ps-1">Unit</span>
                                </div>
                            </a>
                            <ul class="nav collapse {{ request()->is('admin/unit*') ? 'show' : '' }}"
                                id="unit_collapse">
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->is('admin/unit') || request()->is('admin/unit/all*') ? 'active' : '' }}"
                                        href="{{ route('admin.units.index') }}">
                                        <div class="d-flex align-items-center">
                                            <span class="nav-link-text ps-1">All Units</span>
                                        </div>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->is('admin/unit/create*') ? 'active' : '' }}"
                                        href="{{ route('admin.units.create') }}">
                                        <div class="d-flex align-items-center">
                                            <span class="nav-link-text ps-1">Create New Unit</span>
                                        </div>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link dropdown-indicator {{ request()->is('admin/vendor*') ? 'active' : '' }}"
                                href="#vendor_collapse" role="button" data-bs-toggle="collapse"
                                aria-expanded="{{ request()->is('admin/vendor*') ? 'true' : 'false' }}"
                                aria-controls="vendor_collapse">
                                <div class="d-flex align-items-center">
                                    <span class="nav-link-icon"><span class="fas fa-users"></span></span>
                                    <span class="nav-link-text ps-1">Suppliers</span>
                                </div>
                            </a>
                            <ul class="nav collapse {{ request()->is('admin/vendor*') ? 'show' : '' }}"
                                id="vendor_collapse">
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->is('admin/vendor') || request()->is('admin/vendor/all*') ? 'active' : '' }}"
                                        href="{{ route('admin.suppliers.index') }}">
                                        <div class="d-flex align-items-center">
                                            <span class="nav-link-text ps-1">All Suppliers</span>
                                        </div>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->is('admin/vendor/create*') ? 'active' : '' }}"
                                        href="{{ route('admin.suppliers.create') }}">
                                        <div class="d-flex align-items-center">
                                            <span class="nav-link-text ps-1">Create New Supplier</span>
                                        </div>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        @endif
                        <li class="nav-item">
                            <a class="nav-link dropdown-indicator {{ request()->is('admin/products*') ? 'active' : '' }}"
                                href="#products" role="button" data-bs-toggle="collapse"
                                aria-expanded="{{ request()->is('admin/products*') ? 'true' : 'false' }}"
                                aria-controls="products">
                                <div class="d-flex align-items-center">
                                    <span class="nav-link-icon"><span class="fas fa-box"></span></span>
                                    <span class="nav-link-text ps-1">Products</span>
                                </div>
                            </a>
                            <ul class="nav collapse {{ request()->is('admin/products*') ? 'show' : '' }}"
                                id="products">
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->is('admin/products') || request()->is('admin/products/all*') ? 'active' : '' }}"
                                        href="{{ route('admin.products.index') }}">
                                        <div class="d-flex align-items-center">
                                            <span class="nav-link-text ps-1">All Products</span>
                                        </div>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->is('admin/products/create*') ? 'active' : '' }}"
                                        href="{{ route('admin.products.create') }}">
                                        <div class="d-flex align-items-center">
                                            <span class="nav-link-text ps-1">Create New Product</span>
                                        </div>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        @if (Auth::user()?->hasRole('developer'))
                        <li class="nav-item">
                            <a class="nav-link dropdown-indicator {{ request()->is('admin/purchases*') ? 'active' : '' }}"
                                href="#purchases_collapse" role="button" data-bs-toggle="collapse"
                                aria-expanded="{{ request()->is('admin/purchases*') ? 'true' : 'false' }}"
                                aria-controls="purchases_collapse">
                                <div class="d-flex align-items-center">
                                    <span class="nav-link-icon"><span class="fas fa-shopping-cart"></span></span>
                                    <span class="nav-link-text ps-1">Purchasing</span>
                                </div>
                            </a>
                            <ul class="nav collapse {{ request()->is('admin/purchases*') ? 'show' : '' }}"
                                id="purchases_collapse">
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->is('admin/purchases') ? 'active' : '' }}"
                                        href="{{ route('admin.purchase.index') }}">
                                        <div class="d-flex align-items-center">
                                            <span class="nav-link-text ps-1">All Purchases</span>
                                        </div>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->is('admin/purchases/create*') ? 'active' : '' }}"
                                        href="{{ route('admin.purchase.create') }}">
                                        <div class="d-flex align-items-center">
                                            <span class="nav-link-text ps-1">Create Purchase</span>
                                        </div>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        @endif
                        <li class="nav-item">
                            <a class="nav-link dropdown-indicator {{ request()->is('admin/orders*') ? 'active' : '' }}"
                                href="#orders" role="button" data-bs-toggle="collapse"
                                aria-expanded="{{ request()->is('admin/orders*') ? 'true' : 'false' }}"
                                aria-controls="orders">
                                <div class="d-flex align-items-center">
                                    <span class="nav-link-icon"><span class="fas fa-shopping-cart"></span></span>
                                    <span class="nav-link-text ps-1">Orders</span>
                                </div>
                            </a>
                            <ul class="nav collapse {{ request()->is('admin/orders*') ? 'show' : '' }}"
                                id="orders">
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->is('admin/orders*') ? 'active' : '' }}"
                                        href="{{ route('admin.orders.index') }}">
                                        <div class="d-flex align-items-center">
                                            <span class="nav-link-text ps-1">All Orders</span>
                                        </div>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        @if (Auth::user()?->hasRole('developer'))
                        <li class="nav-item">
                            <a class="nav-link dropdown-indicator {{ request()->is('admin/invoices*') ? 'active' : '' }}"
                                href="#invoice" role="button" data-bs-toggle="collapse"
                                aria-expanded="{{ request()->is('admin/invoices*') ? 'true' : 'false' }}"
                                aria-controls="invoice">
                                <div class="d-flex align-items-center">
                                    <span class="nav-link-icon"><span class="fas fa-file-invoice"></span></span>
                                    <span class="nav-link-text ps-1">Invoice Generation</span>
                                </div>
                            </a>
                            <ul class="nav collapse {{ request()->is('admin/invoices*') ? 'show' : '' }}"
                                id="invoice">
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->is('admin/invoices/generate*') ? 'active' : '' }}"
                                        href="#">
                                        <div class="d-flex align-items-center">
                                            <span class="nav-link-text ps-1">Generate Invoice</span>
                                        </div>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        @endif
                    </ul>
                </li>

                @if ($modules->count() > 0)
                    <li class="nav-item">
                        <div class="row navbar-vertical-label-wrapper mt-3 mb-2">
                            <div class="col-auto navbar-vertical-label">Modules</div>
                            <div class="col ps-0">
                                <hr class="mb-0 navbar-vertical-divider">
                            </div>
                        </div>
                    </li>

                    @foreach ($modules as $module)
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('admin/pages/' . $module->id . '*') ? 'active' : '' }}"
                                href="{{ route('admin.cms-builder.pages.listable-sections.index', $module->id) }}">
                                <div class="d-flex align-items-center">
                                    <span class="nav-link-icon">
                                        <span class="{{ $module->icon ?? 'far fa-file' }}"></span>
                                    </span>
                                    <span class="nav-link-text ps-1">{{ $module->name }}</span>
                                </div>
                            </a>
                        </li>
                    @endforeach
                @endif

                {{-- User Management --}}
                @if (Auth::user()?->hasRole('developer'))
                    <li class="nav-item">
                        <div class="row navbar-vertical-label-wrapper mt-3 mb-2">
                            <div class="col-auto navbar-vertical-label">User Management</div>
                            <div class="col ps-0">
                                <hr class="mb-0 navbar-vertical-divider">
                            </div>
                        </div>

                        <a class="nav-link dropdown-indicator" href="#user-management" role="button"
                            data-bs-toggle="collapse"
                            aria-expanded="{{ request()->is('admin/permissions*') ? 'true' : 'false' }}"
                            aria-controls="user-management">
                            <div class="d-flex align-items-center">
                                <span class="nav-link-icon"><span class="fas fa-users-cog"></span></span>
                                <span class="nav-link-text ps-1">User Management</span>
                            </div>
                        </a>

                        <ul class="nav collapse {{ request()->is('admin/permissions*') ? 'show' : '' }}"
                            id="user-management">
                            <li class="nav-item">
                                <a class="nav-link {{ request()->is('admin/permissions*') ? 'active' : '' }}"
                                    href="{{ route('admin.permissions.index') }}">
                                    <div class="d-flex align-items-center">
                                        <span class="nav-link-text ps-1">Permissions</span>
                                    </div>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->is('admin/roles*') ? 'active' : '' }}"
                                    href="{{ route('admin.roles.index') }}">
                                    <div class="d-flex align-items-center">
                                        <span class="nav-link-text ps-1">Roles</span>
                                    </div>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->is('admin/users*') ? 'active' : '' }}"
                                    href="{{ route('admin.users.index') }}">
                                    <div class="d-flex align-items-center">
                                        <span class="nav-link-text ps-1">Users</span>
                                    </div>
                                </a>
                            </li>
                        </ul>
                    </li>
                @else
                    <li class="nav-item">
                        <div class="row navbar-vertical-label-wrapper mt-3 mb-2">
                            <div class="col-auto navbar-vertical-label">User Management</div>
                            <div class="col ps-0">
                                <hr class="mb-0 navbar-vertical-divider">
                            </div>
                        </div>
                        <a class="nav-link {{ request()->is('admin/users*') ? 'active' : '' }}"
                            href="{{ route('admin.users.index') }}">
                            <div class="d-flex align-items-center">
                                <span class="nav-link-icon"><span class="fas fa-users-cog"></span></span>
                                <span class="nav-link-text ps-1">Users</span>
                            </div>
                        </a>
                    </li>
                @endif

                {{-- Settings --}}
                <li class="nav-item">
                    <div class="row navbar-vertical-label-wrapper mt-3 mb-2">
                        <div class="col-auto navbar-vertical-label">Settings</div>
                        <div class="col ps-0">
                            <hr class="mb-0 navbar-vertical-divider">
                        </div>
                    </div>

                    <a class="nav-link dropdown-indicator" href="#settings-menu" role="button"
                        data-bs-toggle="collapse"
                        aria-expanded="{{ request()->is('admin/settings*') || request()->is('admin/profile-settings*') ? 'true' : 'false' }}"
                        aria-controls="settings-menu">
                        <div class="d-flex align-items-center">
                            <span class="nav-link-icon"><span class="fas fa-cog"></span></span>
                            <span class="nav-link-text ps-1">Settings</span>
                        </div>
                    </a>

                    <ul class="nav collapse {{ request()->is('admin/settings*') || request()->is('admin/profile-settings*') ? 'show' : '' }}"
                        id="settings-menu">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('admin/settings*') ? 'active' : '' }}"
                                href="{{ route('admin.settings.index') }}">
                                <div class="d-flex align-items-center">
                                    <span class="nav-link-text ps-1">Website Settings</span>
                                </div>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('admin/profile-settings*') ? 'active' : '' }}"
                                href="{{ route('admin.settings.profile') }}">
                                <div class="d-flex align-items-center">
                                    <span class="nav-link-text ps-1">Profile Settings</span>
                                </div>
                            </a>
                        </li>
                        {{--                        <li class="nav-item"> --}}
                        {{--                        <a class="nav-link {{ request()->is('admin/data-management*') ? 'active' : '' }}" --}}
                        {{--                           href="{{ route('admin.data-management.index') }}"> --}}
                        {{--                            <div class="d-flex align-items-center"> --}}
                        {{--                                <span class="nav-link-text ps-1">Data Management</span> --}}
                        {{--                            </div> --}}
                        {{--                        </a> --}}
                        {{--                        </li> --}}
                    </ul>
                </li>

                @if (Auth::user()?->hasRole('developer'))
                <li class="nav-item">
                    <div class="row navbar-vertical-label-wrapper mt-3 mb-2">
                        <div class="col-auto navbar-vertical-label">Import &amp; Export</div>
                        <div class="col ps-0">
                            <hr class="mb-0 navbar-vertical-divider">
                        </div>
                    </div>

                    <a class="nav-link dropdown-indicator" href="#export-menu" role="button"
                        data-bs-toggle="collapse"
                        aria-expanded="{{ request()->is('admin/export/logs*') || request()->is('admin/export/logs*') ? 'true' : 'false' }}"
                        aria-controls="export-menu">
                        <div class="d-flex align-items-center">
                            <span class="nav-link-icon"><span class="fas fa-file-export"></span></span>
                            <span class="nav-link-text ps-1">Import & Export</span>
                        </div>
                    </a>

                    <ul class="nav collapse {{ request()->is('admin/export/logs*') || request()->is('admin/export/logs*') ? 'show' : '' }}"
                        id="export-menu">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('admin/export/logs*') ? 'active' : '' }}"
                                href="{{ route('admin.export.logs.index') }}">
                                <div class="d-flex align-items-center">
                                    <span class="nav-link-text ps-1">Export Logs</span>
                                </div>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('admin/export/logs/import-logs') ? 'active' : '' }}"
                                href="{{ route('admin.export.logs.import.index') }}">
                                <div class="d-flex align-items-center">
                                    <span class="nav-link-text ps-1">Import Logs</span>
                                </div>
                            </a>
                        </li>
                    </ul>
                </li>
                @endif
            </ul>
        </div>
    </div>
</nav>

@push('custom-script')
    <script>
        $(document).ready(function() {
            var currentRoute = window.location.href;
            $('.nav-item a').each(function() {
                var linkHref = $(this).attr('href');
                if (currentRoute.indexOf(linkHref) !== -1) {
                    $(this).addClass('active');
                    $(this).closest('ul').addClass('show');
                }
            });
        })
    </script>
@endpush
