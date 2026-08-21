@extends('admin.layouts.app')
@section('content')

@include('admin.layouts.partials.topbar')

<div class="card mb-3">
    <div class="card-body">
        <div class="row flex-between-center">
            <div class="col-md">
                <h5 class="mb-2 mb-md-0">Create New Product</h5>
                <p class="text-muted mb-0">Add a new product to your store</p>
            </div>
            <div class="col-auto">
                <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Back to Products
                </a>
            </div>
        </div>
    </div>
</div>

<form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" id="productForm">
    @csrf

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
                                   name="name" value="{{ old('name') }}" placeholder="Enter product name" required>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">SKU</label>
                            <input type="text" class="form-control @error('sku') is-invalid @enderror"
                                   name="sku" value="{{ old('sku') }}" placeholder="Leave blank to auto-generate">
                            @error('sku')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-bold">Short Description</label>
                            <textarea class="form-control @error('short_description') is-invalid @enderror"
                                      rows="3" name="short_description" placeholder="Brief product description">{{ old('short_description') }}</textarea>
                            @error('short_description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-bold">Description *</label>
                            <textarea class="form-control summernote @error('description') is-invalid @enderror"
                                      rows="8" name="description" placeholder="Detailed product description">{{ old('description') }}</textarea>
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
                            <label class="form-label fw-bold">Main Image *</label>
                            <input type="file" class="form-control @error('main_image') is-invalid @enderror"
                                   name="main_image" accept="image/*" required>
                            <small class="form-text text-muted">Supported formats: JPEG, PNG, GIF, WebP. Maximum file size: 5MB.</small>
                            @error('main_image')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-bold">Gallery Images</label>
                            <input type="file" class="form-control @error('gallery_images') is-invalid @enderror"
                                   name="gallery_images[]" accept="image/*" multiple>
                            <small class="form-text text-muted">You can select multiple images. Supported formats: JPEG, PNG, GIF, WebP.</small>
                            @error('gallery_images')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>
            </div>

            <div id="variations-card" class="card mb-4 d-none">
                <div class="card-header bg-light">
                    <h6 class="mb-0">Product Variations</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div id="variations-table-container" class="d-none mt-4">
                            <div class="table-responsive">
                                <table class="table table-bordered align-middle">
                                    <thead class="table-light">
                                        <tr id="variations-thead-row">
                                        </tr>
                                    </thead>
                                    <tbody id="variations-tbody"></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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
                            'selected' => old('categories', [])
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
                            <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active" checked>
                            <label class="form-check-label" for="is_active">Active Product</label>
                        </div>
                        <small class="form-text text-muted">Inactive products won't be visible on the website.</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Featured Product</label>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_featured" value="1" id="is_featured">
                            <label class="form-check-label" for="is_featured">Mark as Featured</label>
                        </div>
                        <small class="form-text text-muted">Featured products will be highlighted on the website.</small>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h6 class="mb-0">Pricing</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Base Price ($) *</label>
                            <input type="number" step="0.01" min="0" class="form-control @error('base_price') is-invalid @enderror"
                                   name="base_price" value="{{ old('base_price') }}" placeholder="0.00" required>
                            @error('base_price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Sale Price ($)</label>
                            <input type="number" step="0.01" min="0" class="form-control @error('sale_price') is-invalid @enderror"
                                   name="sale_price" value="{{ old('sale_price') }}" placeholder="0.00">
                            @error('sale_price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h6 class="mb-0">Inventory</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-6">
                            <label class="form-label fw-bold">Stock Quantity *</label>
                            <input type="number" min="0" class="form-control @error('stock_quantity') is-invalid @enderror"
                                   name="stock_quantity" value="{{ old('stock_quantity', 0) }}" required>
                            @error('stock_quantity')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-6">
                            <label class="form-label fw-bold">Weight (kg)</label>
                            <input type="number" step="0.01" min="0" class="form-control"
                                   name="weight" value="{{ old('weight') }}" placeholder="0.00">
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
                                   value="{{ old('wheelbase') }}" placeholder="e.g. 1350mm">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Range</label>
                            <input type="text" class="form-control" name="range"
                                   value="{{ old('range') }}" placeholder="e.g. 60 miles">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Top Speed</label>
                            <input type="text" class="form-control" name="top_speed"
                                   value="{{ old('top_speed') }}" placeholder="e.g. 45 mph">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Power</label>
                            <input type="text" class="form-control" name="power"
                                   value="{{ old('power') }}" placeholder="e.g. 3000W">
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
                                        ]))'>
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
                    <h5 class="mb-2 mb-md-0">Create Product</h5>
                    <p class="text-muted mb-0">Add this product to your store</p>
                </div>
                <div class="col-auto">
                    <button type="reset" class="btn btn-secondary me-2">
                        <i class="fas fa-undo me-1"></i> Reset
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Create Product
                    </button>
                </div>
            </div>
        </div>
    </div>
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

        // 自动生成SKU
        $('input[name="name"]').on('blur', function() {
            if (!$('input[name="sku"]').val()) {
                const name = $(this).val();
                if (name) {
                    const sku = name.toUpperCase().replace(/[^A-Z0-9]/g, '').substring(0, 10);
                    $('input[name="sku"]').val(sku + '-' + Math.random().toString(36).substr(2, 5).toUpperCase());
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

            const $btn = $(this);
            $btn.prop('disabled', true)
                .html('<i class="fas fa-spinner fa-spin me-1"></i> Generating...');

            setTimeout(function () {

                let optionGroups = [];
                let variationNames = [];
                checkedBoxes.each(function () {
                    optionGroups.push($(this).data('options'));
                    variationNames.push($(this).data('name'));
                });

                const combinations = cartesian(...optionGroups);

                let theadRow = '';
                variationNames.forEach(function (name) {
                    theadRow += `<th>${name}</th>`;
                });
                theadRow += `
                    <th>SKU</th>
                    <th>Base Price</th>
                    <th>Sale Price</th>
                    <th>Description</th>
                    <th>Stock</th>
                    <th class="text-center">Active</th>
                    <th class="text-center">Action</th>
                `;
                $('#variations-thead-row').html(theadRow);

                let rows = '';
                combinations.forEach(function (combo, index) {
                    const comboArray = Array.isArray(combo) ? combo : [combo];
                    const variationName = comboArray.map(o => o.value).join('-');
                    const optionIds  = comboArray.map(o =>
                        `<input type="hidden"
                                name="variations[${index}][option_ids][]"
                                value="${o.id}">`
                    ).join('');
                    const nameInput = `<input type="hidden" name="variations[${index}][name]" value="${variationName}">`;

                    let optionCells = '';
                    comboArray.forEach(function (o) {
                        optionCells += `<td>
                            <span class="fw-semibold d-block mb-1">${o.value}</span>
                            <input type="text" class="form-control form-control-sm"
                                name="variations[${index}][option_labels][${o.id}]"
                                placeholder="Display label (optional)">
                        </td>`;
                    });

                    rows += `
                        <tr>
                            ${optionCells}
                            ${optionIds}
                            ${nameInput}
                            <td>
                                <input type="text"
                                    class="form-control form-control-sm"
                                    name="variations[${index}][sku]"
                                    placeholder="Auto-generate">
                            </td>
                            <td>
                                <div class="input-group input-group-sm">
                                    <input type="number"
                                        class="form-control"
                                        name="variations[${index}][price]"
                                        placeholder="0.00"
                                        step="0.01"
                                        min="0">
                                </div>
                            </td>
                            <td>
                                <input type="number"
                                    class="form-control form-control-sm"
                                    name="variations[${index}][sale_price]"
                                    placeholder="0.00"
                                    step="0.01"
                                    min="0">
                            </td>
                            <td style="min-width: 180px;">
                                <textarea class="form-control form-control-sm"
                                    name="variations[${index}][description]"
                                    rows="2"
                                    placeholder="Optional - overrides main description"></textarea>
                            </td>
                            <td>
                                <input type="number"
                                    class="form-control form-control-sm"
                                    name="variations[${index}][stock_quantity]"
                                    placeholder="0"
                                    min="0">
                            </td>
                            <td class="text-center">
                                <div class="form-check form-switch d-flex justify-content-center">
                                    <input class="form-check-input"
                                        type="checkbox"
                                        name="variations[${index}][is_active]"
                                        value="1"
                                        checked>
                                </div>
                            </td>
                            <td class="text-center">
                                <button type="button" class="btn btn-danger btn-sm remove-variation-row">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    `;
                });

                $('#variations-tbody').html(rows);
                $('#variations-card').removeClass('d-none');
                $('#variations-table-container').removeClass('d-none');

                $btn.prop('disabled', false)
                    .html('<i class="fas fa-magic me-1"></i> Generate Combinations');

            }, 400);
        });

        function cartesian(...arrays) {
            return arrays.reduce((acc, curr) => {
                return acc.flatMap(a =>
                    curr.map(b => [...(Array.isArray(a) ? a : [a]), b])
                );
            });
        }

        $(document).on('click', '.remove-variation-row', function () {
            $(this).closest('tr').remove();

            if ($('#variations-tbody tr').length === 0) {
                $('#variations-card').addClass('d-none');
                $('#variations-table-container').addClass('d-none');
            }
        });
    });
</script>
@endpush
