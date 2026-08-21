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
                    @if ($page->is_cms == 0)
                        <a href="{{ route('admin.cms-builder.page.view', $page->id) }}" class="btn btn-primary">
                            <i class="fas fa-edit me-1"></i> Edit Page Content
                        </a>
                    @endif
                    <a href="{{ route('admin.cms-builder.pages.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Back to Page Builder
                    </a>
                </div>
            </div>
        </div>
    </div>

    @include('admin.cms-builder.partial.form', [
        'action' => route('admin.cms-builder.pages.update', $page->id),
        'method' => 'PUT',
        'page' => $page,
        'relatedPages' => $relatedPages,
        'pageRelations' => $pageRelations,
        'submitButtonText' => 'Update Module',
    ])
@endsection

@push('custom-script')
    <script>
        $(document).ready(function() {
            const example1 = document.getElementById('accordionExample');
            const html = document.getElementById('page-html');
            html.classList.add('navbar-vertical-collapsed');

            let sectionCount = {{ $page->sections->count() }};
            let fieldCounters = {};

            @foreach ($page->sections as $section)
                fieldCounters['section-{{ $section->id }}'] = {{ $section->fields->count() }};
            @endforeach

            initFieldPaletteDrag();

            initDropZones();

            if (example1) {
                initSortable();
            }

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
                <div class="accordion-item mb-2 card" id="${sectionAccordionId}">
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

                            <!-- IMPORTANT: Add fields-drop-zone and drag-fields-zone to new section -->
                            <div class="fields-drop-zone" data-section-id="${sectionAccordionId}">
                                <hr class="text-200">
                                <div class="row gx-2" id="fields-container-${sectionAccordionId}"></div>
                                <div class="drag-fields-zone mt-3" data-section-id="${sectionAccordionId}">
                                    <i class="fas fa-plus-circle me-1"></i>
                                    Drag & Drop fields here
                                </div>
                            </div>
                        </div>
                    </div>
                </div>`;

                $('#accordionExample').append(sectionAccordion);

                if (example1) {
                    initSortable();
                }

                let newOrder = findOrderNumber();

                $('#section-name').val('');
                $('#section-order').val(newOrder);
                $('#section_type').val('');
                $('#is-listable').prop('checked', false);

                reorderSectionsByOrder();
            });

            function initFieldPaletteDrag() {
                document.querySelectorAll('.field-palette-item').forEach(item => {
                    item.addEventListener('dragstart', function(e) {
                        e.dataTransfer.setData('fieldType', this.dataset.fieldType);
                    });
                });
            }

            function initDropZones() {
                $(document).on('dragover', '.drag-fields-zone', function(e) {
                    e.preventDefault();
                    $(this).addClass('drag-over');
                });

                $(document).on('dragleave', '.drag-fields-zone', function() {
                    $(this).removeClass('drag-over');
                });

                $(document).on('drop', '.drag-fields-zone', function(e) {
                    e.preventDefault();
                    $(this).removeClass('drag-over');

                    const fieldType = e.originalEvent.dataTransfer.getData('fieldType');
                    const sectionId = $(this).data('section-id');

                    if (fieldType && sectionId) {
                        addFieldToSection(sectionId, fieldType);
                    }
                });
            }

            function initSortable() {
                new Sortable(example1, {
                    animation: 150,
                    handle: '.accordion-header',
                    ghostClass: 'blue-background-class',
                    onEnd: function(evt) {
                        const sections = document.querySelectorAll('#accordionExample .accordion-item');
                        sections.forEach((section, index) => {
                            const btn = section.querySelector('.accordion-button');
                            const name = section.querySelector('.const-section-name').value;
                            if (btn && name) {
                                btn.textContent = `${name} - Order: ${index + 1}`;
                            }
                            const orderInput = section.querySelector('.const-section-order');
                            if (orderInput) {
                                orderInput.value = index + 1;
                            }
                        });
                    }
                });
            }

            window.addFieldToSection = function(sectionId, fieldType) {
                const container = document.querySelector('#fields-container-' + sectionId);
                if (!container) {
                    console.error(`Container not found for section: ${sectionId}`);
                    return;
                }

                const index = container.querySelectorAll('.field-item').length;
                const hiddenId = `field-hidden-${sectionId}-${index}`;
                const requiredId = `field-required-${sectionId}-${index}`;
                const multipleId = `field-multiple-${sectionId}-${index}`;
                const relatedId = `field-related-${sectionId}-${index}`;
                const dropdownContainerId = `dropdown-container-${sectionId}-${index}`;
                let fieldHtml = `
                    <div class="col-12 mb-3 field-item card p-4 mb-4" data-field-index="${index}">
                        <div class="d-flex justify-content-between align-items-start">
                            <input type="text" name="section[${sectionId}][fields][${index}][field_name]"
                                class="form-control form-control-sm editable-field w-50 mb-2" placeholder="Add Field Name">

                            <div class="d-flex align-items-center gap-3">
                                <div class="form-check form-switch d-flex align-items-center gap-1 m-0">
                                    <input class="form-check-input" type="checkbox" id="${hiddenId}" name="section[${sectionId}][fields][${index}][is_hidden]" value="1">
                                    <label class="form-check-label mb-0" for="${hiddenId}">Hidden</label>
                                </div>
                                <div class="form-check form-switch d-flex align-items-center gap-1 m-0">
                                    <input class="form-check-input" type="checkbox" id="${requiredId}" name="section[${sectionId}][fields][${index}][is_required]" value="1">
                                    <label class="form-check-label mb-0" for="${requiredId}">Required</label>
                                </div>
                                <div class="form-check form-switch d-flex align-items-center gap-1 m-0">
                                    <input class="form-check-input" type="checkbox" id="${multipleId}" name="section[${sectionId}][fields][${index}][is_multiple]" value="1">
                                    <label class="form-check-label mb-0" for="${multipleId}">Multiple</label>
                                </div>

                                ${fieldType === 'input' ? `
                                    <div class="form-check form-switch d-flex align-items-center gap-1 m-0">
                                        <input class="form-check-input is-related-toggle" type="checkbox" id="${relatedId}" 
                                            name="section[${sectionId}][fields][${index}][is_related]" value="1">
                                        <label class="form-check-label mb-0" for="${relatedId}">Is Related</label>
                                    </div>` : ''}

                                <button type="button" class="btn btn-link text-danger btn-sm p-0" onclick="removeField(this)">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                        </div>
                        <input type="hidden" name="section[${sectionId}][fields][${index}][field_type]" value="${fieldType}">
                `;

                if (fieldType === 'input') {
                    fieldHtml += `
                        <input class="form-control form-control-sm mt-2" type="text" placeholder="Text" disabled>
                        <div class="related-dropdowns mt-2 d-none align-items-center gap-3" id="dropdown-container-${sectionId}-${index}">
                            <div class="p-2 border rounded bg-light d-flex align-items-center" style="white-space: nowrap; height: 31px;">
                                <label class="small fw-bold mb-0 me-2 text-muted" style="font-size: 0.75rem;">Type:</label>
                                <div class="d-flex gap-2">
                                    <div class="form-check form-check-inline mb-0 me-0">
                                        <input class="form-check-input" type="radio" 
                                            name="section[${sectionId}][fields][${index}][link_type]" 
                                            id="link-section-${sectionId}-${index}" 
                                            value="section" checked>
                                        <label class="form-check-label small" for="link-section-${sectionId}-${index}" style="font-size: 0.75rem;">Model</label>
                                    </div>
                                    <div class="form-check form-check-inline mb-0 me-0">
                                        <input class="form-check-input" type="radio" 
                                            name="section[${sectionId}][fields][${index}][link_type]" 
                                            id="link-module-${sectionId}-${index}" 
                                            value="module">
                                        <label class="form-check-label small" for="link-module-${sectionId}-${index}" style="font-size: 0.75rem;">Module</label>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex gap-2 flex-grow-1">
                                <select class="form-select form-select-sm cms-page-select"
                                    name="section[${sectionId}][fields][${index}][related_page_id]"
                                    data-section-id="${sectionId}" 
                                    data-field-index="${index}">
                                    <option value="">-- Page --</option>
                                    @foreach ($cmsPages as $cmsPage)
                                        <option value="{{ $cmsPage->id }}">{{ $cmsPage->name }}</option>
                                    @endforeach
                                </select>
                                <select class="form-select form-select-sm cms-section-select"
                                    name="section[${sectionId}][fields][${index}][related_section_id]"
                                    data-field-index="${index}" 
                                    data-section-id="${sectionId}"
                                    data-selected-section=""
                                    disabled>
                                    <option value="">-- Section --</option>
                                </select>
                                <select class="form-select form-select-sm cms-field-select"
                                    name="section[${sectionId}][fields][${index}][related_field_id]"
                                    data-field-index="${index}" 
                                    data-section-id="${sectionId}"
                                    data-selected-field=""
                                    disabled>
                                    <option value="">-- Field --</option>
                                </select>
                            </div>
                        </div>`;
                }

                if (fieldType === 'textarea') {
                    fieldHtml += `<textarea class="form-control form-control-sm mt-2"
                        name="section[${sectionId}][fields][${index}][value]"
                        placeholder="Textarea" rows="3" disabled></textarea>`;
                }

                if (fieldType === 'image') {
                    fieldHtml += `<input class="form-control form-control-sm mt-2"
                        name="section[${sectionId}][fields][${index}][value]"
                        type="file" disabled>`;
                }
                if (fieldType === 'radio' || fieldType === 'checkbox') {
                    fieldHtml += `
                        <div class="options-wrapper mt-2 p-2 border rounded bg-light">
                            <label class="small fw-bold d-block mb-1">Manage Options:</label>
                            <div class="options-container" id="options-container-${sectionId}-${index}">
                                <div class="input-group input-group-sm mb-1">
                                    <input type="text" 
                                        name="section[${sectionId}][fields][${index}][options][]" 
                                        class="form-control" 
                                        placeholder="Option Label">
                                    <button type="button" class="btn btn-outline-danger remove-option">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                            <button type="button" 
                                class="btn btn-link btn-sm p-0 add-option-btn" 
                                data-section-id="${sectionId}" 
                                data-field-index="${index}">
                                <i class="fas fa-plus"></i> Add Option
                            </button>
                        </div>`;
                }

                fieldHtml += `</div>`;

                container.insertAdjacentHTML('beforeend', fieldHtml);
            };

            $(document).on('click', '.add-option-btn', function() {
                const sectionId = $(this).data('section-id');
                const index = $(this).data('field-index');
                const container = $(`#options-container-${sectionId}-${index}`);

                const optionHtml = `
                    <div class="input-group input-group-sm mb-1">
                        <input type="text" 
                            name="section[${sectionId}][fields][${index}][options][]" 
                            class="form-control" 
                            placeholder="Option Label">
                        <button type="button" class="btn btn-outline-danger remove-option">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>`;

                container.append(optionHtml);
            });

            $(document).on('click', '.remove-option', function() {
                const container = $(this).closest('.options-container');
                if (container.find('.input-group').length > 1) {
                    $(this).closest('.input-group').remove();
                } else {
                    alert('You must have at least one option.');
                }
            });

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
                const relatedSectionId = originalSection.find('.const-section-related_section_id').val();
                const relation = originalSection.find('.const-section-relation').val();
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

                        const originalField = $(this);
                        const fieldName = originalField.find('input[name*="[field_name]"]').val();
                        const fieldType = originalField.find('input[name*="[field_type]"]').val();
                        const isRequired = originalField.find('input[name*="[is_required]"]').is(
                            ':checked');
                        const isMultiple = originalField.find('input[name*="[is_multiple]"]').is(
                            ':checked');

                        const fieldDataValue = originalField.find('input[name*="[current_value]"]')
                        .val();

                        fieldsHTML += `
                            <div class="col-12 mb-3 field-item card p-4 mb-4" data-field-index="${fieldIndex}">
                                <div class="d-flex justify-content-between">
                                    <input type="text"
                                        name="section[${newSectionId}][fields][${fieldIndex}][field_name]"
                                        class="form-control form-control-sm editable-field w-50 mb-2"
                                        value="${fieldName}">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="form-check form-switch d-flex align-items-center gap-1 m-0">
                                            <input class="form-check-input"
                                                type="checkbox"
                                                id="field-required-${fieldIndex}-${newSectionId}"
                                                name="section[${newSectionId}][fields][${fieldIndex}][is_required]"
                                                value="1"
                                                ${isRequired ? 'checked' : ''}>
                                            <label class="form-check-label mb-0" for="field-required-${fieldIndex}-${newSectionId}">
                                                Required
                                            </label>
                                        </div>
                                        <div class="form-check form-switch d-flex align-items-center gap-1 m-0">
                                            <input class="form-check-input"
                                                type="checkbox"
                                                id="field-multiple-${fieldIndex}-${newSectionId}"
                                                name="section[${newSectionId}][fields][${fieldIndex}][is_multiple]"
                                                value="1"
                                                ${isMultiple ? 'checked' : ''}>
                                            <label class="form-check-label mb-0" for="field-multiple-${fieldIndex}-${newSectionId}">
                                                Multiple values
                                            </label>
                                        </div>
                                        <button type="button"
                                            class="btn btn-link text-danger btn-sm p-0"
                                            onclick="removeField(this)"
                                            title="Remove Field"
                                            data-bs-toggle="tooltip"
                                            data-bs-placement="top">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </div>
                                </div>
                                <input type="hidden"
                                    name="section[${newSectionId}][fields][${fieldIndex}][field_type]"
                                    value="${fieldType}">`;

                        if (fieldType === 'input') {
                            fieldsHTML += `
                                <input class="form-control form-control-sm"
                                    type="text"
                                    value=""
                                    placeholder="Text"
                                    name="section[${newSectionId}][fields][${fieldIndex}][value]"
                                    disabled>`;
                        } else if (fieldType === 'image') {
                            fieldsHTML += `
                                <input class="form-control form-control-sm"
                                    type="file"
                                    name="section[${newSectionId}][fields][${fieldIndex}][value]"
                                    disabled>`;
                            if (fieldDataValue) {
                                fieldsHTML += `
                                <small class="text-muted">Current: ${fieldDataValue}</small>
                                <input type="hidden"
                                    name="section[${newSectionId}][fields][${fieldIndex}][current_value]"
                                    value="${fieldDataValue}">`;
                            }
                        } else if (fieldType === 'textarea') {
                            fieldsHTML += `
                            <textarea class="form-control form-control-sm"
                                rows="3"
                                placeholder="Textarea"
                                name="section[${newSectionId}][fields][${fieldIndex}][value]"
                                disabled></textarea>`;
                        }

                        fieldsHTML += `
                        </div>`;
                    });
                }

                const newSectionHTML = `
                <div class="accordion-item mb-2 card" id="${newSectionId}">
                    <h2 class="accordion-header const-accordion tb-reltv" id="heading-${newSectionId}">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                            data-bs-target="#${newSectionId}-collapse" aria-expanded="false"
                            aria-controls="${newSectionId}-collapse">
                            Order: ${newSectionOrder}
                        </button>
                        <div class="d-flex justify-content-between m-2">
                            <input type="text"
                                name="section[${newSectionId}][data][name]"
                                class="form-control form-control-sm editable-field w-50 const-section-name font-13"
                                value="${newSectionName}">
                            <div>
                                ${isListable == '1' ? `
                                    <a href="#"
                                        class="btn btn-sm btn-primary">
                                        <i class="fas fa-plus me-1"></i> Add Items
                                    </a>
                                    ` : ''}
                                <div class="btn-group ms-2">
                                    <button class="btn btn-secondary btn-sm" type="button"
                                        title="Edit this section" data-bs-toggle="tooltip"
                                        data-bs-placement="top"
                                        onclick="editSection('${newSectionId}')">
                                        <i class="far fa-edit"></i>
                                    </button>
                                    <button class="btn btn-success btn-sm" type="button"
                                        onclick="duplicateSection('${newSectionId}')"
                                        title="Duplicate Section" data-bs-toggle="tooltip"
                                        data-bs-placement="top">
                                        <i class="far fa-copy"></i>
                                    </button>
                                    <button class="btn btn-danger btn-sm" type="button"
                                        title="Remove this section" data-bs-toggle="tooltip"
                                        data-bs-placement="top"
                                        onclick="removeSection('${newSectionId}')">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </h2>
                    <div class="accordion-collapse collapse" id="${newSectionId}-collapse"
                        aria-labelledby="heading-${newSectionId}"
                        data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                            <input type="hidden"
                                name="section[${newSectionId}][data][id]"
                                class="const-section-id"
                                value="${newSectionId}">
                            <input type="hidden"
                                name="section[${newSectionId}][data][order]"
                                class="const-section-order"
                                value="${newSectionOrder}">
                            <input type="hidden"
                                name="section[${newSectionId}][data][section_type]"
                                class="const-section-type"
                                value="${sectionType}">
                            <input type="hidden"
                                name="section[${newSectionId}][data][is_listable]"
                                class="const-section-listable"
                                value="${isListable}">
                            <input type="hidden"
                                name="section[${newSectionId}][data][related_section_id]"
                                class="const-section-related_section_id"
                                value="${relatedSectionId}">
                            <input type="hidden"
                                name="section[${newSectionId}][data][relation]"
                                class="const-section-relation"
                                value="${relation}">
                            <div class="card p-4 alert alert-primary">
                                <p>
                                    <strong>Selected Type:</strong>&nbsp;
                                    ${sectionType === 'form' ? 'Form Section' : 'Content Section'}
                                </p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <p class="mb-0">
                                        <strong>Listable:</strong>
                                        <span class="badge rounded-pill badge-subtle-danger">
                                            ${isListable == '1' ? 'Yes' : 'No'}
                                        </span>
                                    </p>
                                </div>
                            </div>
                            <div class="fields-drop-zone"
                                data-section-id="${newSectionId}">
                                <hr class="text-200">
                                <div class="row gx-2"
                                    id="fields-container-${newSectionId}">
                                    ${fieldsHTML}
                                </div>
                                <div class="drag-fields-zone mt-3"
                                    data-section-id="${newSectionId}">
                                    <i class="fas fa-plus-circle me-1"></i>
                                    Drag & Drop fields here
                                </div>
                            </div>
                        </div>
                    </div>
                </div>`;

                originalSection.after(newSectionHTML);

                $('[data-bs-toggle="tooltip"]').tooltip();

                reorderSectionsByOrder();

                $(`#${sectionId}-collapse`).collapse('hide');
                $(`#${newSectionId}-collapse`).collapse('show');
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
                let newOrder = findOrderNumber();
                $('#section-order').val(newOrder);
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

                let newOrder = findOrderNumber();

                $('#update-section-id').val('');
                $('#update-section-name').text('');
                $('#section-name').val('');
                $('#section-order').val(newOrder);
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

            function findOrderNumber() {
                const totalOrders = $('#accordionExample .accordion-item').length;
                return totalOrders + 1;
            }

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

            $(document).on('change', '.cms-page-select', function() {
                const pageId = $(this).val();
                const sectionSelect = $(this).closest('.related-dropdowns').find('.cms-section-select');

                sectionSelect.html('<option value="">Loading...</option>').prop('disabled', true);

                if (!pageId) {
                    sectionSelect.html('<option value="">-- Select Section --</option>').prop('disabled', true);
                    return;
                }

                $.ajax({
                    url: `/admin/cms-builder/cms-pages/${pageId}/sections`,
                    method: 'GET',
                    success: function(sections) {
                        let options = '<option value="">-- Select Section --</option>';
                        sections.forEach(function(section) {
                            options += `<option value="${section.id}">${section.section_name}</option>`;
                        });
                        sectionSelect.html(options).prop('disabled', false);
                    },
                    error: function() {
                        sectionSelect.html('<option value="">Failed to load</option>');
                    }
                });
            });

            $(document).on('change', '.cms-section-select', function() {
                const sectionId = $(this).val();
                const fieldIndex = $(this).data('field-index');
                const parentSectionId = $(this).data('section-id');
                const $fieldSelect = $(`.cms-field-select[data-section-id="${parentSectionId}"][data-field-index="${fieldIndex}"]`);

                if (!sectionId) {
                    $fieldSelect.empty().append('<option value="">-- Field --</option>').prop('disabled', true);
                    return;
                }

                $.ajax({
                    url: `/admin/get-fields/${sectionId}`,
                    type: 'GET',
                    success: function(data) {
                        $fieldSelect.empty().append('<option value="">-- Field --</option>');
                        $.each(data, function(key, field) {
                            $fieldSelect.append(`<option value="${field.id}">${field.field_name}</option>`);
                        });
                        $fieldSelect.prop('disabled', false);
                    }
                });
            });

            $(document).on('change', '.is-related-toggle', function() {
                const checkbox = $(this);
                const container = checkbox.closest('.field-item').find('.related-dropdowns');

                if (checkbox.is(':checked')) {
                    container.removeClass('d-none');
                } else {
                    container.addClass('d-none');
                    container.find('select').val('').trigger('change');
                }
            });
        });
    </script>
@endpush
