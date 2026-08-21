@extends('admin.layouts.app')
@section('content')
    @include('admin.layouts.partials.topbar')

    <div class="card mb-3">
        <div class="card-body">
            <div class="row flex-between-center">
                <div class="col-md">
                    <h5 class="mb-2 mb-md-0">Edit Item - {{ $section->section_name }}</h5>
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

    <form action="{{ route('admin.cms-builder.pages.sections.items.update', [$page->id, $section->id, $item->id]) }}"
        method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="card">
            <div class="card-header bg-light">
                <h6 class="mb-0">Edit Item Details</h6>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Meta Title</label>
                            <input type="text" class="form-control" name="meta_title"
                                value="{{ $item->meta_title ?? '' }}" placeholder="Enter Meta Title" required>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Meta Description</label>
                            <input type="text" class="form-control" name="meta_description"
                                value="{{ $item->meta_description ?? '' }}" placeholder="Enter Meta Description">
                        </div>
                    </div>
                    @foreach ($section->fields as $field)
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label fw-bold">{{ $field->field_name }}</label>

                                @php
                                    $fieldData = $item->fieldData->where('page_section_field_id', $field->id)->first();
                                    $currentValue = $fieldData ? $fieldData->value : '';
                                @endphp

                                @if ($field->field_type === 'input')
                                    <input type="text"
                                        class="form-control @error('fields.' . $field->id) is-invalid @enderror"
                                        name="fields[{{ $field->id }}]"
                                        value="{{ old('fields.' . $field->id, $currentValue) }}"
                                        placeholder="Enter {{ $field->field_name }}" required>
                                @elseif($field->field_type === 'textarea')
                                    <textarea class="form-control summernote @error('fields.' . $field->id) is-invalid @enderror" rows="5"
                                        name="fields[{{ $field->id }}]" placeholder="Enter {{ $field->field_name }}" required>{{ old('fields.' . $field->id, $currentValue) }}</textarea>
                                @elseif($field->field_type === 'image')
                                    <div class="row align-items-center">
                                        <div class="col-md-6">
                                            <input type="file"
                                                class="form-control @error('fields.' . $field->id) is-invalid @enderror"
                                                name="fields[{{ $field->id }}]" accept="image/*">
                                            <small class="form-text text-muted">
                                                Leave empty to keep current image.
                                            </small>
                                            @if (isset($fieldData->id))
                                                <input type="hidden" name="field_data_ids[{{ $field->id }}]"
                                                    value="{{ $fieldData->id }}">
                                            @endif
                                            <input type="text"
                                                class="form-control mt-2 @error('fields.' . $field->id . '.alt') is-invalid @enderror"
                                                name="alt[{{ $field->id }}]" placeholder="Enter image alt text"
                                                value="{{ old('alt.' . $field->id, $fieldData->alt_text ?? '') }}" />
                                        </div>
                                        @if ($currentValue)
                                            <div class="col-md-6">
                                                <div class="current-image">
                                                    <small class="text-muted d-block mb-1">Current Image:</small>
                                                    <img src="{{ $item->getFirstMediaUrl('section-items') ?? '' }}"
                                                        alt="{{ $field->field_name }}" class="img-thumbnail"
                                                        style="max-height: 100px;">
                                                    <div class="mt-2">
                                                        <a href="{{ $item->getFirstMediaUrl('section-items') ?? '' }}"
                                                            target="_blank" class="btn btn-sm btn-outline-primary">
                                                            <i class="fas fa-external-link-alt me-1"></i> View Full Size
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                @elseif($field->field_type === 'radio')
                                    <div class="mt-2">
                                        @foreach ($field->options as $option)
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio"
                                                    name="fields[{{ $field->id }}]" id="opt_{{ $option->id }}"
                                                    value="{{ $option->option_label }}"
                                                    {{ $currentValue == $option->option_label ? 'checked' : '' }}>
                                                <label class="form-check-label"
                                                    for="opt_{{ $option->id }}">{{ $option->option_label }}</label>
                                            </div>
                                        @endforeach
                                    </div>
                                @elseif($field->field_type === 'checkbox')
                                    <div class="mt-2">
                                        @php
                                            $selectedArray = array_map('trim', explode(',', $currentValue));
                                        @endphp
                                        @foreach ($field->options as $option)
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox"
                                                    name="fields[{{ $field->id }}][]" id="opt_{{ $option->id }}"
                                                    value="{{ $option->option_label }}"
                                                    {{ in_array($option->option_label, $selectedArray) ? 'checked' : '' }}>
                                                <label class="form-check-label"
                                                    for="opt_{{ $option->id }}">{{ $option->option_label }}</label>
                                            </div>
                                        @endforeach
                                    </div>
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
                        <h5 class="mb-2 mb-md-0">Update Item</h5>
                        <p class="text-muted mb-0">Update this item in {{ $section->section_name }}</p>
                    </div>
                    <div class="col-auto">
                        <button type="reset" class="btn btn-secondary me-2">
                            <i class="fas fa-undo me-1"></i> Reset
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Update Item
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
