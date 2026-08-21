@extends('admin.layouts.app')
@section('content')
    <style>
        html,
        body {
            height: 100%;
        }

        .module-scroll-card {
            height: calc(100vh - 220px);
            overflow-y: auto;
        }

        .field-palette-item {
            cursor: grab;
            background: #f8f9fa;
        }

        .fields-drop-zone.border-primary {
            background: rgba(13, 110, 253, .05);
        }

        .drag-fields-zone {
            border: 2px dashed #ced4da;
            color: #6c757d;
            padding: 30px;
            text-align: center;
            font-size: 13px;
            border-radius: 6px;
            cursor: pointer;
            background-color: #f8f9fa;
            transition: all 0.2s ease-in-out;
        }

        .drag-fields-zone:hover {
            background-color: #f1f3f5;
            border-color: #adb5bd;
            color: #495057;
        }

        .drag-fields-zone.drag-over {
            background-color: #e9ecef;
            border-color: #0d6efd;
            color: #0d6efd;
        }
    </style>

    @include('admin.layouts.partials.topbar')
    <div class="card mb-3">
        <div class="card-body">
            <div class="row flex-between-center">
                <div class="col-md">
                    <h5 class="mb-2 mb-md-0">Edit Page: {{ $page->name }}</h5>
                </div>
                <div class="col-auto">
                    <a href="{{ route('admin.cms-builder.pages.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Back to Page Builder
                    </a>
                </div>
            </div>
        </div>
    </div>

    <form action="{{ route('admin.cms-builder.pages.update', $page->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="row g-0">
            <div class="col-lg-8 pe-lg-2">
                <div class="card mb-3 mb-lg-0 module-scroll-card">
                    <div class="card-header">
                        <h5 class="mb-0">Module Sections</h5>
                    </div>
                    <div class="card-body bg-body-tertiary">
                        <div class="accordion" id="accordionExample">
                            @foreach ($page->sections as $sectionIndex => $section)
                                <div class="accordion-item mb-2" id="section-{{ $section->id }}">

                                    <h2 class="accordion-header const-accordion tb-reltv"
                                        id="heading-section-{{ $section->id }}">

                                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#section-{{ $section->id }}-collapse" aria-expanded="true"
                                            aria-controls="section-{{ $section->id }}-collapse">
                                            Order: {{ $section->order }}
                                        </button>
                                        <div class="d-flex justify-content-between m-2">
                                            <input type="text" name="section[section-{{ $section->id }}][data][name]"
                                                class="form-control form-control-sm editable-field w-50 const-section-name font-13"
                                                value="{{ $section->section_name }}">
                                            <div class="btn-group ms-2">
                                                <button class="btn btn-secondary btn-sm" type="button"
                                                    title="Edit this section" data-bs-toggle="tooltip"
                                                    data-bs-placement="top"
                                                    onclick="editSection('section-{{ $section->id }}')"><i
                                                        class="far fa-edit"></i></button>
                                                <button class="btn btn-success btn-sm" type="button"
                                                    onclick="duplicateSection('section-{{ $section->id }}')"
                                                    title="Duplicate Section" data-bs-toggle="tooltip"
                                                    data-bs-placement="top">
                                                    <i class="far fa-copy"></i></button>
                                                <button class="btn btn-danger btn-sm" type="button"
                                                    title="Remove this section" data-bs-toggle="tooltip"
                                                    data-bs-placement="top"
                                                    onclick="removeSection('section-{{ $section->id }}')"><i
                                                        class="fas fa-trash-alt"></i></button>
                                            </div>
                                        </div>

                                    </h2>

                                    <div class="accordion-collapse collapse " id="section-{{ $section->id }}-collapse"
                                        aria-labelledby="heading-section-{{ $section->id }}"
                                        data-bs-parent="#accordionExample">
                                        <div class="accordion-body">
                                            <input type="hidden" name="section[section-{{ $section->id }}][data][id]"
                                                class="const-section-id" value="section-{{ $section->id }}">

                                            <input type="hidden" name="section[section-{{ $section->id }}][data][order]"
                                                class="const-section-order" value="{{ $section->order }}">
                                            <input type="hidden"
                                                name="section[section-{{ $section->id }}][data][section_type]"
                                                class="const-section-type" value="{{ $section->section_type }}">
                                            <input type="hidden"
                                                name="section[section-{{ $section->id }}][data][is_listable]"
                                                class="const-section-listable" value="{{ $section->is_listable }}">
                                            <input type="hidden"
                                                name="section[section-{{ $section->id }}][data][related_section_id]"
                                                class="const-section-related_section_id"
                                                value="{{ $section->related_section_id }}">
                                            <input type="hidden"
                                                name="section[section-{{ $section->id }}][data][relation]"
                                                class="const-section-relation" value="{{ $section->relation }}">
                                            <p>
                                                <strong>Selected Type:</strong>&nbsp;
                                                {{ $section->section_type === 'form' ? 'Form Section' : 'Content Section' }}
                                            </p>

                                            <p>
                                                <strong>Listable:</strong> {{ $section->is_listable ? 'Yes' : 'No' }}
                                            </p>

                                            <div class="fields-drop-zone" data-section-id="section-{{ $section->id }}">

                                                <hr class="text-200">

                                                <div class="row gx-2" id="fields-container-section-{{ $section->id }}">
                                                    @foreach ($section->fields as $fieldIndex => $field)
                                                        @php
                                                            $fieldData = $field->fieldData;
                                                        @endphp
                                                        <input type="hidden"
                                                            name="section[section-{{ $section->id }}][fields][{{ $fieldIndex }}][id]"
                                                            value="{{ $field->id ?? '' }}">
                                                        <div class="col-12 mb-3 field-item"
                                                            data-field-index="{{ $fieldIndex }}">
                                                            {{-- <label class="form-label">{{ $field->field_name }}</label> --}}
                                                            <div class="d-flex justify-content-between">
                                                                <input type="text"
                                                                    name="section[section-{{ $section->id }}][fields][{{ $fieldIndex }}][field_name]"
                                                                    class="form-control form-control-sm editable-field w-50 mb-2"
                                                                    value="{{ $field->field_name }}">
                                                                <div class="form-check form-switch mb-0 lh-1">
                                                                    <input class="form-check-input" type="checkbox"
                                                                        id="field-multiple-{{ $fieldIndex }}-{{ $section->id }}"
                                                                        name="section[section-{{ $section->id }}][fields][{{ $fieldIndex }}][is_multiple]"
                                                                        value="1"
                                                                        {{ $field->is_multiple ? 'checked' : '' }}>
                                                                    <label class="form-check-label mb-0"
                                                                        for="field-multiple-{{ $fieldIndex }}-{{ $section->id }}">
                                                                        Multiple values
                                                                    </label>

                                                                    <button type="button"
                                                                        class="btn btn-link text-danger btn-sm"
                                                                        onclick="removeField(this)" title="Remove Field"
                                                                        data-bs-toggle="tooltip" data-bs-placement="top">
                                                                        <i class="fas fa-trash-alt"></i>
                                                                    </button>
                                                                </div>

                                                            </div>
                                                            {{-- <input type="hidden" name="section[section-{{ $section->id }}][fields][{{ $fieldIndex }}][field_name]" value="{{ $field->field_name }}"> --}}
                                                            <input type="hidden"
                                                                name="section[section-{{ $section->id }}][fields][{{ $fieldIndex }}][field_type]"
                                                                value="{{ $field->field_type }}">

                                                            @if ($field->field_type === 'input')
                                                                <input class="form-control form-control-sm" type="text"
                                                                    value="{{ $fieldData->value ?? '' }}"
                                                                    name="section[section-{{ $section->id }}][fields][{{ $fieldIndex }}][value]">
                                                            @elseif($field->field_type === 'image')
                                                                <input class="form-control form-control-sm" type="file"
                                                                    name="section[section-{{ $section->id }}][fields][{{ $fieldIndex }}][value]">
                                                                @if ($fieldData && $fieldData->value)
                                                                    <small class="text-muted">Current:
                                                                        {{ $fieldData->value }}</small>
                                                                    <input type="hidden"
                                                                        name="section[section-{{ $section->id }}][fields][{{ $fieldIndex }}][current_value]"
                                                                        value="{{ $fieldData->value }}">
                                                                @endif
                                                            @elseif($field->field_type === 'textarea')
                                                                <textarea class="form-control form-control-sm" rows="3"
                                                                    name="section[section-{{ $section->id }}][fields][{{ $fieldIndex }}][value]">{{ $fieldData->value ?? '' }}</textarea>
                                                            @endif
                                                        </div>
                                                    @endforeach
                                                </div>
                                                <div class="drag-fields-zone mt-3"
                                                    data-section-id="section-{{ $section->id }}">
                                                    <i class="fas fa-plus-circle me-1"></i>
                                                    Drag & Drop fields here
                                                </div>
                                            </div>
                                            {{-- <button class="btn btn-secondary btn-sm mt-2" type="button"
                                                onclick="addFieldToSection('section-{{ $section->id }}')">
                                                Add Field to Section
                                            </button> --}}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 ps-lg-2">
                <div class="sticky-sidebar module-scroll-card">
                    <div class="card mb-3">
                        <div class="card-header bg-body-tertiary">
                            <h6 class="mb-0">Module Settings</h6>
                        </div>
                        <div class="card-body">
                            <div class="row gx-2">
                                <div class="col-12 mb-3">
                                    <label for="page-name" class="form-label">Module Name</label>
                                    <input type="text" class="form-control form-control-sm" id="page-name"
                                        name="page[name]" value="{{ old('page.name', $page->name) }}"
                                        placeholder="Enter Page Name">

                                    <div class="form-check form-switch mt-3">
                                        <input class="form-check-input" name="is_cms" type="checkbox" id="is-cms"
                                            {{ old('is_cms', $page->is_cms) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is-cms">Feature Module</label>
                                    </div>

                                    <div class="mt-3" id="icon-selection-section"
                                        style="{{ $page->is_cms ? 'display:block;' : 'display:none;' }}">

                                        <label class="form-label d-flex justify-content-between align-items-center">
                                            <span>Select Icon</span>
                                            <small class="text-muted">Scroll horizontally to see more icons →</small>
                                        </label>

                                        <div class="icon-scroll-container mb-3">
                                            <div class="icon-scroll-wrapper" id="icon-scroll-wrapper"></div>
                                            <div class="scroll-arrows">
                                                <button type="button" class="scroll-arrow scroll-left">
                                                    <i class="fas fa-chevron-left"></i>
                                                </button>
                                                <button type="button" class="scroll-arrow scroll-right">
                                                    <i class="fas fa-chevron-right"></i>
                                                </button>
                                            </div>
                                        </div>

                                        <input type="hidden" name="icon" id="selected-icon"
                                            value="{{ old('icon', $page->icon) }}">
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card mb-3 const-control-d-parent">
                        <div class="card-header bg-body-tertiary">
                            <h6 class="mb-0">Section Settings <span id="update-section-name"></span></h6>
                        </div>
                        <div class="card-body">
                            <input type="hidden" id="update-section-id">
                            <div class="row gx-2">
                                <div class="col-12 mb-3">
                                    <label for="section-name" class="form-label">Section Name</label>
                                    <input type="text" class="form-control form-control-sm" id="section-name"
                                        placeholder="Enter Section Name">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="section_type" class="form-label">Section Type</label>
                                <select name="section_type" id="section_type" class="form-select">
                                    <option value="">-- Select Section Type --</option>
                                    <option value="content" {{ old('section_type') == 'content' ? 'selected' : '' }}>
                                        Content Section
                                    </option>
                                    <option value="form" {{ old('section_type') == 'form' ? 'selected' : '' }}>
                                        Form Section
                                    </option>
                                </select>
                            </div>
                            <div class="col-12 mb-3">
                                <label for="section-order" class="form-label">Section Order</label>
                                <input type="number" class="form-control form-control-sm" id="section-order"
                                    placeholder="Enter Section Order">
                            </div>
                            <div class="col-12 mb-3">
                                {{-- <label class="form-label d-block">Is Listable</label> --}}
                                <div class="form-check form-switch">
                                    <input class="form-check-input const-control-d-toggle" type="checkbox"
                                        id="is-listable">
                                    <label class="form-check-label" for="is-listable">List view section</label>
                                </div>
                            </div>

                            <div class="const-control-d-grp const-section-relation-group d-none">
                                <div class="row gx-2">
                                    <div class="col-12 mb-3">
                                        <label for="related_page_id" class="form-label">Related Module Section</label>
                                        <select name="related_page_id" id="related_page_id" class="form-select">
                                            <option value="">-- Select Related Section --</option>
                                            @foreach ($relatedPages as $relatedPage)
                                                <optgroup label="{{ $relatedPage->name }}">
                                                    @foreach ($relatedPage->sectionList as $sectionId => $sectionName)
                                                        <option value="{{ $sectionId }}"
                                                            {{ old('related_section_id') == $sectionId ? 'selected' : '' }}>
                                                            {{ $sectionName }}
                                                        </option>
                                                    @endforeach
                                                </optgroup>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row gx-2">
                                    <div class="col-12 mb-3">
                                        <label for="relation_type" class="form-label">Relationship</label>
                                        <select name="relation_type" id="relation_type" class="form-select">
                                            <option value="">-- Select Relation Type --</option>
                                            @foreach ($pageRelations as $direction => $relations)
                                                <optgroup
                                                    label="{{ ucfirst($direction) }} ({{ $direction == 'child' ? 'Parent' : 'Child' }})">
                                                    @foreach ($relations as $relationKey => $eloquentMethod)
                                                        <option value="{{ $relationKey }}"
                                                            {{ old('relation', $page->relation ?? '') == $relationKey ? 'selected' : '' }}
                                                            data-direction="{{ $direction }}">
                                                            {{ str_replace('_', ' ', ucwords($relationKey)) }}
                                                        </option>
                                                    @endforeach

                                                </optgroup>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 text-end">
                                <button class="btn btn-success btn-sm mt-3" id="add-section" type="button">Add Section
                                </button>
                                <button class="btn btn-danger btn-sm mt-3" id="cancel" type="button"
                                    style="display: none">Cancel
                                </button>
                                <button class="btn btn-success btn-sm mt-3" id="update-section" style="display: none"
                                    type="button">Update Section
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card mb-3">
                        <div class="card-header bg-body-tertiary">
                            <h6 class="mb-0">Field Settings</h6>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-around gap-3">
                                <div class="field-palette-item border border-2 rounded
                                    px-3 py-2 text-center fw-semibold text-secondary
                                    bg-light cursor-pointer"
                                    draggable="true" data-field-type="input">
                                    Input
                                </div>

                                <div class="field-palette-item border border-2 rounded
                                    px-3 py-2 text-center fw-semibold text-secondary
                                    bg-light cursor-pointer"
                                    draggable="true" data-field-type="textarea">
                                    Textarea
                                </div>

                                <div class="field-palette-item border border-2 rounded
                                    px-3 py-2 text-center fw-semibold text-secondary
                                    bg-light cursor-pointer"
                                    draggable="true" data-field-type="image">
                                    File
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-body">
                <div class="row justify-content-between align-items-center">
                    <div class="col-md">
                        <h5 class="mb-2 mb-md-0">You're almost done!</h5>
                    </div>
                    <div class="col-auto">
                        <a href="{{ route('admin.cms-builder.pages.index') }}"
                            class="btn btn-link text-secondary p-0 me-3 fw-medium">Discard</a>
                        <button class="btn btn-primary" type="submit">Update Module</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@push('custom-script')
    <script>
        $(document).ready(function() {

            const example1 = document.getElementById('accordionExample');

            let sectionCount = {{ $page->sections->count() }};
            let fieldCounters = {};

            @foreach ($page->sections as $section)
                fieldCounters['section-{{ $section->id }}'] = {{ $section->fields->count() }};
            @endforeach

            $('#add-section').on('click', function() {
                const pageName = $('#page-name').val();
                const sectionName = $('#section-name').val();
                const sectionOrder = $('#section-order').val();
                const sectionType = $('#section_type').val();
                const relatedPageId = $('#related_page_id').val();
                const relationType = $('#relation_type').val();
                const isListable = $('#is-listable').is(':checked') ? 1 : 0;


                if (!pageName || !sectionName || !sectionOrder || !sectionType) {
                    alert('Please fill in all section details before adding.');
                    return;
                }

                sectionCount++;

                const sectionAccordionId = `section-new-${sectionCount}`;
                fieldCounters[sectionAccordionId] = 0;

                const sectionAccordion = `
                <div class="accordion-item mb-2" id="${sectionAccordionId}">
                    <h2 class="accordion-header const-accordion tb-reltv" id="heading-${sectionAccordionId}">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                            data-bs-target="#${sectionAccordionId}-collapse" aria-expanded="true" aria-controls="${sectionAccordionId}-collapse">
                            ${sectionName} - Order: ${sectionOrder}
                        </button>
                        <div class="btn-group ms-2">
                            <button class="btn btn-warning btn-sm" type="button" onclick="editSection('${sectionAccordionId}')"><i class="far fa-edit"></i>Edit</button>
                            <button class="btn btn-info btn-sm" type="button" onclick="duplicateSection('${sectionAccordionId}')" title="Duplicate Section"><i class="far fa-copy"></i></button>
                            <button class="btn btn-danger btn-sm" type="button" onclick="removeSection('${sectionAccordionId}')"><i class="fas fa-trash-alt"></i>Remove</button>
                        </div>
                    </h2>
                    <div class="accordion-collapse collapse" id="${sectionAccordionId}-collapse" aria-labelledby="heading-${sectionAccordionId}"
                        data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                            <input type="hidden" name="section[${sectionAccordionId}][data][id]" class="const-section-id" value="${sectionAccordionId}">
                            <input type="hidden" name="section[${sectionAccordionId}][data][name]" class="const-section-name" value="${sectionName}">
                            <input type="hidden" name="section[${sectionAccordionId}][data][order]" class="const-section-order" value="${sectionOrder}">
                            <input type="hidden" name="section[${sectionAccordionId}][data][section_type]" class="const-section-type" value="${sectionType}">
                            <input type="hidden" name="section[${sectionAccordionId}][data][is_listable]" class="const-section-listable" value="${isListable}">
                            <input type="hidden" name="section[${sectionAccordionId}][data][related_section_id]" class="const-section-related_section_id" value="${relatedPageId}">
                            <input type="hidden" name="section[${sectionAccordionId}][data][relation]" class="const-section-relation" value="${relationType}">
                            <p><strong>Selected Type:</strong> ${sectionType === 'form' ? 'Form Section' : 'Content Section'}</p>
                            <p><strong>Listable:</strong> ${isListable ? 'Yes' : 'No'}</p>
                            <div class="row gx-2" id="fields-container-${sectionAccordionId}"></div>
                            <button class="btn btn-secondary btn-sm mt-2" type="button"
                                onclick="addFieldToSection('${sectionAccordionId}')">Add Field to Section</button>
                        </div>
                    </div>
                </div>`;

                $('#accordionExample').append(sectionAccordion);

                $('#section-name').val('');
                $('#section-order').val('');
                $('#section_type').val('');
                $('#is-listable').prop('checked', false);

                reorderSectionsByOrder();
            });

            // window.addFieldToSection = function(sectionId) {
            //     const fieldName = $('#field-name').val().trim();
            //     const fieldType = $('#field-type').val();
            //     const fieldMultiple = $('#field-multiple').is(':checked') ? 'on' : 'off';

            //     if (!fieldName || !fieldType) {
            //         alert('Please provide both field name and type.');
            //         return;
            //     }

            //     const fieldIndex = fieldCounters[sectionId] || 0;
            //     fieldCounters[sectionId] = fieldIndex + 1;

            //     const fieldHTML = generateFieldHTML(sectionId, fieldType, fieldName, fieldMultiple, fieldIndex);
            //     const fieldsContainer = $(`#fields-container-${sectionId}`);

            //     const fieldItem = `
        // <div class="d-flex justify-content-between">
        //     <div class="col-12 mb-3 field-item" data-field-index="${fieldIndex}">

        //         ${fieldHTML}
        //         <button type="button" class="btn btn-link text-danger btn-sm" onclick="removeField(this)">Remove Field</button>
        //     </div>
        //     </div>`;

            //     fieldsContainer.append(fieldItem);

            //     $('#field-name').val('');
            //     $('#field-type').val('input');
            //     $('#field-multiple').prop('checked', false);
            // };

            function addFieldToSection(sectionId, fieldType) {

                const container = document.querySelector('#fields-container-' + sectionId);
                const index = container.querySelectorAll('.field-item').length;

                const requiredId = `field-required-${sectionId}-${index}`;
                const multipleId = `field-multiple-${sectionId}-${index}`;

                let fieldHtml = `
                    <div class="col-12 mb-3 field-item" data-field-index="${index}">
                        <div class="d-flex justify-content-between align-items-start">
                            <input type="text"
                                name="section[${sectionId}][fields][${index}][field_name]"
                                class="form-control form-control-sm editable-field w-50 mb-2"
                                placeholder="Add Field Name">

                            <div class="d-flex align-items-center gap-3">

                                <!-- Required -->
                                <div class="form-check form-switch d-flex align-items-center gap-1 m-0">
                                    <input class="form-check-input"
                                        type="checkbox"
                                        id="${requiredId}"
                                        name="section[${sectionId}][fields][${index}][is_required]"
                                        value="1">
                                    <label class="form-check-label mb-0" for="${requiredId}">
                                        Required
                                    </label>
                                </div>

                                <!-- Multiple -->
                                <div class="form-check form-switch d-flex align-items-center gap-1 m-0">
                                    <input class="form-check-input"
                                        type="checkbox"
                                        id="${multipleId}"
                                        name="section[${sectionId}][fields][${index}][is_multiple]"
                                        value="1">
                                    <label class="form-check-label mb-0" for="${multipleId}">
                                        Multiple values
                                    </label>
                                </div>

                                <!-- Delete -->
                                <button type="button"
                                    class="btn btn-link text-danger btn-sm p-0"
                                    onclick="removeField(this)">
                                    <i class="fas fa-trash-alt"></i>
                                </button>

                            </div>
                        </div>

                        <input type="hidden"
                            name="section[${sectionId}][fields][${index}][field_type]"
                            value="${fieldType}">
                `;

                if (fieldType === 'input') {
                    fieldHtml += `<input class="form-control form-control-sm mt-2" placeholder="Text" type="text">`;
                }

                if (fieldType === 'textarea') {
                    fieldHtml += `<textarea class="form-control form-control-sm mt-2" placeholder="Textarea" rows="3"></textarea>`;
                }

                if (fieldType === 'image') {
                    fieldHtml += `<input class="form-control form-control-sm mt-2" type="file">`;
                }

                fieldHtml += `</div>`;

                container.insertAdjacentHTML('beforeend', fieldHtml);
            }



            function generateFieldHTML(sectionId, fieldType, fieldName, fieldMultiple, fieldIndex) {
                const fieldMultipleChecked = fieldMultiple == 'on' ? 'checked' : '';

                const baseInputs = `
            <div class="d-flex justify-content-between">
                <input type="text" name="section[${sectionId}][fields][${fieldIndex}][field_name]" class='form-control-sm no-border' value="${fieldName}">
                <div class="d-flex justify-content-end form-check form-switch mb-0 lh-1">
                    <input class="form-check-input" type="checkbox" id="field-multiple-${fieldIndex}" name="section[${sectionId}][fields][${fieldIndex}][is_multiple]"
                    value="${fieldMultiple}" ${fieldMultipleChecked}>
                    <label class="form-check-label mb-0" for="field-multiple">Multiple values
                    </label>
                </div>

                </div>

                <input type="hidden" name="section[${sectionId}][fields][${fieldIndex}][field_type]" value="${fieldType}">
            `;

                switch (fieldType) {
                    case 'input':
                        return `
                        ${baseInputs}
                        <input class="form-control form-control-sm" type="text" placeholder="${fieldName}" name="section[${sectionId}][fields][${fieldIndex}][value]">
                    `;
                    case 'image':
                        return `
                        ${baseInputs}
                        <input class="form-control form-control-sm" type="file" name="section[${sectionId}][fields][${fieldIndex}][value]">
                    `;
                    case 'textarea':
                        return `
                        ${baseInputs}
                        <textarea class="form-control form-control-sm" rows="3" placeholder="${fieldName}" name="section[${sectionId}][fields][${fieldIndex}][value]"></textarea>
                    `;
                    default:
                        return baseInputs;
                }
            }

            window.removeField = function(button) {
                $(button).closest('.col-12').remove();
            };

            window.duplicateSection = function(sectionId) {
                const originalSection = $(`#${sectionId}`);

                const sectionName = originalSection.find('.const-section-name').val();
                const sectionOrder = originalSection.find('.const-section-order').val();
                const sectionType = originalSection.find('.const-section-type').val();
                const isListable = originalSection.find('.const-section-listable').val();
                const fieldsContainer = originalSection.find(`#fields-container-${sectionId}`);

                sectionCount++;
                const newSectionId = `section-new-${sectionCount}`;

                const newSectionName = `${sectionName} (Copy)`;

                const sections = $('#accordionExample .accordion-item');
                let maxOrder = 0;
                sections.each(function() {
                    const order = parseInt($(this).find('.const-section-order').val(), 10);
                    if (order > maxOrder) maxOrder = order;
                });
                const newSectionOrder = maxOrder + 1;

                fieldCounters[newSectionId] = 0;

                let fieldsHTML = '';
                const fieldItems = fieldsContainer.find('.field-item');
                if (fieldItems.length > 0) {
                    fieldItems.each(function() {
                        const fieldIndex = fieldCounters[newSectionId];
                        fieldCounters[newSectionId]++;

                        const fieldNameInput = $(this).find('input[name*="[field_name]"]');
                        const fieldName = fieldNameInput.val() || fieldNameInput.attr('placeholder') ||
                            'Field';
                        const fieldType = $(this).find('input[name*="[field_type]"]').val() || 'input';
                        const fieldMultiple = $(this).find('input[name*="[is_multiple]"]').is(
                            ':checked') ? 'on' : 'off';
                        const fieldValue = $(this).find('input[type="text"], textarea').val() || '';

                        const fieldMultipleChecked = fieldMultiple == 'on' ? 'checked' : '';

                        fieldsHTML +=
                            `
                    <div class="col-12 mb-3 field-item" data-field-index="${fieldIndex}">
                        <div class="d-flex justify-content-between">
                            <input type="text" name="section[${newSectionId}][fields][${fieldIndex}][field_name]" class='form-control-sm no-border' value="${fieldName}">
                            <div class="d-flex justify-content-end form-check form-switch mb-0 lh-1">
                                <input class="form-check-input" type="checkbox" id="field-multiple-${fieldIndex}-${newSectionId}"
                                    name="section[${newSectionId}][fields][${fieldIndex}][is_multiple]" value="1" ${fieldMultipleChecked}>
                                <label class="form-check-label mb-0" for="field-multiple-${fieldIndex}-${newSectionId}">Multiple values</label>
                            </div>
                        </div>
                        <input type="hidden" name="section[${newSectionId}][fields][${fieldIndex}][field_type]" value="${fieldType}">`;

                        if (fieldType === 'input') {
                            fieldsHTML +=
                                `<input class="form-control form-control-sm" type="text" placeholder="${fieldName}" name="section[${newSectionId}][fields][${fieldIndex}][value]" value="${fieldValue}">`;
                        } else if (fieldType === 'textarea') {
                            fieldsHTML +=
                                `<textarea class="form-control form-control-sm" rows="3" placeholder="${fieldName}" name="section[${newSectionId}][fields][${fieldIndex}][value]">${fieldValue}</textarea>`;
                        } else if (fieldType === 'image') {
                            fieldsHTML +=
                                `<input class="form-control form-control-sm" type="file" name="section[${newSectionId}][fields][${fieldIndex}][value]">`;
                        }

                        fieldsHTML += `
                        <button type="button" class="btn btn-link text-danger btn-sm" onclick="removeField(this)">Remove Field</button>
                    </div>`;
                    });
                }

                const newSectionHTML = `
                <div class="accordion-item mb-2" id="${newSectionId}">
                    <h2 class="accordion-header const-accordion tb-reltv" id="heading-${newSectionId}">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                            data-bs-target="#${newSectionId}-collapse" aria-expanded="false" aria-controls="${newSectionId}-collapse">
                            ${newSectionName} - Order: ${newSectionOrder}
                        </button>
                        <div class="btn-group ms-2">
                            <button class="btn btn-warning btn-sm" type="button" onclick="editSection('${newSectionId}')" title="Edit Section"><i class="far fa-edit"></i></button>
                            <button class="btn btn-info btn-sm" type="button" onclick="duplicateSection('${newSectionId}')" title="Duplicate Section"><i class="far fa-copy"></i></button>
                            <button class="btn btn-danger btn-sm" type="button" onclick="removeSection('${newSectionId}')" title="Delete Section"><i class="fas fa-trash-alt"></i></button>
                        </div>
                    </h2>
                    <div class="accordion-collapse collapse" id="${newSectionId}-collapse" aria-labelledby="heading-${newSectionId}"
                        data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                            <input type="hidden" name="section[${newSectionId}][data][id]" class="const-section-id" value="${newSectionId}">
                            <input type="hidden" name="section[${newSectionId}][data][name]" class="const-section-name" value="${newSectionName}">
                            <input type="hidden" name="section[${newSectionId}][data][order]" class="const-section-order" value="${newSectionOrder}">
                            <input type="hidden" name="section[${newSectionId}][data][section_type]" class="const-section-type" value="${sectionType}">
                            <input type="hidden" name="section[${newSectionId}][data][is_listable]" class="const-section-listable" value="${isListable}">
                            <p><strong>Selected Type:</strong> ${sectionType === 'form' ? 'Form Section' : 'Content Section'}</p>
                            <p><strong>Listable:</strong> ${isListable == 1 ? 'Yes' : 'No'}</p>
                            <div class="row gx-2" id="fields-container-${newSectionId}">
                                ${fieldsHTML}
                            </div>
                            <button class="btn btn-secondary btn-sm mt-2" type="button"
                                onclick="addFieldToSection('${newSectionId}')">Add Field to Section</button>
                        </div>
                    </div>
                </div>`;

                originalSection.after(newSectionHTML);

                reorderSectionsByOrder();
            };

            $(document).on('click', '#cancel', function() {
                $('#section-name').val('');
                $('#section-order').val('');
                $('#section_type').val('');
                $('#is-listable').prop('checked', false);
                $('#update-section-id').val('');
                $('#update-section-name').text('');
                $('#add-section').show();
                $('#update-section').hide();
                $('#cancel').hide();

                $('.const-section-relation-group').toggleClass('d-none')
            });

            window.editSection = function(sectionId) {
                const sectionElement = $(`#${sectionId}`);
                const sectionName = sectionElement.find('.const-section-name').val();
                const sectionOrder = sectionElement.find('.const-section-order').val();
                const sectionType = sectionElement.find('.const-section-type').val();
                const isListable = sectionElement.find('.const-section-listable').val();
                const relatedPageId = sectionElement.find('.const-section-related_section_id').val();
                const relationType = sectionElement.find('.const-section-relation').val();


                $('#section-name').val(sectionName);
                $('#section-order').val(sectionOrder);
                $('#section_type').val(sectionType);
                $('#related_page_id').val(relatedPageId);
                $('#relation_type').val(relationType);
                $('#is-listable').prop('checked', isListable == 1);

                $('#update-section-id').val(sectionId);
                $('#update-section-name').text(sectionName);

                $('#add-section').hide();
                $('#update-section').show();
                $('#cancel').show();

                if ($('.const-control-d-toggle').is(':checked')) {
                    $('.const-section-relation-group').toggleClass('d-none')
                }
            };

            window.removeSection = function(sectionId) {
                $(`#${sectionId}`).remove();
                delete fieldCounters[sectionId];
                reorderSectionsByOrder();
            };

            $(document).on('click', '#update-section', function() {
                const sectionNameUpdated = $('#section-name').val();
                const sectionTypeUpdated = $('#section_type').val();
                const sectionOrderUpdated = $('#section-order').val();
                const sectionIsListableUpdated = $('#is-listable').is(':checked') ? 1 : 0;
                const sectionId = $('#update-section-id').val();
                const relatedPageId = $('#related_page_id').val();
                const relationType = $('#relation_type').val();

                const sectionElement = $(`#${sectionId}`);
                sectionElement.find('.const-section-name').val(sectionNameUpdated);
                sectionElement.find('.const-section-order').val(sectionOrderUpdated);
                sectionElement.find('.const-section-type').val(sectionTypeUpdated);
                sectionElement.find('.const-section-listable').val(sectionIsListableUpdated);
                sectionElement.find('.const-section-related_section_id').val(relatedPageId);
                sectionElement.find('.const-section-relation').val(relationType);

                console.log(relatedPageId, relationType)

                sectionElement.find('.accordion-button').text(
                    `${sectionNameUpdated} - Order: ${sectionOrderUpdated}`);

                const sectionTypeText = sectionTypeUpdated === 'form' ? 'Form Section' : 'Content Section';
                const listableText = sectionIsListableUpdated ? 'Yes' : 'No';
                sectionElement.find('p strong').parent().html(
                    `<strong>Selected Type:</strong> ${sectionTypeText}`);
                sectionElement.find('.accordion-body').find('p').eq(1).html(
                    `<strong>Listable:</strong> ${listableText}`);

                $('#update-section-id').val('');
                $('#update-section-name').text('');
                $('#section-name').val('');
                $('#section-order').val('');
                $('#section_type').val('');
                $('#related_page_id').val('');
                $('#relation_type').val('');
                $('#is-listable').prop('checked', false);

                $('#add-section').show();
                $('#update-section').hide();
                $('#cancel').hide();

                reorderSectionsByOrder();
                $('.const-section-relation-group').toggleClass('d-none')
            });

            function reorderSectionsByOrder() {
                const sections = $('#accordionExample .accordion-item');

                sections.sort(function(a, b) {
                    const orderA = parseInt($(a).find('.const-section-order').val(), 10);
                    const orderB = parseInt($(b).find('.const-section-order').val(), 10);
                    return orderA - orderB;
                });

                $('#accordionExample').html(sections);

                $('#accordionExample .accordion-item').each(function(index) {
                    const btn = $(this).find('.accordion-button');
                    const name = $(this).find('.const-section-name').val();
                    const orderInput = $(this).find('.const-section-order');
                    orderInput.val(index + 1);
                    btn.text(`${name} - Order: ${index + 1}`);
                });
            }

            new Sortable(example1, {
                animation: 150,
                handle: '.accordion-header',
                ghostClass: 'blue-background-class',

                onEnd: function(evt) {
                    const sections = document.querySelectorAll('#accordionExample .accordion-item');

                    sections.forEach((section, index) => {
                        const btn = section.querySelector('.accordion-button');
                        const name = section.querySelector('.const-section-name').value;
                        btn.textContent = `${name} - Order: ${index + 1}`;
                        const orderInput = section.querySelector('.const-section-order');
                        orderInput.value = index + 1;
                    });
                }
            });

            document.querySelectorAll('.field-palette-item').forEach(item => {
                item.addEventListener('dragstart', function(e) {
                    e.dataTransfer.setData(
                        'fieldType',
                        this.dataset.fieldType
                    );
                });
            });

            document.querySelectorAll('.fields-drop-zone').forEach(zone => {

                zone.addEventListener('dragover', function(e) {
                    e.preventDefault();
                    this.classList.add('drag-active');
                });

                zone.addEventListener('dragleave', function() {
                    this.classList.remove('drag-active');
                });

                zone.addEventListener('drop', function(e) {
                    e.preventDefault();
                    this.classList.remove('drag-active');

                    const fieldType = e.dataTransfer.getData('fieldType');
                    const sectionId = this.dataset.sectionId;

                    addFieldToSection(sectionId, fieldType);
                });

            });



        });
    </script>
@endpush
