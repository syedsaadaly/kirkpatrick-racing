@extends('admin.layouts.app')
@section('content')

@include('admin.layouts.partials.topbar')

<div class="card mb-3">
    <div class="card-body">
        <div class="row flex-between-center">
            <div class="col-md">
                <h5 class="mb-2 mb-md-0">Edit Product</h5>
                <p class="text-muted mb-0">Update product information</p>
            </div>
            <div class="col-auto">
                <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Back to Products
                </a>
            </div>
        </div>
    </div>
</div>

<form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data" id="productForm">
    @csrf
    @method('PUT')

    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h6 class="mb-0">Product Information</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Product Name *</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                   name="name" value="{{ old('name', $product->name) }}" placeholder="Enter product name" required>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">SKU *</label>
                            <input type="text" class="form-control @error('sku') is-invalid @enderror"
                                   name="sku" value="{{ old('sku', $product->sku) }}" placeholder="Product SKU" required>
                            @error('sku')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-bold">Short Description</label>
                            <textarea class="form-control @error('short_description') is-invalid @enderror"
                                      rows="3" name="short_description" placeholder="Brief product description">{{ old('short_description', $product->short_description) }}</textarea>
                            @error('short_description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-bold">Description *</label>
                            <textarea class="form-control summernote @error('description') is-invalid @enderror"
                                      rows="8" name="description" placeholder="Detailed product description" required>{{ old('description', $product->description) }}</textarea>
                            @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h6 class="mb-0">Product Images</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-bold">Main Image</label>

                            @if($product->main_image)
                                <div class="mb-2">
                                    <img src="{{ $product->main_image }}" alt="{{ $product->name }}"
                                         class="img-thumbnail" style="max-height: 150px;">
                                    <div class="form-check mt-2">
                                        <input class="form-check-input" type="checkbox"
                                               name="remove_main_image" value="1" id="remove_main_image">
                                        <label class="form-check-label text-danger" for="remove_main_image">
                                            Remove current image
                                        </label>
                                    </div>
                                </div>
                            @endif

                            <input type="file" class="form-control @error('main_image') is-invalid @enderror"
                                   name="main_image" accept="image/*">
                            <small class="form-text text-muted">Supported formats: JPEG, PNG, GIF, WebP. Maximum file size: 5MB.</small>
                            @error('main_image')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-bold">Gallery Images</label>

                            @if($product->gallery_images->count() > 0)
                                <div class="row mb-3">
                                    @foreach($product->gallery_images as $image)
                                        <div class="col-md-3 mb-2">
                                            <div class="position-relative">
                                                <img src="{{ $image->getUrl() }}" alt="Gallery image"
                                                     class="img-thumbnail" style="max-height: 100px;">
                                                <div class="form-check position-absolute top-0 start-0 mt-1 ms-1">
                                                    <input class="form-check-input" type="checkbox"
                                                           name="remove_gallery_images[]" value="{{ $image->id }}">
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <small class="text-muted d-block mb-2">Check images to remove</small>
                            @endif

                            <input type="file" class="form-control @error('gallery_images') is-invalid @enderror"
                                   name="gallery_images[]" accept="image/*" multiple>
                            <small class="form-text text-muted">You can select multiple images.</small>
                            @error('gallery_images')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>
            </div>
            @php
                $selectedVariationIds = $product->variations
                    ->flatMap(fn($v) => $v->variationOptions->pluck('variation_id'))
                    ->unique()
                    ->toArray();
            @endphp
            @if($product->variations->isNotEmpty())
            <div id="variations-card" class="card mb-4">
                <div class="card-header bg-light">
                    <h6 class="mb-0">Product Variations</h6>
                </div>
                <div class="card-body">
                    <div id="variations-table-container">
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle">
                                <thead class="table-light">
                                    <tr id="variations-thead-row">
                                        @foreach($variations->whereIn('id', $selectedVariationIds) as $v)
                                            <th>{{ $v->name }}</th>
                                        @endforeach
                                        <th>SKU</th>
                                        <th>Price Adjustment</th>
                                        <th>Sale Price</th>
                                        <th>Description</th>
                                        <th>Stock</th>
                                        <th class="text-center">Active</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="variations-tbody">
                                    @foreach($product->variations as $index => $item)
                                        <tr>
                                            <input type="hidden" name="variations[{{ $index }}][id]" value="{{ $item->id }}">
                                            @foreach($item->variationOptions as $opt)
                                                <td>
                                                    <span class="fw-semibold d-block mb-1">{{ $opt->value }}</span>
                                                    <input type="text" class="form-control form-control-sm"
                                                        name="variations[{{ $index }}][option_labels][{{ $opt->id }}]"
                                                        value="{{ $opt->pivot->label }}"
                                                        placeholder="Display label (optional)">
                                                </td>
                                                <input type="hidden" name="variations[{{ $index }}][option_ids][]" value="{{ $opt->id }}">
                                            @endforeach
                                            <td>
                                                <input type="text" class="form-control form-control-sm"
                                                    name="variations[{{ $index }}][sku]" value="{{ $item->sku }}">
                                            </td>
                                            <td>
                                                <input type="number" class="form-control form-control-sm"
                                                    name="variations[{{ $index }}][price]" value="{{ $item->price }}" step="0.01">
                                            </td>
                                            <td>
                                                <input type="number" class="form-control form-control-sm"
                                                    name="variations[{{ $index }}][sale_price]" value="{{ $item->sale_price }}" step="0.01">
                                            </td>
                                            <td style="min-width: 180px;">
                                                <textarea class="form-control form-control-sm"
                                                    name="variations[{{ $index }}][description]" rows="2"
                                                    placeholder="Optional - overrides main description">{{ $item->description }}</textarea>
                                            </td>
                                            <td>
                                                <input type="number" class="form-control form-control-sm"
                                                    name="variations[{{ $index }}][stock_quantity]" value="{{ $item->stock_quantity }}" required>
                                            </td>
                                            <td class="text-center">
                                                <div class="form-check form-switch d-flex justify-content-center">
                                                    <input class="form-check-input" type="checkbox" 
                                                        name="variations[{{ $index }}][is_active]" value="1" {{ $item->is_active ? 'checked' : '' }}>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <button type="button" class="btn btn-danger btn-sm remove-variation-row" data-id="{{ $item->id }}">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            @else
                <div id="variations-card" class="card mb-4 d-none">
                    <div class="card-header bg-light">
                        <h6 class="mb-0">Generated Combinations</h6>
                    </div>
                    <div class="card-body">
                        <div id="variations-table-container" class="d-none">
                            <div class="table-responsive">
                                <table class="table table-bordered align-middle">
                                    <thead class="table-light">
                                        <tr id="variations-thead-row"></tr>
                                    </thead>
                                    <tbody id="variations-tbody"></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h6 class="mb-0">Categories</h6>
                </div>
                <div class="card-body">
                    <label class="form-label fw-bold">Product Categories *</label>
                    <div class="category-checkboxes" style="max-height: 300px; overflow-y: auto;">
                        @include('admin.products.partials.category-tree', [
                            'categories' => $categories,
                            'selected' => old('categories', $product->categories->pluck('id')->toArray())
                        ])
                    </div>
                    @error('categories')<div class="text-danger small">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h6 class="mb-0">Status</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Product Status</label>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_active" value="1"
                                   id="is_active" {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">Active Product</label>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Featured Product</label>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_featured" value="1"
                                   id="is_featured" {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_featured">Mark as Featured</label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-4" id="pricing-card">
                <div class="card-header bg-light">
                    <h6 class="mb-0">Pricing</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Base Price ($) *</label>
                            <input type="number" step="0.01" min="0" class="form-control @error('base_price') is-invalid @enderror"
                                   name="base_price" value="{{ old('base_price', $product->base_price) }}" required>
                            @error('base_price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Sale Price ($)</label>
                            <input type="number" step="0.01" min="0" class="form-control @error('sale_price') is-invalid @enderror"
                                   name="sale_price" value="{{ old('sale_price', $product->sale_price) }}">
                            @error('sale_price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-4 {{ $product->variations->isNotEmpty() ? 'd-none' : '' }}" id="inventory-card">
                <div class="card-header bg-light">
                    <h6 class="mb-0">Inventory</h6>
                    <p class="text-muted small mb-0">Hidden while this product has variations &mdash; each variation has its own stock below.</p>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-6">
                            <label class="form-label fw-bold">Stock Quantity *</label>
                            <input type="number" min="0" class="form-control @error('stock_quantity') is-invalid @enderror"
                                   name="stock_quantity" value="{{ old('stock_quantity', $product->stock_quantity) }}" required>
                            @error('stock_quantity')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-6">
                            <label class="form-label fw-bold">Weight (kg)</label>
                            <input type="number" step="0.01" min="0" class="form-control"
                                   name="weight" value="{{ old('weight', $product->weight) }}">
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h6 class="mb-0">Specifications</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Wheelbase</label>
                            <input type="text" class="form-control" name="wheelbase"
                                   value="{{ old('wheelbase', $product->wheelbase) }}" placeholder="e.g. 1350mm">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Range</label>
                            <input type="text" class="form-control" name="range"
                                   value="{{ old('range', $product->range) }}" placeholder="e.g. 60 miles">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Top Speed</label>
                            <input type="text" class="form-control" name="top_speed"
                                   value="{{ old('top_speed', $product->top_speed) }}" placeholder="e.g. 45 mph">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Power</label>
                            <input type="text" class="form-control" name="power"
                                   value="{{ old('power', $product->power) }}" placeholder="e.g. 3000W">
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h6 class="mb-0">Product Variations</h6>
                </div>
                <div class="card-body">
                    <label class="form-label fw-bold">Select Variation Types</label>
                    <div class="variation-checkboxes" style="max-height: 500px; overflow-y: auto;">
                        @if($variations->isEmpty())
                            <p class="text-muted mb-0">
                                No variations found.
                                <a href="{{ route('admin.variations.create') }}" target="_blank">
                                    Create one here
                                </a>
                            </p>
                        @else
                            @foreach($variations as $variation)
                                <div class="form-check px-4 py-2">
                                    <input class="form-check-input variation-type-check"
                                        type="checkbox"
                                        value="{{ $variation->id }}"
                                        id="variation_{{ $variation->id }}"
                                        data-name="{{ $variation->name }}"
                                        data-options='@json($variation->options->map(fn($o) => [
                                            "id"             => $o->id,
                                            "value"          => $o->value,
                                            "variation_name" => $variation->name
                                        ]))'
                                        {{ in_array($variation->id, $selectedVariationIds) ? 'checked' : '' }}>
                                    <label class="form-check-label fw-semibold" for="variation_{{ $variation->id }}">
                                        {{ $variation->name }}
                                        <small class="text-muted fw-normal">
                                            ({{ $variation->options->pluck('value')->join(', ') }})
                                        </small>
                                    </label>
                                </div>
                            @endforeach
                        @endif
                    </div>
                    @error('variations')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                    <button type="button"
                            class="btn btn-secondary btn-sm mt-3"
                            id="generateVariationsBtn">
                        <i class="fas fa-magic me-1"></i> Generate Combinations
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-body">
            <div class="row justify-content-between align-items-center">
                <div class="col-md">
                    <h5 class="mb-2 mb-md-0">Update Product</h5>
                    <p class="text-muted mb-0">Save changes to this product</p>
                </div>
                <div class="col-auto">
                    <button type="reset" class="btn btn-secondary me-2">
                        <i class="fas fa-undo me-1"></i> Reset
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Update Product
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div id="deleted-variations-container"></div>
</form>

@endsection

@push('custom-script')
<script>
    $(document).ready(function() {
        // Summernote初始化
        $('.summernote').summernote({
            height: 300,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['fontname', ['fontname']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture', 'video']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ],
            callbacks: {
                onChange: function(contents) {
                    $(this).val(contents);
                }
            }
        });

        // 分类折叠功能
        $('.category-toggle').click(function(e) {
            e.preventDefault();
            $(this).find('i').toggleClass('fa-chevron-right fa-chevron-down');
            $(this).next('.category-children').slideToggle();
        });

        $('#generateVariationsBtn').on('click', function () {
            const checkedBoxes = $('.variation-type-check:checked');

            if (checkedBoxes.length === 0) {
                Swal.fire({
                    title: 'No Variations Selected',
                    text: 'Please check at least one variation type.',
                    icon: 'warning',
                    confirmButtonColor: '#3085d6'
                });
                return;
            }

            Swal.fire({
                title: 'Are you sure?',
                text: "Regenerating will replace all current rows below!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, regenerate',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    executeGeneration(checkedBoxes, $(this));
                }
            });
        });

        function executeGeneration(checkedBoxes, $btn) {
            $btn.prop('disabled', true)
                .html('<i class="fas fa-spinner fa-spin me-1"></i> Generating...');

            // Mark every existing (already-saved) variation row for deletion before
            // replacing the table, otherwise regenerating just piles up duplicates
            // alongside the old rows instead of replacing them.
            $('#variations-tbody input[name$="[id]"]').each(function () {
                const variationId = $(this).val();
                if (variationId) {
                    $('#deleted-variations-container').append(
                        `<input type="hidden" name="deleted_variations[]" value="${variationId}">`
                    );
                }
            });

            setTimeout(function () {
                let optionGroups = [];
                let variationNames = [];
                
                checkedBoxes.each(function () {
                    optionGroups.push($(this).data('options'));
                    variationNames.push($(this).data('name'));
                });

                const combinations = cartesian(...optionGroups);

                let theadRow = '';
                variationNames.forEach(name => theadRow += `<th>${name}</th>`);
                theadRow += `<th>SKU</th><th>Base Price</th><th>Sale Price</th><th>Description</th><th>Stock</th><th class="text-center">Active</th><th class="text-center">Action</th>`;
                $('#variations-thead-row').html(theadRow);

                let rows = '';
                combinations.forEach(function (combo, index) {
                    const comboArray = Array.isArray(combo) ? combo : [combo];
                    const inputPrefix = `variations[new_${index}]`;
                    const optionInputs = comboArray.map(o =>
                        `<input type="hidden" name="${inputPrefix}[option_ids][]" value="${o.id}">`
                    ).join('');

                    let optionCells = '';
                    comboArray.forEach(o => {
                        optionCells += `<td>
                            <span class="fw-semibold d-block mb-1">${o.value}</span>
                            <input type="text" class="form-control form-control-sm"
                                name="${inputPrefix}[option_labels][${o.id}]"
                                placeholder="Display label (optional)">
                        </td>`;
                    });

                    rows += `
                        <tr>
                            ${optionCells}
                            ${optionInputs}
                            <td><input type="text" class="form-control form-control-sm" name="${inputPrefix}[sku]" placeholder="Auto-generate"></td>
                            <td><input type="number" class="form-control form-control-sm" name="${inputPrefix}[price]" step="0.01"></td>
                            <td><input type="number" class="form-control form-control-sm" name="${inputPrefix}[sale_price]" step="0.01"></td>
                            <td style="min-width: 180px;"><textarea class="form-control form-control-sm" name="${inputPrefix}[description]" rows="2" placeholder="Optional - overrides main description"></textarea></td>
                            <td><input type="number" class="form-control form-control-sm" name="${inputPrefix}[stock_quantity]"></td>
                            <td class="text-center">
                                <div class="form-check form-switch d-flex justify-content-center">
                                    <input class="form-check-input" type="checkbox" name="${inputPrefix}[is_active]" value="1" checked>
                                </div>
                            </td>
                            <td class="text-center">
                                <button type="button" class="btn btn-danger btn-sm remove-variation-row"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>`;
                });

                $('#variations-tbody').html(rows);
                $('#variations-card, #variations-table-container').removeClass('d-none');
                $('#inventory-card').addClass('d-none');
                $btn.prop('disabled', false).html('<i class="fas fa-magic me-1"></i> Generate Combinations');

            }, 400);
        }

        function cartesian(...arrays) {
            return arrays.reduce((acc, curr) => {
                return acc.flatMap(a =>
                    curr.map(b => [...(Array.isArray(a) ? a : [a]), b])
                );
            });
        }

        $(document).on('click', '.remove-variation-row', function () {
            const variationId = $(this).data('id');

            if (variationId) {
                $('#deleted-variations-container').append(
                    `<input type="hidden" name="deleted_variations[]" value="${variationId}">`
                );
                console.log('Container HTML:', $('#deleted-variations-container').html()); // 👈 add this
            }

            $(this).closest('tr').remove();

            if ($('#variations-tbody tr').length === 0) {
                $('#variations-card').addClass('d-none');
                $('#variations-table-container').addClass('d-none');
                $('#inventory-card').removeClass('d-none');
            }
        });
    });
</script>
@endpush
