@extends('front.include.app')
@section('title','Kirkpatrick Racing')
@section('bodyClass','inner-body')
@section('content')




    <section class="inner-banner">
        <div class="container">
            <div class="row">
                <div class="col-md-7">
                    <div class="inner-content italic-content">
                        <h3>PRO-GRADE PERFORMANCE</h3>
                        <h2>THE FUTURE OF<span>PURE POWER</span></h2>
                        <p>Engineered for the dirt. Built for the podium. Explore the
                            world's most advanced electric motorsports arsenal.</p>
                        <div class="btn-group">
                            <a href="#shop-page" class="themeBtn">SHOP ALL CATALOG</a>
                            <a href="{{ route('front.contact') }}" class="themeBtn">BUILD YOUR RIG</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <section id="shop-page-categories" class="shop-page-category-section">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="shop-page-category-header gs-reveal">
                        <h2 class="shop-page-category-title">BROWSE BY CATEGORY</h2>
                        <div class="shop-page-category-line"></div>
                    </div>
                </div>
            </div>
            <div class="row px-2">
                @php $categoryFallbackImages = ['front/images/brow1.png', 'front/images/brow2.png', 'front/images/brow3.png', 'front/images/brow4.png']; @endphp
                @foreach ($categories as $index => $category)
                    <div class="col-12 col-sm-6 col-lg-3 px-1 mb-3 mb-lg-0">
                        <a href="{{ route('front.shop', array_merge(request()->except('page'), ['category' => $category->slug])) }}"
                            class="shop-page-category-card gs-reveal-up delay-{{ $index }}">
                            @php $categoryImage = $category->getFirstMediaUrl('categories'); @endphp
                            <img src="{{ $categoryImage ?: asset($categoryFallbackImages[$index % 4]) }}" alt="{{ $category->name }}" class="card-bg-img">
                            <div class="card-gradient-overlay"></div>
                            <div class="card-content">
                                <h3 class="card-title">{{ $category->name }}</h3>
                                <span class="card-subtitle">VIEW {{ $category->products_count }} MODELS</span>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </section>


    <section id="shop-page" class="shop-page-wrapper">
        <div class="container-fluid slectric-bike-container">
            <div class="row">
                <!-- Sidebar Filters -->
                <div class="col-lg-3 col-xl-2 slectric-bike-sidebar">
                    <div class="slectric-bike-filter-header">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2">
                            <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon>
                        </svg>
                        PERFORMANCE FILTER
                    </div>

                    <div class="slectric-bike-filter-group">
                        <h4 class="slectric-bike-filter-title">CATEGORY</h4>
                        <a href="{{ route('front.shop', request()->except('category', 'page')) }}"
                            class="slectric-bike-checkbox-container">
                            <input type="checkbox" {{ request('category') ? '' : 'checked' }} disabled>
                            <span class="slectric-bike-checkmark"></span>
                            All Units
                        </a>
                        @foreach ($categories as $category)
                            <a href="{{ route('front.shop', array_merge(request()->except('page'), ['category' => $category->slug])) }}"
                                class="slectric-bike-checkbox-container">
                                <input type="checkbox" {{ request('category') === $category->slug ? 'checked' : '' }} disabled>
                                <span class="slectric-bike-checkmark"></span>
                                {{ $category->name }}
                            </a>
                            @if ($category->children->isNotEmpty())
                                <div class="slectric-bike-subcategory-list" style="padding-left: 20px;">
                                    @foreach ($category->children as $child)
                                        <a href="{{ route('front.shop', array_merge(request()->except('page'), ['category' => $child->slug])) }}"
                                            class="slectric-bike-checkbox-container">
                                            <input type="checkbox" {{ request('category') === $child->slug ? 'checked' : '' }} disabled>
                                            <span class="slectric-bike-checkmark"></span>
                                            {{ $child->name }}
                                        </a>
                                    @endforeach
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>

                <!-- Main Content -->
                <div class="col-lg-9 col-xl-10 slectric-bike-main-content">

                    @if ($selectedCategory)
                        <div class="slectric-bike-category-breadcrumb mb-3">
                            @if ($selectedCategory->parent)
                                <a href="{{ route('front.shop', array_merge(request()->except('page'), ['category' => $selectedCategory->parent->slug])) }}">{{ $selectedCategory->parent->name }}</a>
                                <span class="mx-1">/</span>
                                <strong>{{ $selectedCategory->name }}</strong>
                            @else
                                <strong>{{ $selectedCategory->name }}</strong>
                            @endif
                        </div>
                    @endif

                    <!-- Top Bar -->
                    <div class="d-flex justify-content-between align-items-center slectric-bike-topbar">
                        <div class="slectric-bike-results-count">DISPLAYING <span id="displayCount">{{ $products->count() }}</span> OF <span
                                id="totalCount">{{ $products->total() }}</span> HIGH-PERFORMANCE ITEMS</div>
                        <div class="slectric-bike-sort-dropdown dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                SORT BY: <span>
                                    @switch(request('sort'))
                                        @case('price_asc')
                                            PRICE (LOW-HIGH)
                                            @break
                                        @case('price_desc')
                                            PRICE (HIGH-LOW)
                                            @break
                                        @default
                                            NEWEST
                                    @endswitch
                                    <svg width="10" height="10" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <polyline points="6 9 12 15 18 9"></polyline>
                                    </svg>
                                </span>
                            </a>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="{{ route('front.shop', array_merge(request()->except(['sort', 'page']), ['sort' => 'newest'])) }}">Newest</a>
                                <a class="dropdown-item" href="{{ route('front.shop', array_merge(request()->except(['sort', 'page']), ['sort' => 'price_asc'])) }}">Price (Low-High)</a>
                                <a class="dropdown-item" href="{{ route('front.shop', array_merge(request()->except(['sort', 'page']), ['sort' => 'price_desc'])) }}">Price (High-Low)</a>
                            </div>
                        </div>
                    </div>

                    <!-- Product Grid - 3 Products per Row (Bootstrap 4) -->
                    <div class="row slectric-bike-grid" id="productGrid">
                        @forelse ($products as $product)
                            @php
                                $colorOptions = $product->variations
                                    ->flatMap->variationOptions
                                    ->filter(fn ($opt) => optional($opt->attribute)->name === 'Color')
                                    ->unique('id');
                            @endphp
                            <div class="col-sm-6 col-md-4 col-lg-4 mb-4 slectric-bike-grid-item product-item">
                                <div class="slectric-bike-card">
                                    <div class="slectric-bike-card-image-wrap">
                                        <a href="{{ route('front.product-details', $product->slug) }}" class="bikes_box">
                                            @if ($product->categories->contains(fn ($c) => str_contains(strtolower($c->name), 'electric')))
                                                <span class="slectric-bike-tag slectric-bike-tag-red">Electric</span>
                                            @endif
                                            <img src="{{ $product->getFirstMediaUrl('products') ?: asset('front/images/logo.png') }}" alt="{{ $product->name }}">
                                        </a>
                                    </div>
                                    <div class="slectric-bike-card-body">
                                        <h3 class="slectric-bike-card-title">{{ $product->name }}</h3>
                                        <p class="slectric-bike-card-subtitle">{{ $product->slug }}</p>

                                        @if ($colorOptions->isNotEmpty())
                                            <div class="slectric-bike-color-wrap">
                                                <p class="slectric-bike-color-label">Color</p>
                                                <div class="slectric-bike-color-options">
                                                    @foreach ($colorOptions as $option)
                                                        <button class="color-btn" style="background: {{ $option->color ?: explode(' ', trim((string) $option->value))[0] }};" type="button" title="{{ $option->value }}"></button>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif

                                        @if ($product->wheelbase || $product->range)
                                            <div class="slectric-bike-specs-grid">
                                                @if ($product->wheelbase)
                                                    <div class="slectric-bike-spec-box">
                                                        <div class="slectric-bike-spec-label">WHEELBASE</div>
                                                        <div class="slectric-bike-spec-value">{{ $product->wheelbase }}</div>
                                                    </div>
                                                @endif
                                                @if ($product->range)
                                                    <div class="slectric-bike-spec-box">
                                                        <div class="slectric-bike-spec-label">RANGE</div>
                                                        <div class="slectric-bike-spec-value">{{ $product->range }}</div>
                                                    </div>
                                                @endif
                                            </div>
                                        @endif
                                        @php
                                            $displayPrice = $product->variations->isNotEmpty()
                                                ? $product->variations->map(fn ($v) => $v->sale_price ?? $v->price)->min()
                                                : ($product->sale_price ?? $product->base_price);
                                        @endphp
                                        <div class="slectric-bike-card-footer">
                                            <div class="slectric-bike-price">${{ number_format((float) ($displayPrice ?? 0), 2) }}</div>
                                            <form action="{{ route('cart.add') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                                <input type="hidden" name="qty" value="1">
                                                <button class="slectric-bike-cart-btn" type="submit">
                                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                        stroke-linejoin="round">
                                                        <circle cx="9" cy="21" r="1"></circle>
                                                        <circle cx="20" cy="21" r="1"></circle>
                                                        <path
                                                            d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6">
                                                        </path>
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12 text-center py-5">
                                <p class="mb-0">No items match your filters right now.</p>
                            </div>
                        @endforelse
                    </div>
                    @if ($products->hasPages())
                        <div class="slectric-bike-pagination-wrap mt-4">
                            <div class="slectric-bike-pagination">
                                <a href="{{ $products->previousPageUrl() ?? '#' }}"
                                    class="slectric-bike-page-item prev-page {{ $products->onFirstPage() ? 'disabled' : '' }}">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <polyline points="15 18 9 12 15 6"></polyline>
                                    </svg>
                                </a>
                                @for ($page = 1; $page <= $products->lastPage(); $page++)
                                    <a href="{{ $products->url($page) }}"
                                        class="slectric-bike-page-item {{ $products->currentPage() === $page ? 'active' : '' }}">{{ $page }}</a>
                                @endfor
                                <a href="{{ $products->nextPageUrl() ?? '#' }}"
                                    class="slectric-bike-page-item next-page {{ $products->hasMorePages() ? '' : 'disabled' }}">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <polyline points="9 18 15 12 9 6"></polyline>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <section id="electric-bike-podium-banner" class="position-relative overflow-hidden speed-back">
        <div class="bg-image"></div>
        <div class="container position-relative z-index-1">
            <div class="row justify-content-center">
                <div class="col-12 col-md-10 col-lg-8 text-center">
                    <h2 class="podium-title text-uppercase">The Podium Awaits</h2>
                    <p class="podium-subtitle">
                        Don't settle for standard. Our 2025 racing<br class="d-none d-md-block">
                        fleet is now available for early reservation.
                    </p>
                    <a href="{{ route('front.contact') }}" class="btn-secure">Secure your slot now</a>
                </div>
            </div>
        </div>
    </section>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                var wrapper = document.querySelector('.slectric-bike-sort-dropdown');
                if (!wrapper) return;

                var toggle = wrapper.querySelector('.dropdown-toggle');
                var menu = wrapper.querySelector('.dropdown-menu');

                toggle.addEventListener('click', function (e) {
                    e.preventDefault();
                    e.stopPropagation();
                    menu.classList.toggle('show');
                });

                document.addEventListener('click', function (e) {
                    if (!wrapper.contains(e.target)) {
                        menu.classList.remove('show');
                    }
                });
            });
        </script>
    @endpush

    @endsection