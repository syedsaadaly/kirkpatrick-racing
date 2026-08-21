@extends('admin.layouts.app')
@section('content')

    @include('admin.layouts.partials.topbar')
    <div class="card mb-3">
        <div class="card-body">
            <div class="row flex-between-center">
                <div class="col-md">
                    <h5 class="mb-2 mb-md-0">Add a page</h5>
                </div>
                <div class="col-auto">
                    <a href="{{ route('admin.cms-builder.pages.index') }}" class="btn btn-link text-secondary p-0 me-3 fw-medium">Discard</a>
                    <button class="btn btn-primary" role="button">Add page</button>
                </div>
            </div>
        </div>
    </div>

    <form action="{{ route('admin.cms-builder.pages.store') }}" method="POST">
        @csrf
        <div class="row g-0">
            <div class="col-lg-8 pe-lg-2">
                <div class="card mb-3 mb-lg-0">
                    <div class="card-header">
                        <h5 class="mb-0">Page Sections</h5>
                    </div>
                    <div class="card-body bg-body-tertiary">
                        <div class="accordion" id="accordionExample"></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 ps-lg-2">
                <div class="sticky-sidebar">
                    <div class="card mb-3">
                        <div class="card-header bg-body-tertiary">
                            <h6 class="mb-0">Page Settings</h6>
                        </div>
                        <div class="card-body">
                            <div class="row gx-2">
                                <div class="col-12 mb-3">
                                    <label for="page-name" class="form-label">Page Name</label>
                                    <input type="text" class="form-control form-control-sm" id="page-name"
                                        name="page[name]"
                                        placeholder="Enter Page Name">

                                    <div class="form-check form-switch mt-3">
                                        <input class="form-check-input" name="is_cms" type="checkbox" id="is-cms" checked>
                                        <label class="form-check-label" for="is-cms">Is this a CMS Page?</label>
                                    </div>

                                    <div class="mt-3" id="icon-selection-section" style="display: none;">
                                        <label class="form-label d-flex justify-content-between align-items-center">
                                            <span>Select Icon</span>
                                            <small class="text-muted">Scroll horizontally to see more icons →</small>
                                        </label>

                                        <div class="icon-scroll-container mb-3">
                                            <div class="icon-scroll-wrapper" id="icon-scroll-wrapper">
                                            </div>
                                            <div class="scroll-arrows">
                                                <button type="button" class="scroll-arrow scroll-left">
                                                    <i class="fas fa-chevron-left"></i>
                                                </button>
                                                <button type="button" class="scroll-arrow scroll-right">
                                                    <i class="fas fa-chevron-right"></i>
                                                </button>
                                            </div>
                                        </div>

                                        <input type="hidden" name="icon" id="selected-icon">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card mb-3">
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
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="is-listable">
                                    <label class="form-check-label" for="is-listable">List view section</label>
                                </div>
                            </div>
                            <div class="col-12 text-end">
                                <button class="btn btn-success btn-sm mt-3" id="add-section" type="button">Add Section
                                </button>
                                <button class="btn btn-danger btn-sm mt-3" id="cancel" type="button" style="display: none">Cancel
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
                            <div class="col-12 mb-3">
                                <label for="field-name" class="form-label">Field Name</label>
                                <input type="text" class="form-control form-control-sm" id="field-name"
                                       placeholder="Field Name">
                            </div>
                            <div class="col-12 mb-3">
                                <label for="field-type" class="form-label">Select Field Type</label>
                                <select class="form-select" id="field-type">
                                    <option value="input">Input Text</option>
                                    <option value="image">Image</option>
                                    <option value="textarea">Textarea</option>
                                </select>
                            </div>
                            <div class="col-12 mb-3">
                                <div class="form-check form-switch mb-0 lh-1">
                                    <input class="form-check-input" type="checkbox" id="field-multiple">
                                    <label class="form-check-label mb-0" for="field-multiple">Multiple values
                                    </label>
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
                        <a href="{{ route('admin.cms-builder.pages.index') }}" class="btn btn-link text-secondary p-0 me-3 fw-medium">Discard</a>
                        <button class="btn btn-primary" type="submit">Save Page</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

@endsection

