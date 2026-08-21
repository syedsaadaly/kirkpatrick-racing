@php
    $isEditing = isset($variation);
    $action = $isEditing ? route('admin.variations.update', $variation->id) : route('admin.variations.store');
    $options = old('options', $isEditing ? $variation->options->toArray() : [['value' => '']]);
@endphp

<form action="{{ $action }}" method="POST" id="variationForm">
    @csrf
    @if ($isEditing)
        @method('PUT')
    @endif

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
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    name="name" value="{{ old('name', $isEditing ? $variation->name : '') }}"
                                    placeholder="e.g., Size, Color, Material" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Slug</label>
                                <input type="text" class="form-control @error('slug') is-invalid @enderror"
                                    name="slug" value="{{ old('slug', $isEditing ? $variation->slug : '') }}"
                                    placeholder="auto-generated-slug">
                                @error('slug')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Description</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" rows="3" name="description"
                                    placeholder="Variation description">{{ old('description', $isEditing ? $variation->description : '') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card mb-4">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">Variation Options</h6>
                    <button type="button" class="btn btn-sm btn-primary" id="addOptionBtn">
                        <i class="fas fa-plus me-1"></i> Add Option
                    </button>
                </div>
                <div class="card-body">
                    <div id="options-container">
                        @foreach ($options as $index => $option)
                            <div class="option-row mb-3 p-3 border rounded">
                                <div class="row g-2">
                                    @if (!empty($option['id']))
                                        <input type="hidden" name="options[{{ $index }}][id]" value="{{ $option['id'] }}">
                                    @endif
                                    <div class="col-md-7">
                                        <label class="form-label">Option Value <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control"
                                            name="options[{{ $index }}][value]"
                                            value="{{ $option['value'] ?? '' }}" placeholder="e.g., Small, Red, Cotton"
                                            required>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Swatch Color</label>
                                        <input type="color" class="form-control form-control-color w-100"
                                            name="options[{{ $index }}][color]"
                                            value="{{ $option['color'] ?? '#cccccc' }}"
                                            title="Only used for Color-type variations">
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
                    </div>
                    @error('options')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h6 class="mb-0">Status</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Variation Status</label>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_active" value="1"
                                id="is_active"
                                {{ old('is_active', $isEditing ? $variation->is_active : true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">Active Variation</label>
                        </div>
                        <small class="form-text text-muted">
                            Inactive variations won't be available for products.
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card mt-4">
        <div class="card-body">
            <div class="row justify-content-between align-items-center">
                <div class="col-md">
                    <h5 class="mb-2 mb-md-0">{{ $isEditing ? 'Update Variation' : 'Create Variation' }}</h5>
                    <p class="text-muted mb-0">
                        {{ $isEditing ? 'Save your changes to this variation' : 'Add this variation type to your store' }}
                    </p>
                </div>
                <div class="col-auto">
                    @if (!$isEditing)
                        <button type="reset" class="btn btn-secondary me-2">
                            <i class="fas fa-undo me-1"></i> Reset
                        </button>
                    @else
                        <a href="{{ route('admin.variations.index') }}" class="btn btn-secondary me-2">
                            <i class="fas fa-arrow-left me-1"></i> Cancel
                        </a>
                    @endif
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i>
                        {{ $isEditing ? 'Update Variation' : 'Create Variation' }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>
