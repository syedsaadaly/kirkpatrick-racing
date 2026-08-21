@extends('front.include.app')
@section('title', 'Kirkpatrick Racing | ' . $product->name)
@section('bodyClass','inner-body bg-black-color')
@section('content')

@php
    $galleryMedia = $product->getMedia('products');
    $attributes = $product->variations->flatMap->variationOptions
        ->map(fn ($option) => $option->attribute)
        ->filter()
        ->unique('id')
        ->values();
    $isElectricProduct = $product->categories->contains(fn ($c) => str_contains(strtolower($c->name), 'electric'));
@endphp

    <section>
        <div class="detail-page-wrapper">

            <!-- Back Button -->
            <a href="{{ route('front.shop') }}" class="back-button">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                    stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="15 18 9 12 15 6"></polyline>
                </svg>
                Back to Shop
            </a>

            <!-- Detail Card -->
            <div class="detail-card">

                <!-- Left Column - Image -->
                <div class="detail-card-image-wrap">
                    <div class="swiper detail-slide">
                        <div class="swiper-wrapper">
                            @forelse ($galleryMedia as $media)
                                <div class="swiper-slide">
                                    <figure class="bikes_box">
                                        @if ($isElectricProduct)
                                            <span class="slectric-bike-tag">Electric</span>
                                        @endif
                                        <img src="{{ $media->getUrl() }}" alt="{{ $product->name }}" id="detailBikeImage-{{ $loop->index }}" />
                                    </figure>
                                </div>
                            @empty
                                <div class="swiper-slide">
                                    <figure class="bikes_box">
                                        @if ($isElectricProduct)
                                            <span class="slectric-bike-tag">Electric</span>
                                        @endif
                                        <img src="{{ asset('front/images/logo.png') }}" alt="{{ $product->name }}" />
                                    </figure>
                                </div>
                            @endforelse
                        </div>
                        <div class="swiper-button-next"></div>
                        <div class="swiper-button-prev"></div>
                        <div class="swiper-pagination"></div>
                    </div>
                </div>

                <!-- Right Column - Content -->
                <div class="detail-card-body">
                    <h2 class="detail-card-title">
                        {{ $product->name }}
                    </h2>
                    <p class="detail-card-subtitle">{{ $product->slug }}</p>

                    <div class="detail-card-description" id="productDescription">
                        {!! $product->description ?: $product->short_description !!}
                    </div>
                    <button type="button" class="detail-read-more-btn d-none" id="readMoreBtn">Read More</button>

                    <!-- Variation Selection -->
                    @if ($attributes->isNotEmpty())
                        <div class="detail-color-section" id="variation-container">
                            @foreach ($attributes as $index => $attribute)
                                <div class="mb-3 variation-group" data-index="{{ $index }}">
                                    <div class="detail-color-label">
                                        <span>{{ $attribute->name }}</span>
                                        @if (strtolower($attribute->name) === 'color')
                                            <span class="detail-color-name detail-selected-color-name" id="selectedColorName"></span>
                                        @endif
                                    </div>
                                    <div class="detail-color-options attribute-options" data-attribute-id="{{ $attribute->id }}">
                                        @if ($index === 0)
                                            @foreach ($product->variations->flatMap->variationOptions->where('variation_id', $attribute->id)->unique('id') as $option)
                                                <div class="variation-item d-inline-block">
                                                    <input type="radio" name="attr_{{ $attribute->id }}"
                                                        id="opt_{{ $option->id }}" value="{{ $option->id }}"
                                                        class="btn-check d-none variation-selector" data-index="{{ $index }}"
                                                        data-value="{{ $option->value }}"
                                                        {{ $loop->first ? 'checked' : '' }}>
                                                    @if (strtolower($attribute->name) === 'color')
                                                        <label for="opt_{{ $option->id }}" class="detail-color-btn"
                                                            style="background: {{ $option->color ?: explode(' ', trim((string) $option->value))[0] }};" title="{{ $option->value }}"></label>
                                                    @else
                                                        <label for="opt_{{ $option->id }}" class="detail-color-btn detail-option-btn" title="{{ $option->value }}">{{ $option->pivot->label ?: $option->value }}</label>
                                                    @endif
                                                </div>
                                            @endforeach
                                        @else
                                            <p class="text-muted small mb-0">Select {{ $attributes[$index - 1]->name }} first</p>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <!-- Specs -->
                    @if ($product->wheelbase || $product->range || $product->top_speed || $product->power)
                        <div class="detail-specs-grid">
                            @if ($product->wheelbase)
                                <div class="detail-spec-box">
                                    <div class="detail-spec-label">WHEELBASE</div>
                                    <div class="detail-spec-value">{{ $product->wheelbase }}</div>
                                </div>
                            @endif
                            @if ($product->range)
                                <div class="detail-spec-box">
                                    <div class="detail-spec-label">RANGE</div>
                                    <div class="detail-spec-value">{{ $product->range }}</div>
                                </div>
                            @endif
                            @if ($product->top_speed)
                                <div class="detail-spec-box">
                                    <div class="detail-spec-label">TOP SPEED</div>
                                    <div class="detail-spec-value">{{ $product->top_speed }}</div>
                                </div>
                            @endif
                            @if ($product->power)
                                <div class="detail-spec-box">
                                    <div class="detail-spec-label">POWER</div>
                                    <div class="detail-spec-value">{{ $product->power }}</div>
                                </div>
                            @endif
                        </div>
                    @endif

                    <p class="detail-stock-status">
                        <strong>Availability:</strong>
                        <span id="stockLabel">{{ $product->stock_quantity > 0 ? 'In Stock (' . $product->stock_quantity . ')' : 'Out of Stock' }}</span>
                    </p>

                    <!-- Footer -->
                    <form action="{{ route('cart.add') }}" method="POST" class="detail-card-footer">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <input type="hidden" name="product_variation_id" id="selected-variation-id" value="">
                        <input type="hidden" name="qty" id="quantity-input" value="1">
                        @php
                            $displayPrice = $product->variations->isNotEmpty()
                                ? $product->variations->map(fn ($v) => $v->sale_price ?? $v->price)->min()
                                : ($product->sale_price ?? $product->base_price);
                        @endphp
                        <div class="detail-price" id="currentPrice">
                            <span class="currency">$</span>{{ number_format($displayPrice, 2) }}
                            @if ($product->variations->isEmpty() && $product->sale_price && $product->sale_price < $product->base_price)
                                <del class="text-muted ml-2" style="font-size: 1rem;">${{ number_format($product->base_price, 2) }}</del>
                            @endif
                        </div>
                        <button class="detail-cart-btn" id="add-to-cart-btn" type="submit"
                            {{ $attributes->isNotEmpty() || $product->stock_quantity < 1 ? 'disabled' : '' }}>
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="9" cy="21" r="1"></circle>
                                <circle cx="20" cy="21" r="1"></circle>
                                <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                            </svg>
                            <span>Add to Cart</span>
                        </button>
                    </form>

                </div>
            </div>
        </div>
    </section>

    @push('scripts')
    <script>
        $(document).ready(function () {
            const $desc = $('#productDescription');
            const $readMoreBtn = $('#readMoreBtn');

            function checkDescriptionOverflow() {
                if (!$desc.length || $desc.hasClass('user-expanded')) return;
                $desc.addClass('is-collapsed');
                if ($desc[0].scrollHeight > $desc[0].clientHeight + 10) {
                    $readMoreBtn.removeClass('d-none');
                } else {
                    $desc.removeClass('is-collapsed');
                    $readMoreBtn.addClass('d-none');
                }
            }
            window.checkDescriptionOverflow = checkDescriptionOverflow;

            checkDescriptionOverflow();

            if (document.fonts && document.fonts.ready) {
                document.fonts.ready.then(checkDescriptionOverflow);
            }
            $(window).on('load', checkDescriptionOverflow);
            setTimeout(checkDescriptionOverflow, 500);

            $readMoreBtn.on('click', function () {
                const collapsed = $desc.toggleClass('is-collapsed').hasClass('is-collapsed');
                $desc.toggleClass('user-expanded', !collapsed);
                $(this).text(collapsed ? 'Read More' : 'Read Less');
            });
        });
    </script>
    @endpush

    @if ($attributes->isNotEmpty())
        <style>
            .variation-selector:checked + .detail-color-btn {
                outline: 3px solid #d81111;
                outline-offset: 2px;
            }
            .detail-cart-btn:disabled {
                opacity: 0.4;
                cursor: not-allowed;
            }
        </style>
        @push('scripts')
        <script>
            $(document).ready(function () {
                const variations = @json($product->variations->load('variationOptions'));
                const baseDescription = @js($product->description ?: $product->short_description ?: '');

                function setDescription(html) {
                    const $desc = $('#productDescription');
                    $desc.removeClass('user-expanded').html(html);
                    $('#readMoreBtn').text('Read More');
                    if (window.checkDescriptionOverflow) {
                        window.checkDescriptionOverflow();
                    }
                }

                $(document).on('change', '.variation-selector', function () {
                    const $this = $(this);
                    const currentIndex = parseInt($this.data('index'));
                    const nextGroup = $(`.variation-group[data-index="${currentIndex + 1}"]`);

                    const $colorNameEl = $this.closest('.variation-group').find('.detail-selected-color-name');
                    if ($colorNameEl.length) {
                        $colorNameEl.text($this.data('value') || '');
                    }

                    $('.variation-group').each(function () {
                        if (parseInt($(this).data('index')) > currentIndex) {
                            $(this).find('.attribute-options').html('<p class="text-muted small mb-0">Select previous option first</p>');
                        }
                    });

                    let selectedOptionIds = [];
                    $('.variation-selector:checked').each(function () {
                        selectedOptionIds.push(parseInt($(this).val()));
                    });

                    const availableVariations = variations.filter(v => {
                        const vOptionIds = v.variation_options.map(o => o.id);
                        return selectedOptionIds.every(id => vOptionIds.includes(id));
                    });

                    if (nextGroup.length > 0) {
                        const nextAttrContainer = nextGroup.find('.attribute-options');
                        const nextAttrId = nextAttrContainer.data('attribute-id');

                        let validOptions = [];
                        availableVariations.forEach(v => {
                            v.variation_options.forEach(opt => {
                                if (opt.variation_id === nextAttrId) validOptions.push(opt);
                            });
                        });

                        const uniqueOptions = [...new Map(validOptions.map(item => [item.id, item])).values()];

                        let html = '';
                        uniqueOptions.forEach(opt => {
                            html += `<div class="variation-item d-inline-block">
                                <input type="radio" name="attr_${nextAttrId}" id="opt_${opt.id}" value="${opt.id}"
                                    class="btn-check d-none variation-selector" data-index="${currentIndex + 1}" data-value="${opt.value}">
                                <label for="opt_${opt.id}" class="detail-color-btn detail-option-btn" title="${opt.value}">${(opt.pivot && opt.pivot.label) || opt.value}</label>
                            </div>`;
                        });
                        nextAttrContainer.html(html);
                    }

                    if (availableVariations.length === 1 && selectedOptionIds.length === $('.variation-group').length) {
                        const match = availableVariations[0];
                        $('#selected-variation-id').val(match.id);
                        $('#currentPrice').html('<span class="currency">$</span>' + parseFloat(match.sale_price || match.price).toFixed(2));
                        const maxStock = parseInt(match.stock_quantity);
                        $('#quantity-input').val(maxStock > 0 ? 1 : 0);
                        $('#stockLabel').text(maxStock > 0 ? `In Stock (${maxStock})` : 'Out of Stock');

                        $('#add-to-cart-btn').prop('disabled', maxStock <= 0);
                        setDescription(match.description ? match.description : baseDescription);
                    } else {
                        $('#selected-variation-id').val('');
                        $('#add-to-cart-btn').prop('disabled', true);
                        setDescription(baseDescription);
                    }
                });

                $('.variation-selector:checked').trigger('change');
            });
        </script>
        @endpush
    @endif

@endsection
