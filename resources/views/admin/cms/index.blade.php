@extends('admin.layouts.app')

@section('content')
    @include('admin.layouts.partials.topbar')

    <div class="card mb-3">
        <div class="card-body">
            <div class="row flex-between-center">
                <div class="col-md">
                    <h5 class="mb-2 mb-md-0">{{ $page->name }}</h5>
                </div>
                @if ($page)
                    <div class="col-auto">
                        <a href="{{ route('admin.cms-builder.pages.listable-sections.index', $page->id) }}"
                            class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Back
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <form action="{{ route('admin.cms-builder.pages.update-data', $page->id) }}" method="POST" enctype="multipart/form-data"
        id="page-form">
        @csrf
        @method('PUT')
        <div class="card mb-4 shadow-sm">
            <div class="card-body">
                <div class="row g-3">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Meta Title</label>
                            <input type="text" class="form-control" name="meta_title"
                                value="{{ $page->meta_title ?? $page->name }}" placeholder="Enter Meta Title">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Meta Description</label>
                            <input type="text" class="form-control" name="meta_description"
                                value="{{ $page->meta_description ?? '' }}" placeholder="Enter Meta Description">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @foreach ($page->sections->sortBy('order') as $section)
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0">{{ $section->section_name }}</h5>
                    <span class="badge bg-{{ $section->section_type === 'form' ? 'primary' : 'secondary' }}">
                        {{ ucfirst($section->section_type) }} Section
                    </span>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        @foreach ($section->fields as $field)
                            <div class="col-12">
                                <div class="mb-3 field-wrapper">
                                    <label class="form-label fw-bold">{{ $field->field_name }}</label>

                                    @php
                                        $readonly = $section->section_type === 'form' ? 'readonly disabled' : '';

                                        $fieldData = $field->fieldData;
                                        $values = [];

                                        if ($fieldData && $fieldData->value) {
                                            if ($field->is_multiple && $field->field_type !== 'image') {
                                                $decoded = json_decode($fieldData->value, true);
                                                $values = is_array($decoded) ? $decoded : [$fieldData->value];
                                            } else {
                                                $values = [$fieldData->value];
                                            }
                                        }

                                        if (empty($values)) {
                                            $values = [''];
                                        }
                                    @endphp

                                    @if ($field->is_multiple && $field->field_type !== 'image')
                                        <div class="multiple-fields-container border rounded p-3 bg-light mb-2"
                                            id="multiple-fields-{{ $field->id }}">
                                            @foreach ($values as $index => $value)
                                                <div class="multiple-field-item mb-3">
                                                    <div class="input-group">
                                                        @if ($field->field_type === 'input')
                                                            <input type="text" class="form-control"
                                                                name="fields[{{ $field->id }}][]"
                                                                value="{{ $value }}"
                                                                placeholder="Enter {{ $field->field_name }}"
                                                                {{ $readonly }}>
                                                        @elseif($field->field_type === 'textarea')
                                                            <textarea class="form-control summernote" rows="5" name="fields[{{ $field->id }}][]"
                                                                placeholder="Enter {{ $field->field_name }}" {{ $readonly }}>{{ $value }}</textarea>
                                                        @endif

                                                        @if (!$readonly)
                                                            <button type="button" class="btn btn-danger remove-field ms-2"
                                                                style="border-radius: 4px; height: fit-content; align-self: center;"
                                                                @if (count($values) === 1) disabled @endif>
                                                                <i class="fas fa-times"></i>
                                                            </button>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>

                                        @if (!$readonly)
                                            <button type="button" class="btn btn-sm btn-success add-more-field"
                                                data-field-id="{{ $field->id }}"
                                                data-field-type="{{ $field->field_type }}">
                                                <i class="fas fa-plus me-1"></i> Add More
                                            </button>
                                        @endif
                                    @else
                                        @if ($field->field_type === 'input')
                                            <input type="text" class="form-control" name="fields[{{ $field->id }}]"
                                                value="{{ $values[0] ?? '' }}"
                                                placeholder="Enter {{ $field->field_name }}" {{ $readonly }}>
                                        @elseif($field->field_type === 'textarea')
                                            <textarea class="form-control summernote" rows="5" name="fields[{{ $field->id }}]"
                                                placeholder="Enter {{ $field->field_name }}" {{ $readonly }}>{{ $values[0] ?? '' }}</textarea>
                                        @elseif($field->field_type === 'radio')
                                            <div class="mt-2">
                                                @foreach ($field->options as $option)
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio"
                                                            name="fields[{{ $field->id }}]"
                                                            id="opt_{{ $option->id }}"
                                                            value="{{ $option->option_label }}"
                                                            {{ $values[0] == $option->option_label ? 'checked' : '' }}
                                                            {{ $readonly }}>
                                                        <label class="form-check-label" for="opt_{{ $option->id }}">
                                                            {{ $option->option_label }}
                                                        </label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @elseif($field->field_type === 'checkbox')
                                            <div class="mt-2">
                                                @php
                                                    $selectedValues = explode(',', $values[0] ?? '');
                                                    $selectedValues = array_map('trim', $selectedValues);
                                                @endphp
                                                @foreach ($field->options as $option)
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="checkbox"
                                                            name="fields[{{ $field->id }}][]"
                                                            id="opt_{{ $option->id }}"
                                                            value="{{ $option->option_label }}"
                                                            {{ in_array($option->option_label, $selectedValues) ? 'checked' : '' }}
                                                            {{ $readonly }}>
                                                        <label class="form-check-label" for="opt_{{ $option->id }}">
                                                            {{ $option->option_label }}
                                                        </label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @elseif($field->field_type === 'image')
                                            <div class="row align-items-center">
                                                <div class="col-md-6">
                                                    <input type="file" class="form-control"
                                                        name="fields[{{ $field->id }}]" accept="image/*"
                                                        {{ $readonly }}>
                                                    @if ($field->fieldData)
                                                        <input type="hidden" name="field_data_ids[{{ $field->id }}]"
                                                            value="{{ $field->fieldData->id }}">
                                                    @endif
                                                    <label class="form-label fw-bold mt-2">Media Alt Text</label>
                                                    <input type="text"
                                                        class="form-control @error('alt.' . $field->id) is-invalid @enderror"
                                                        name="alt[{{ $field->id }}]"
                                                        placeholder="Enter image alt text"
                                                        value="{{ old('alt.' . $field->id, $field->fieldData->alt_text ?? '') }}"
                                                        {{ $readonly }}>
                                                    @error('alt.' . $field->id)
                                                        <span class="text-danger small">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                @if ($values[0] ?? false)
                                                    <div class="col-md-6">
                                                        <div class="current-image">
                                                            <small class="text-muted d-block mb-1">Current Image:</small>
                                                            <img src="{{ $values[0] }}"
                                                                alt="{{ $field->field_name }}" class="img-thumbnail"
                                                                style="max-height: 100px;">
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        @endif
                                    @endif

                                    @if ($field->is_multiple && $field->field_type !== 'image')
                                        <small class="text-info mt-1 d-block">
                                            <i class="fas fa-info-circle"></i> This field supports multiple values
                                        </small>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endforeach

        <div class="card mt-4">
            <div class="card-body">
                <div class="row justify-content-between align-items-center">
                    <div class="col-md">
                        <h5 class="mb-2 mb-md-0">Update Page Content</h5>
                    </div>
                    <div class="col-auto">
                        <button type="reset" class="btn btn-secondary me-2" id="reset">Reset</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Update Content
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
                    onInit: function() {},
                    onChange: function(contents, $editable) {
                        $(this).val(contents);
                    }
                }
            });

            $(document).on('click', '.add-more-field', function() {
                const fieldId = $(this).data('field-id');
                const fieldType = $(this).data('field-type');
                const container = $(`#multiple-fields-${fieldId}`);

                container.find('.remove-field').prop('disabled', false);

                let newFieldHtml = '';

                if (fieldType === 'input') {
                    newFieldHtml = `
                    <div class="multiple-field-item mb-3">
                        <div class="input-group">
                            <input type="text" class="form-control" name="fields[${fieldId}][]" placeholder="Enter value">
                            <button type="button" class="btn btn-danger remove-field ms-2" style="border-radius: 4px; height: fit-content; align-self: center;">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                `;
                } else if (fieldType === 'textarea') {
                    newFieldHtml = `
                    <div class="multiple-field-item mb-3">
                        <div class="input-group">
                            <textarea class="form-control summernote" rows="5" name="fields[${fieldId}][]" placeholder="Enter value"></textarea>
                            <button type="button" class="btn btn-danger remove-field ms-2" style="border-radius: 4px; height: fit-content; align-self: center;">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                `;
                } else if (fieldType === 'radio' || fieldType === 'checkbox') {
                    const firstItem = container.find('.multiple-field-item').first();
                    const clonedOptions = firstItem.find('.form-check').parent().html();
                    
                    newFieldHtml = `
                    <div class="multiple-field-item mb-3 border-top pt-3">
                        <div class="d-flex align-items-start">
                            <div class="flex-grow-1">
                                ${clonedOptions}
                            </div>
                            <button type="button" class="btn btn-danger remove-field ms-2" style="border-radius: 4px;">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>`;
                }

                container.append(newFieldHtml);

                if (fieldType === 'textarea') {
                    container.find('.summernote').last().summernote({
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
                }
            });

            $(document).on('click', '.remove-field', function() {
                const fieldItem = $(this).closest('.multiple-field-item');
                const container = fieldItem.closest('.multiple-fields-container');
                const fieldItems = container.find('.multiple-field-item');

                if (fieldItems.length > 1) {
                    fieldItem.remove();

                    if (fieldItems.length === 1) {
                        container.find('.remove-field').prop('disabled', true);
                    }
                }
            });

            $('#page-form').on('submit', function() {
                $('.summernote').each(function() {
                    $(this).val($(this).summernote('code'));
                });
            });

            $(document).on('click', '#reset', function() {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "All unsaved changes will be lost!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, reset it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.reload();
                    }
                });
            });
        });
    </script>
@endpush
