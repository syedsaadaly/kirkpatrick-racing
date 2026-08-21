<form action="{{ $action }}" method="POST" enctype="multipart/form-data">
    @csrf
    @if ($method === 'PUT')
        @method('PUT')
    @endif

    <div class="row g-0">
        <div class="col-lg-8 pe-lg-2">
            <div class="card mb-3 mb-lg-0 module-scroll-card">
                <div class="card-header">
                    <h5 class="mb-0">Module Sections</h5>
                </div>
                <div class="card-body bg-body-tertiary">
                    <div class="accordion" id="accordionExample">
                        @php
                            $sectionCount = 0;
                            $fieldCounters = [];
                        @endphp

                        @if (isset($page) && $page->sections->count() > 0)
                            @foreach ($page->sections as $sectionIndex => $section)
                                @php
                                    $sectionCount++;
                                    $fieldCounters['section-' . $section->id] = $section->fields->count();
                                @endphp
                                <div class="accordion-item mb-2 card" id="section-{{ $section->id }}">
                                    <h2 class="accordion-header const-accordion tb-reltv"
                                        id="heading-section-{{ $section->id }}">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#section-{{ $section->id }}-collapse" aria-expanded="true"
                                            aria-controls="section-{{ $section->id }}-collapse">
                                            Order: {{ $section->order }}
                                        </button>
                                        <div class="d-flex justify-content-between m-2">
                                            <input type="text"
                                                name="section[section-{{ $section->id }}][data][name]"
                                                class="form-control form-control-sm editable-field w-50 const-section-name font-13"
                                                value="{{ $section->section_name }}">
                                            <div>
                                                @if ($section->is_listable)
                                                    <a href="{{ route('admin.cms-builder.pages.sections.items.create', [$page->id, $section->id]) }}"
                                                        class="btn btn-sm btn-primary">
                                                        <i class="fas fa-plus me-1"></i> Add
                                                        {{ $section->section_name }} Items
                                                    </a>
                                                @endif
                                                <div class="btn-group ms-2">
                                                    <button class="btn btn-secondary btn-sm" type="button"
                                                        title="Edit this section" data-bs-toggle="tooltip"
                                                        data-bs-placement="top"
                                                        onclick="editSection('section-{{ $section->id }}')">
                                                        <i class="far fa-edit"></i>
                                                    </button>
                                                    <button class="btn btn-success btn-sm" type="button"
                                                        onclick="duplicateSection('section-{{ $section->id }}')"
                                                        title="Duplicate Section" data-bs-toggle="tooltip"
                                                        data-bs-placement="top">
                                                        <i class="far fa-copy"></i>
                                                    </button>
                                                    <button class="btn btn-danger btn-sm" type="button"
                                                        title="Remove this section" data-bs-toggle="tooltip"
                                                        data-bs-placement="top"
                                                        onclick="removeSection('section-{{ $section->id }}')">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </h2>

                                    <div class="accordion-collapse collapse" id="section-{{ $section->id }}-collapse"
                                        aria-labelledby="heading-section-{{ $section->id }}"
                                        data-bs-parent="#accordionExample">
                                        <div class="accordion-body">
                                            <input type="hidden" name="section[section-{{ $section->id }}][data][id]"
                                                class="const-section-id" value="section-{{ $section->id }}">
                                            <input type="hidden"
                                                name="section[section-{{ $section->id }}][data][order]"
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

                                            <div class="card p-4 alert alert-primary">
                                                <p>
                                                    <strong>Selected Type:</strong>&nbsp;
                                                    {{ $section->section_type === 'form' ? 'Form Section' : 'Content Section' }}
                                                </p>
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <p class="mb-0">
                                                        <strong>Listable:</strong>
                                                        <span class="badge rounded-pill badge-subtle-warning">
                                                            {{ $section->is_listable ? 'Yes' : 'No' }}
                                                        </span>
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="fields-drop-zone"
                                                data-section-id="section-{{ $section->id }}">
                                                <hr class="text-200">
                                                <div class="row gx-2"
                                                    id="fields-container-section-{{ $section->id }}">
                                                    @foreach ($section->fields as $fieldIndex => $field)
                                                        @php
                                                            $fieldData = $field->fieldData ?? null;
                                                        @endphp
                                                        <input type="hidden"
                                                            name="section[section-{{ $section->id }}][fields][{{ $fieldIndex }}][id]"
                                                            value="{{ $field->id ?? '' }}">
                                                        <div class="col-12 field-item card p-4 mb-4"
                                                            data-field-index="{{ $fieldIndex }}">
                                                            <div class="d-flex justify-content-between">
                                                                <input type="text"
                                                                    name="section[section-{{ $section->id }}][fields][{{ $fieldIndex }}][field_name]"
                                                                    class="form-control form-control-sm editable-field w-50 mb-2"
                                                                    value="{{ $field->field_name }}">
                                                                <div class="d-flex align-items-center gap-3">
                                                                    <div
                                                                        class="form-check form-switch d-flex align-items-center gap-1 m-0">
                                                                        <input class="form-check-input"
                                                                            type="checkbox"
                                                                            id="field-hidden-{{ $fieldIndex }}-{{ $section->id }}"
                                                                            name="section[section-{{ $section->id }}][fields][{{ $fieldIndex }}][is_hidden]"
                                                                            value="1"
                                                                            {{ $field->is_hidden ? 'checked' : '' }}>
                                                                        <label class="form-check-label mb-0"
                                                                            for="field-hidden-{{ $fieldIndex }}-{{ $section->id }}">
                                                                            Hidden
                                                                        </label>
                                                                    </div>
                                                                    <div
                                                                        class="form-check form-switch d-flex align-items-center gap-1 m-0">
                                                                        <input class="form-check-input"
                                                                            type="checkbox"
                                                                            id="field-required-{{ $fieldIndex }}-{{ $section->id }}"
                                                                            name="section[section-{{ $section->id }}][fields][{{ $fieldIndex }}][is_required]"
                                                                            value="1"
                                                                            {{ $field->is_required ? 'checked' : '' }}>
                                                                        <label class="form-check-label mb-0"
                                                                            for="field-required-{{ $fieldIndex }}-{{ $section->id }}">
                                                                            Required
                                                                        </label>
                                                                    </div>

                                                                    <div
                                                                        class="form-check form-switch d-flex align-items-center gap-1 m-0">
                                                                        <input class="form-check-input"
                                                                            type="checkbox"
                                                                            id="field-multiple-{{ $fieldIndex }}-{{ $section->id }}"
                                                                            name="section[section-{{ $section->id }}][fields][{{ $fieldIndex }}][is_multiple]"
                                                                            value="1"
                                                                            {{ $field->is_multiple ? 'checked' : '' }}>
                                                                        <label class="form-check-label mb-0"
                                                                            for="field-multiple-{{ $fieldIndex }}-{{ $section->id }}">
                                                                            Multiple values
                                                                        </label>
                                                                    </div>
                                                                    @if ($field->field_type === 'input')
                                                                        <div
                                                                            class="form-check form-switch d-flex align-items-center gap-1 m-0">
                                                                            <input
                                                                                class="form-check-input is-related-toggle"
                                                                                type="checkbox"
                                                                                id="field-related-{{ $section->id }}-{{ $fieldIndex }}"
                                                                                name="section[section-{{ $section->id }}][fields][{{ $fieldIndex }}][is_related]"
                                                                                value="1"
                                                                                {{ $field->is_related ? 'checked' : '' }}>
                                                                            <label class="form-check-label mb-0"
                                                                                for="field-related-{{ $fieldIndex }}-{{ $section->id }}">
                                                                                Is Related
                                                                            </label>
                                                                        </div>
                                                                    @endif

                                                                    <button type="button"
                                                                        class="btn btn-link text-danger btn-sm p-0"
                                                                        onclick="removeField(this)"
                                                                        title="Remove Field" data-bs-toggle="tooltip"
                                                                        data-bs-placement="top">
                                                                        <i class="fas fa-trash-alt"></i>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                            <input type="hidden"
                                                                name="section[section-{{ $section->id }}][fields][{{ $fieldIndex }}][field_type]"
                                                                value="{{ $field->field_type }}">

                                                            @if ($field->field_type === 'input')
                                                                <input class="form-control form-control-sm"
                                                                    type="text" value="" placeholder="Text"
                                                                    name="section[section-{{ $section->id }}][fields][{{ $fieldIndex }}][value]"
                                                                    disabled>
                                                                <div class="related-dropdowns mt-2 {{ $field->is_related ? 'd-flex' : 'd-none' }} align-items-center gap-3"
                                                                    id="dropdown-container-{{ $section->id }}-{{ $fieldIndex }}"
                                                                    data-field-id="{{ $field->id }}">
                                                                    <div class="p-2 border rounded bg-light d-flex align-items-center"
                                                                        style="white-space: nowrap; height: 31px;">
                                                                        <label
                                                                            class="small fw-bold mb-0 me-2 text-muted"
                                                                            style="font-size: 0.75rem;">Type:</label>
                                                                        <div class="d-flex gap-2 mt-3">
                                                                            <div class="form-check form-check-inline">
                                                                                <input class="form-check-input"
                                                                                    type="radio"
                                                                                    name="section[section-{{ $section->id }}][fields][{{ $fieldIndex }}][link_type]"
                                                                                    id="link-section-{{ $section->id }}-{{ $fieldIndex }}"
                                                                                    value="section"
                                                                                    {{ (optional($field->relation)->link_type ?? 'section') === 'section' ? 'checked' : '' }}>
                                                                                <label class="form-check-label small"
                                                                                    for="link-section-{{ $section->id }}-{{ $fieldIndex }}"
                                                                                    style="font-size: 0.75rem;">Model</label>
                                                                            </div>
                                                                            <div class="form-check form-check-inline">
                                                                                <input class="form-check-input"
                                                                                    type="radio"
                                                                                    name="section[section-{{ $section->id }}][fields][{{ $fieldIndex }}][link_type]"
                                                                                    id="link-module-{{ $section->id }}-{{ $fieldIndex }}"
                                                                                    value="module"
                                                                                    {{ (optional($field->relation)->link_type ?? '') === 'module' ? 'checked' : '' }}>
                                                                                <label class="form-check-label small"
                                                                                    for="link-module-{{ $section->id }}-{{ $fieldIndex }}"
                                                                                    style="font-size: 0.75rem;">Module</label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="d-flex gap-2 flex-grow-1">
                                                                        <select
                                                                            class="form-select form-select-sm cms-page-select"
                                                                            name="section[section-{{ $section->id }}][fields][{{ $fieldIndex }}][related_page_id]"
                                                                            data-field-index="{{ $fieldIndex }}"
                                                                            data-section-id="section-{{ $section->id }}">
                                                                            <option value="">-- Page --</option>
                                                                            @foreach ($cmsPages as $cmsPage)
                                                                                <option value="{{ $cmsPage->id }}"
                                                                                    {{ optional($field->relation)->related_page_id == $cmsPage->id ? 'selected' : '' }}>
                                                                                    {{ $cmsPage->name }}
                                                                                </option>
                                                                            @endforeach
                                                                        </select>
                                                                        <select
                                                                            class="form-select form-select-sm cms-section-select"
                                                                            name="section[section-{{ $section->id }}][fields][{{ $fieldIndex }}][related_section_id]"
                                                                            data-field-index="{{ $fieldIndex }}"
                                                                            data-section-id="section-{{ $section->id }}"
                                                                            {{ optional($field->relation)->related_page_id ? '' : 'disabled' }}>
                                                                            <option value="">-- Section --
                                                                            </option>
                                                                            @if (optional($field->relation)->available_sections)
                                                                                @foreach ($field->relation->available_sections as $relSection)
                                                                                    <option
                                                                                        value="{{ $relSection->id }}"
                                                                                        {{ $field->relation->related_section_id == $relSection->id ? 'selected' : '' }}>
                                                                                        {{ $relSection->section_name }}
                                                                                    </option>
                                                                                @endforeach
                                                                            @endif
                                                                        </select>
                                                                        <select class="form-select form-select-sm cms-field-select"
                                                                            name="section[section-{{ $section->id }}][fields][{{ $fieldIndex }}][related_field_id]"
                                                                            data-field-index="{{ $fieldIndex }}" 
                                                                            data-section-id="section-{{ $section->id }}"
                                                                            data-selected-field="{{ optional($field->relation)->related_field_id }}"
                                                                            {{ optional($field->relation)->related_section_id ? '' : 'disabled' }}>
                                                                            <option value="">-- Field --</option>
                                                                            @if(optional($field->relation)->available_fields)
                                                                                @foreach($field->relation->available_fields as $relField)
                                                                                    <option value="{{ $relField->id }}" {{ $field->relation->related_field_id == $relField->id ? 'selected' : '' }}>
                                                                                        {{ $relField->field_name }}
                                                                                    </option>
                                                                                @endforeach
                                                                            @endif
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            @elseif($field->field_type === 'image')
                                                                <input class="form-control form-control-sm"
                                                                    type="file"
                                                                    name="section[section-{{ $section->id }}][fields][{{ $fieldIndex }}][value]"
                                                                    disabled>
                                                                @if ($fieldData && $fieldData->value)
                                                                    <small class="text-muted">Current:
                                                                        {{ $fieldData->value }}</small>
                                                                    <input type="hidden"
                                                                        name="section[section-{{ $section->id }}][fields][{{ $fieldIndex }}][current_value]"
                                                                        value="{{ $fieldData->value }}">
                                                                @endif
                                                            @elseif($field->field_type === 'textarea')
                                                                <textarea class="form-control form-control-sm" rows="3" placeholder="Textarea"
                                                                    name="section[section-{{ $section->id }}][fields][{{ $fieldIndex }}][value]" disabled></textarea>
                                                            @elseif($field->field_type === 'radio' || $field->field_type === 'checkbox')
                                                                <div
                                                                    class="options-wrapper mt-2 p-3 border rounded bg-light">
                                                                    <label
                                                                        class="small fw-bold d-block mb-2 text-primary">
                                                                        <i class="fas fa-list-ul"></i> Field Options
                                                                    </label>

                                                                    <div class="options-container"
                                                                        id="options-container-section-{{ $section->id }}-{{ $fieldIndex }}">
                                                                        @if ($field->options->count() > 0)
                                                                            @foreach ($field->options as $option)
                                                                                <div
                                                                                    class="input-group input-group-sm mb-1 option-row">
                                                                                    <span
                                                                                        class="input-group-text bg-white">
                                                                                        <i
                                                                                            class="fas fa-{{ $field->field_type === 'radio' ? 'dot-circle' : 'check-square' }} text-muted"></i>
                                                                                    </span>
                                                                                    <input type="text"
                                                                                        name="section[section-{{ $section->id }}][fields][{{ $fieldIndex }}][options][]"
                                                                                        class="form-control"
                                                                                        value="{{ $option->option_label }}"
                                                                                        placeholder="Option Label">
                                                                                    <button type="button"
                                                                                        class="btn btn-outline-danger remove-option">
                                                                                        <i class="fas fa-times"></i>
                                                                                    </button>
                                                                                </div>
                                                                            @endforeach
                                                                        @else
                                                                            <div
                                                                                class="input-group input-group-sm mb-1 option-row">
                                                                                <span
                                                                                    class="input-group-text bg-white">
                                                                                    <i
                                                                                        class="fas fa-{{ $field->field_type === 'radio' ? 'dot-circle' : 'check-square' }} text-muted"></i>
                                                                                </span>
                                                                                <input type="text"
                                                                                    name="section[section-{{ $section->id }}][fields][{{ $fieldIndex }}][options][]"
                                                                                    class="form-control"
                                                                                    placeholder="e.g. Yes / No / Maybe">
                                                                                <button type="button"
                                                                                    class="btn btn-outline-danger remove-option">
                                                                                    <i class="fas fa-times"></i>
                                                                                </button>
                                                                            </div>
                                                                        @endif
                                                                    </div>

                                                                    <button type="button"
                                                                        class="btn btn-sm btn-outline-primary mt-2 add-option-btn"
                                                                        data-section-id="section-{{ $section->id }}"
                                                                        data-field-index="{{ $fieldIndex }}">
                                                                        <i class="fas fa-plus-circle"></i> Add New
                                                                        Option
                                                                    </button>
                                                                </div>
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
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
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
                                    name="page[name]" value="{{ old('page.name', $page->name ?? '') }}"
                                    placeholder="Enter Page Name">

                                <div class="form-check form-switch mt-3">
                                    <input class="form-check-input" name="is_cms" type="checkbox" id="is-cms"
                                        {{ old('is_cms', $page->is_cms ?? '') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is-cms">Feature Module</label>
                                </div>

                                <div class="mt-3" id="icon-selection-section"
                                    style="{{ $page->is_cms ?? false ? 'display:block;' : 'display:none;' }}">
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
                                        value="{{ old('icon', $page->icon ?? '') }}">
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
                                placeholder="Enter Section Order" readonly
                                value="{{ isset($page) ? $page->sections->count() + 1 : 1 }}">
                        </div>
                        <div class="col-12 mb-3">
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
                            <button class="btn btn-success btn-sm mt-3" id="add-section" type="button">
                                <i class="fas fa-plus me-1"></i> Add Section
                            </button>
                            <button class="btn btn-danger btn-sm mt-3" id="cancel" type="button"
                                style="display: none">
                                <i class="fas fa-times me-1"></i> Cancel
                            </button>
                            <button class="btn btn-success btn-sm mt-3" id="update-section" style="display: none"
                                type="button">
                                <i class="fas fa-save me-1"></i> Update Section
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
                            <div class="field-palette-item border border-2 rounded px-3 py-2 text-center fw-semibold text-secondary bg-light cursor-pointer"
                                draggable="true" data-field-type="input">
                                Input
                            </div>
                            <div class="field-palette-item border border-2 rounded px-3 py-2 text-center fw-semibold text-secondary bg-light cursor-pointer"
                                draggable="true" data-field-type="textarea">
                                Textarea
                            </div>
                            <div class="field-palette-item border border-2 rounded px-3 py-2 text-center fw-semibold text-secondary bg-light cursor-pointer"
                                draggable="true" data-field-type="image">
                                File
                            </div>
                            <div class="field-palette-item border border-2 rounded px-3 py-2 text-center fw-semibold text-secondary bg-light cursor-pointer"
                                draggable="true" data-field-type="radio">
                                Radio
                            </div>
                            <div class="field-palette-item border border-2 rounded px-3 py-2 text-center fw-semibold text-secondary bg-light cursor-pointer"
                                draggable="true" data-field-type="checkbox">
                                Checkbox
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
                    <a href="{{ route('admin.cms-builder.pages.index') }}" class="btn btn-dark">Discard</a>
                    <button class="btn btn-primary" type="submit" name="action" value="save_close">
                        Save and Close
                    </button>
                    <button class="btn btn-primary" type="submit" name="action" value="save">
                        Save
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>
