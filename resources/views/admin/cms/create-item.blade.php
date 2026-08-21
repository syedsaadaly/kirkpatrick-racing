@extends('admin.layouts.app')
@section('content')
    @include('admin.layouts.partials.topbar')

    <div class="card mb-3">
        <div class="card-body">
            <div class="row flex-between-center">
                <div class="col-md">
                    <h5 class="mb-2 mb-md-0">Add New Item - {{ $section->section_name }}</h5>
                    <p class="text-muted mb-0">Page: {{ $page->name }}</p>
                </div>
                <div class="col-auto">
                    <a href="{{ route('admin.cms-builder.pages.listable-sections.index', $page->id) . '?section_id=' . $section->id }}"
                        class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Back to Items
                    </a>
                </div>
            </div>
        </div>
    </div>

    <form action="{{ route('admin.cms-builder.pages.sections.items.store', [$page->id, $section->id]) }}" method="POST"
        enctype="multipart/form-data">
        @csrf

        <div class="card">
            <div class="card-header bg-light">
                <h6 class="mb-0">Item Details</h6>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Meta Title</label>
                            <input type="text" class="form-control" name="meta_title" value="{{ old('meta_title') }}" placeholder="Enter Meta Title" required>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Meta Description</label>
                            <input type="text" class="form-control" name="meta_description" value="{{ old('meta_description') }}" placeholder="Enter Meta Description">
                        </div>
                    </div>
                    @foreach ($section->fields as $field)
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label fw-bold">
                                    {{ $field->field_name }}
                                    @if ($field->field_type === 'image')
                                        <span class="text-danger">*</span>
                                    @endif
                                </label>

                                @if ($field->field_type === 'input')
                                    <input type="text"
                                        class="form-control @error('fields.' . $field->id) is-invalid @enderror"
                                        name="fields[{{ $field->id }}]" value="{{ old('fields.' . $field->id) }}"
                                        placeholder="Enter {{ $field->field_name }}" required>
                                @elseif($field->field_type === 'textarea')
                                    <textarea class="form-control summernote @error('fields.' . $field->id) is-invalid @enderror" rows="5"
                                        name="fields[{{ $field->id }}]" placeholder="Enter {{ $field->field_name }}" required>{{ old('fields.' . $field->id) }}</textarea>
                                @elseif($field->field_type === 'image')
                                    <input type="file"
                                        class="form-control @error('fields.' . $field->id) is-invalid @enderror"
                                        name="fields[{{ $field->id }}]" accept="image/*" required>
                                    <input type="text" 
                                        class="form-control mt-2 @error('fields.' . $field->id . '.alt') is-invalid @enderror" 
                                        name="alt[{{ $field->id }}]" 
                                        placeholder="Enter image alt text"
                                        value="{{ old('fields.'.$field->id.'.alt') }}">
                                    <small class="form-text text-muted">
                                        Supported formats: JPEG, PNG, GIF, WebP. Maximum file size: 5MB.
                                    </small>
                                @elseif($field->field_type === 'radio')
                                    @foreach($field->options as $option)
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" 
                                                name="field_{{ $field->id }}" 
                                                id="opt_{{ $option->id }}" 
                                                value="{{ $option->option_label }}"
                                                {{ $field->is_required ? 'required' : '' }}>
                                            <label class="form-check-label" for="opt_{{ $option->id }}">
                                                {{ $option->option_label }}
                                            </label>
                                        </div>
                                    @endforeach
                                @elseif($field->field_type === 'checkbox')
                                    @foreach($field->options as $option)
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" 
                                                name="field_{{ $field->id }}[]" 
                                                id="opt_{{ $option->id }}" 
                                                value="{{ $option->option_label }}">
                                            <label class="form-check-label" for="opt_{{ $option->id }}">
                                                {{ $option->option_label }}
                                            </label>
                                        </div>
                                    @endforeach
                                @endif

                                @error('fields.' . $field->id)
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-body">
                <div class="row justify-content-between align-items-center">
                    <div class="col-md">
                        <h5 class="mb-2 mb-md-0">Create New Item</h5>
                        <p class="text-muted mb-0">Add this item to {{ $section->section_name }}</p>
                    </div>
                    <div class="col-auto">
                        <button type="reset" class="btn btn-secondary me-2">
                            <i class="fas fa-undo me-1"></i> Reset
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Create Item
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
        });
    </script>
@endpush
