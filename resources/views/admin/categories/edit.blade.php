@extends('admin.layouts.app')
@section('content')

@include('admin.layouts.partials.topbar')

<div class="card mb-3">
    <div class="card-body">
        <div class="row flex-between-center">
            <div class="col-md">
                <h5 class="mb-2 mb-md-0">Edit Category - {{ $category->name }}</h5>
                <p class="text-muted mb-0">Update category details</p>
            </div>
            <div class="col-auto">
                <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Back to Categories
                </a>
            </div>
        </div>
    </div>
</div>

<form action="{{ route('admin.categories.update', $category->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="card">
        <div class="card-header bg-light">
            <h6 class="mb-0">Category Details</h6>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            Category Name <span class="text-danger">*</span>
                        </label>
                        <input type="text"
                               class="form-control @error('name') is-invalid @enderror"
                               name="name"
                               value="{{ old('name', $category->name) }}"
                               placeholder="Enter category name"
                               required>
                        @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            Parent Category
                        </label>
                        <select class="form-select @error('parent_id') is-invalid @enderror" name="parent_id">
                            <option value="">Select Parent Category</option>
                            @foreach($parentCategories as $parent)
                            @if($parent->id !== $category->id)
                            <option value="{{ $parent->id }}"
                                {{ old('parent_id', $category->parent_id) == $parent->id ? 'selected' : '' }}>
                                {{ $parent->name }}
                            </option>
                            @endif
                            @endforeach
                        </select>
                        @error('parent_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            Current Image
                        </label>
                        <div>
                            @if($category->getFirstMediaUrl('categories'))
                            <img src="{{ $category->getFirstMediaUrl('categories') }}"
                                 alt="{{ $category->name }}"
                                 class="img-thumbnail mb-2"
                                 style="max-height: 100px; max-width: 150px;">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remove_image" value="1" id="remove_image">
                                <label class="form-check-label text-danger" for="remove_image">
                                    Remove current image
                                </label>
                            </div>
                            @else
                            <span class="text-muted">No image uploaded</span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-12">
                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            Description <span class="text-danger">*</span>
                        </label>
                        <textarea class="form-control summernote @error('description') is-invalid @enderror"
                                  rows="5"
                                  name="description"
                                  placeholder="Enter category description"
                                  required>{{ old('description', $category->description) }}</textarea>
                        @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            Update Image
                        </label>
                        <input type="file"
                               class="form-control @error('image') is-invalid @enderror"
                               name="image"
                               accept="image/*">
                        <small class="form-text text-muted">
                            Leave empty to keep current image. Supported formats: JPEG, PNG, GIF, WebP. Maximum file size: 5MB.
                        </small>
                        @error('image')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            Status
                        </label>
                        <div class="form-check form-switch">
                            <input class="form-check-input"
                                   type="checkbox"
                                   name="is_active"
                                   value="1"
                                   id="is_active"
                                   {{ old('is_active', $category->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                Active Category
                            </label>
                        </div>
                        <small class="form-text text-muted">
                            Inactive categories won't be visible on the website.
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
                    <h5 class="mb-2 mb-md-0">Update Category</h5>
                    <p class="text-muted mb-0">Save changes to this category</p>
                </div>
                <div class="col-auto">
                    <button type="reset" class="btn btn-secondary me-2">
                        <i class="fas fa-undo me-1"></i> Reset
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Update Category
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
        $('.summernote').summernote({
            height: 200,
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
                onChange: function(contents, $editable) {
                    $(this).val(contents);
                }
            }
        });

        $('form').on('submit', function() {
            $('.summernote').each(function() {
                if ($(this).summernote('isEmpty')) {
                    $(this).val('');
                }
            });
        });

        $('#remove_image').on('change', function() {
            if ($(this).is(':checked')) {
                $('input[name="image"]').prop('required', true);
            } else {
                $('input[name="image"]').prop('required', false);
            }
        });
    });
</script>
@endpush
