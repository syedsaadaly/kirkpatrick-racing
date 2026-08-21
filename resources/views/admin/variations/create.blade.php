{{-- @extends('admin.layouts.app')
@section('content')

@include('admin.layouts.partials.topbar')

<div class="card mb-3">
    <div class="card-body">
        <div class="row flex-between-center">
            <div class="col-md">
                <h5 class="mb-2 mb-md-0">Create New Variation</h5>
                <p class="text-muted mb-0">Add a new product variation type</p>
            </div>
            <div class="col-auto">
                <a href="{{ route('admin.variations.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Back to Variations
                </a>
            </div>
        </div>
    </div>
</div>

<form action="{{ route('admin.variations.store') }}" method="POST" id="variationForm">
    @csrf

    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h6 class="mb-0">Variation Details</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">
                                    Variation Name <span class="text-danger">*</span>
                                </label>
                                <input type="text"
                                       class="form-control @error('name') is-invalid @enderror"
                                       name="name"
                                       value="{{ old('name') }}"
                                       placeholder="e.g., Size, Color, Material"
                                       required>
                                @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">
                                    Slug
                                </label>
                                <input type="text"
                                       class="form-control @error('slug') is-invalid @enderror"
                                       name="slug"
                                       value="{{ old('slug') }}"
                                       placeholder="auto-generated-slug">
                                @error('slug')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label fw-bold">
                                    Description
                                </label>
                                <textarea class="form-control @error('description') is-invalid @enderror"
                                          rows="3"
                                          name="description"
                                          placeholder="Variation description">{{ old('description') }}</textarea>
                                @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Variation Options -->
            <div class="card mb-4">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">Variation Options</h6>
                    <button type="button" class="btn btn-sm btn-primary" id="addOptionBtn">
                        <i class="fas fa-plus me-1"></i> Add Option
                    </button>
                </div>
                <div class="card-body">
                    <div id="options-container">
                        @if (old('options'))
                            @foreach (old('options') as $index => $option)
                            <div class="option-row mb-3 p-3 border rounded">
                                <div class="row g-2">
                                    <div class="col-md-10">
                                        <label class="form-label">Option Value <span class="text-danger">*</span></label>
                                        <input type="text"
                                               class="form-control"
                                               name="options[{{ $index }}][value]"
                                               value="{{ $option['value'] ?? '' }}"
                                               placeholder="e.g., Small, Red, Cotton"
                                               required>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">&nbsp;</label>
                                        <button type="button" class="btn btn-danger w-100 remove-option">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        @else
                        <div class="option-row mb-3 p-3 border rounded">
                            <div class="row g-2">
                                <div class="col-md-10">
                                    <label class="form-label">Option Value <span class="text-danger">*</span></label>
                                    <input type="text"
                                           class="form-control"
                                           name="options[0][value]"
                                           value=""
                                           placeholder="e.g., Small, Red, Cotton"
                                           required>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">&nbsp;</label>
                                    <button type="button" class="btn btn-danger w-100 remove-option">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                    @error('options')
                    <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Status -->
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h6 class="mb-0">Status</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Variation Status</label>
                        <div class="form-check form-switch">
                            <input class="form-check-input"
                                   type="checkbox"
                                   name="is_active"
                                   value="1"
                                   id="is_active"
                                   {{ old('is_active', true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                Active Variation
                            </label>
                        </div>
                        <small class="form-text text-muted">
                            Inactive variations won't be available for products.
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Submit Card -->
    <div class="card mt-4">
        <div class="card-body">
            <div class="row justify-content-between align-items-center">
                <div class="col-md">
                    <h5 class="mb-2 mb-md-0">Create Variation</h5>
                    <p class="text-muted mb-0">Add this variation type to your store</p>
                </div>
                <div class="col-auto">
                    <button type="reset" class="btn btn-secondary me-2">
                        <i class="fas fa-undo me-1"></i> Reset
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Create Variation
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
        let optionCount = {{ old('options') ? count(old('options')) : 1 }};

        $('#addOptionBtn').on('click', function() {
            const newRow = `
                <div class="option-row mb-3 p-3 border rounded">
                    <div class="row g-2">
                        <div class="col-md-10">
                            <label class="form-label">Option Value <span class="text-danger">*</span></label>
                            <input type="text"
                                   class="form-control"
                                   name="options[${optionCount}][value]"
                                   value=""
                                   placeholder="e.g., Small, Red, Cotton"
                                   required>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">&nbsp;</label>
                            <button type="button" class="btn btn-danger w-100 remove-option">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            `;
            $('#options-container').append(newRow);
            optionCount++;
        });

        $(document).on('click', '.remove-option', function() {
            if ($('.option-row').length > 1) {
                $(this).closest('.option-row').remove();
            } else {
                Swal.fire({
                    title: 'Cannot Remove',
                    text: 'At least one option is required.',
                    icon: 'warning',
                    confirmButtonColor: '#3085d6'
                });
            }
        });

        $('input[name="name"]').on('blur', function() {
            if (!$('input[name="slug"]').val()) {
                const name = $(this).val();
                if (name) {
                    const slug = name.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)/g, '');
                    $('input[name="slug"]').val(slug);
                }
            }
        });
    });
</script>
@endpush --}}

@extends('admin.layouts.app')
@section('content')
    @include('admin.layouts.partials.topbar')

    <div class="card mb-3">
        <div class="card-body">
            <div class="row flex-between-center">
                <div class="col-md">
                    <h5 class="mb-2 mb-md-0">Create New Variation</h5>
                    <p class="text-muted mb-0">Add a new product variation type</p>
                </div>
                <div class="col-auto">
                    <a href="{{ route('admin.variations.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Back to Variations
                    </a>
                </div>
            </div>
        </div>
    </div>

    @include('admin.variations.partials.form')
@endsection

@push('custom-script')
    @include('admin.variations.partials.form-script')
@endpush