@push('custom-script')
<script>
    $(document).ready(function () {

        const example1 = document.getElementById('accordionExample');
        let sectionCount = 0;

        $('#add-section').on('click', function () {
            const pageName = $('#page-name').val();
            const sectionName = $('#section-name').val();
            const sectionOrder = $('#section-order').val();
            const sectionType = $('#section_type').val();
            const isListable = $('#is-listable').is(':checked') ? 1 : 0;

            if (!pageName || !sectionName || !sectionOrder || !sectionType) {
                alert('Please fill in all section details before adding.');
                return;
            }

            sectionCount++;

            const sectionAccordionId = `section-${sectionCount}`;
            const sectionAccordion = `
                            <div class="accordion-item mb-2" id="${sectionAccordionId}">
                                <h2 class="accordion-header const-accordion tb-reltv" id="heading-${sectionAccordionId}">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#${sectionAccordionId}-collapse" aria-expanded="true" aria-controls="${sectionAccordionId}-collapse">
                                        ${sectionName} - Order: ${sectionOrder}
                                    </button>
                                    <div class="btn-group ms-2">
                                        <button class="btn btn-warning btn-sm" type="button" onclick="editSection('${sectionAccordionId}')"><i class="far fa-edit"></i></button>
                                        <button class="btn btn-info btn-sm" type="button" onclick="duplicateSection('${sectionAccordionId}')"><i class="fas fa-copy"></i></button>
                                        <button class="btn btn-danger btn-sm" type="button" onclick="removeSection('${sectionAccordionId}')"><i class="fas fa-trash-alt"></i></button>
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

        window.addFieldToSection = function (sectionId) {
                const fieldName = $('#field-name').val().trim();
                const fieldType = $('#field-type').val();
                const fieldMultiple = $('#field-multiple').is(':checked') ? 'on' : 'off';

                if (!fieldName || !fieldType) {
                    alert('Please provide both field name and type.');
                    return;
                }

                const fieldHTML = generateFieldHTML(sectionId, fieldType, fieldName, fieldMultiple);
                const fieldsContainer = $(`#fields-container-${sectionId}`);

                const fieldItem = `
                    <div class="col-12 mb-3">
                        <label class="form-label" for="${fieldName}">${fieldName}</label>
                        ${fieldHTML}
                        <button type="button" class="btn btn-link text-danger btn-sm" onclick="removeField(this)">Remove Field</button>
                    </div>`;

                fieldsContainer.append(fieldItem);

                $('#field-name').val('');
                $('#field-type').val('input');
                $('#field-multiple').prop('checked', false);
            };

            function generateFieldHTML(sectionId, fieldType, fieldName, fieldMultiple) {
                const baseInputs = `
                    <input type="hidden" value="${fieldName}" name="section[${sectionId}][fields][field_name][]">
                    <input type="hidden" value="${fieldType}" name="section[${sectionId}][fields][field_type][]">
                    <input type="hidden" value="${fieldMultiple}" name="section[${sectionId}][fields][is_multiple][]">
                `;

                switch (fieldType) {
                    case 'input':
                        return `
                            ${baseInputs}
                            <input class="form-control form-control-sm" type="text" placeholder="${fieldName}" name="section[${sectionId}][fields][value][]">
                        `;
                    case 'image':
                        return `
                            ${baseInputs}
                            <input class="form-control form-control-sm" type="file" name="section[${sectionId}][fields][value][]">
                        `;
                    case 'textarea':
                        return `
                            ${baseInputs}
                            <textarea class="form-control form-control-sm" rows="3" placeholder="${fieldName}" name="section[${sectionId}][fields][value][]"></textarea>
                        `;
                    default:
                        return baseInputs;
                }
            }



        window.removeField = function (button) {
            $(button).closest('.col-12').remove();
        };

        window.duplicateSection = function (sectionId) {
            const originalSection = $(`#${sectionId}`);

            const sectionName = originalSection.find('.const-section-name').val();
            const sectionOrder = originalSection.find('.const-section-order').val();
            const sectionType = originalSection.find('.const-section-type').val();
            const isListable = originalSection.find('.const-section-listable').val();
            const fieldsContainer = originalSection.find(`#fields-container-${sectionId}`);

            sectionCount++;
            const newSectionId = `section-${sectionCount}`;

            const newSectionName = `${sectionName} (Copy)`;

            const sections = $('#accordionExample .accordion-item');
            let maxOrder = 0;
            sections.each(function() {
                const order = parseInt($(this).find('.const-section-order').val(), 10);
                if (order > maxOrder) maxOrder = order;
            });
            const newSectionOrder = maxOrder + 1;

            let fieldsHTML = '';
            if (fieldsContainer.children().length > 0) {
                fieldsContainer.children().each(function() {
                    const fieldLabel = $(this).find('label').text();
                    const fieldType = $(this).find('input[name$="[field_type][]"]').val();
                    const fieldMultiple = $(this).find('input[name$="[is_multiple][]"]').val();
                    const fieldValue = $(this).find('input[type="text"], textarea, input[type="file"]').val() || '';

                    fieldsHTML += `
                    <div class="col-12 mb-3">
                        <label class="form-label" for="${fieldLabel}">${fieldLabel}</label>
                        <input type="hidden" value="${fieldLabel}" name="section[${newSectionId}][fields][field_name][]">
                        <input type="hidden" value="${fieldType}" name="section[${newSectionId}][fields][field_type][]">
                        <input type="hidden" value="${fieldMultiple}" name="section[${newSectionId}][fields][is_multiple][]">`;

                    if (fieldType === 'input') {
                        fieldsHTML += `<input class="form-control form-control-sm" type="text" placeholder="${fieldLabel}" name="section[${newSectionId}][fields][value][]" value="${fieldValue}">`;
                    } else if (fieldType === 'textarea') {
                        fieldsHTML += `<textarea class="form-control form-control-sm" rows="3" placeholder="${fieldLabel}" name="section[${newSectionId}][fields][value][]">${fieldValue}</textarea>`;
                    } else if (fieldType === 'image') {
                        fieldsHTML += `<input class="form-control form-control-sm" type="file" name="section[${newSectionId}][fields][value][]">`;
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

        $(document).on('click', '#cancel', function(){
            $('#section-name').val('');
            $('#section-order').val('');
            $('#section_type').val('');
            $('#is-listable').prop('checked',false);
            $('#update-section-id').val('');
            $('#update-section-name').text('');
            $('#add-section').show();
            $('#update-section').hide();
            $('#cancel').hide();
        });

        window.editSection = function (sectionId) {
            const sectionElement = $(`#${sectionId}`);
            const sectionName = sectionElement.find('.const-section-name').val()
            const sectionOrder = sectionElement.find('.const-section-order').val()
            const sectionType = sectionElement.find('.const-section-type').val()
            const isListable = sectionElement.find('.const-section-listable').val();

            $('#section-name').val(sectionName)
            $('#section-order').val(sectionOrder)
            $('#section_type').val(sectionType)
            $('#is-listable').prop('checked', isListable == 1);

            $('#update-section-id').val(sectionId)
            $('#update-section-name').val(sectionName)

            $('#add-section').hide()
            $('#update-section').show()
            $('#cancel').show()
        };

        window.removeSection = function (sectionId) {
            $(`#${sectionId}`).remove();
            reorderSectionsByOrder();
        };

        $(document).on('click', '#update-section', function () {
            const sectionNameUpdated = $('#section-name').val();
            const sectionTypeUpdated = $('#section_type').val();
            const sectionOrderUpdated = $('#section-order').val();
            const sectionIsListableUpdated = $('#is-listable').is(':checked') ? 1 : 0;
            const sectionId = $('#update-section-id').val();

            const sectionElement = $(`#${sectionId}`);
            sectionElement.find('.const-section-name').val(sectionNameUpdated);
            sectionElement.find('.const-section-order').val(sectionOrderUpdated);
            sectionElement.find('.const-section-type').val(sectionTypeUpdated);
            sectionElement.find('.const-section-listable').val(sectionIsListableUpdated);

            sectionElement.find('.accordion-button').text(`${sectionNameUpdated} - Order: ${sectionOrderUpdated}`);

            const sectionTypeText = sectionTypeUpdated === 'form' ? 'Form Section' : 'Content Section';
            const listableText = sectionIsListableUpdated ? 'Yes' : 'No';
            sectionElement.find('p strong').parent().html(`<strong>Selected Type:</strong> ${sectionTypeText}`);
            sectionElement.find('.accordion-body').find('p').eq(1).html(`<strong>Listable:</strong> ${listableText}`);

            $('#update-section-id').val('');
            $('#update-section-name').text('');
            $('#section-name').val('');
            $('#section-order').val('');
            $('#section_type').val('');
            $('#is-listable').prop('checked', false);

            $('#add-section').show();
            $('#update-section').hide();
            $('#cancel').hide();

            reorderSectionsByOrder();
        });

        function reorderSectionsByOrder() {
            const sections = $('#accordionExample .accordion-item');

            sections.sort(function (a, b) {
                const orderA = parseInt($(a).find('.const-section-order').val(), 10);
                const orderB = parseInt($(b).find('.const-section-order').val(), 10);
                return orderA - orderB;
            });

            $('#accordionExample').html(sections);

            $('#accordionExample .accordion-item').each(function (index) {
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

            onEnd: function (evt) {
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
    });
</script>
@endpush
