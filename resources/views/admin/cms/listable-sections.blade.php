@extends('admin.layouts.app')
@section('content')

    @include('admin.layouts.partials.topbar')

    <div class="card mb-3">
        <div class="card-body">
            <div class="row flex-between-center">
                <div class="col-md">
                    <h5 class="mb-2 mb-md-0">{{ $page->name }} - Listable Sections</h5>
                </div>
                <div class="col-auto">
                    @if ($page->is_cms == 0)
                        <a href="{{ route('admin.cms-builder.page.view', $page->id) }}" class="btn btn-primary">
                            <i class="fas fa-edit me-1"></i> Edit Page Content
                        </a>
                    @endif
                    @if(Auth::user()->hasRole('developer'))
                        <a href="{{ route('admin.cms-builder.pages.edit', $page->id) }}" class="btn btn-secondary">
                            <i class="fas fa-cog me-1"></i> {{ $page->name }} Settings
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @if($listableSections->count() > 0)
        @php
            $activeSectionId = request()->get('section_id', $listableSections->first()->id);
            $listableSection = $listableSections->where('id', $activeSectionId)->first();
        @endphp

        <div class="card">
            <div class="card-body">
                <ul class="nav nav-tabs" id="sectionTabs" role="tablist">
                    @foreach($listableSections as $index => $section)
                        <li class="nav-item" role="presentation">
                            <a href="?section_id={{ $section->id }}"
                               class="nav-link {{ $activeSectionId == $section->id ? 'active' : '' }}"
                               id="tab-{{ $section->id }}">
                                {{ $section->section_name }}
                                <span class="badge bg-secondary ms-1">{{ $section->items_count }}</span>
                            </a>
                        </li>
                    @endforeach
                </ul>

                <div class="tab-content" id="sectionTabsContent">
                    <div class="tab-pane show active" id="content-{{ $listableSection->id }}"
                         role="tabpanel" aria-labelledby="tab-{{ $listableSection->id }}">
                        <div class="d-flex justify-content-between align-items-center mt-4 mb-3">
                            <h5 class="mb-0">{{ $listableSection->section_name }} Items</h5>

                            <div class="btn-toolbar" role="toolbar" aria-label="Item actions">

                                <div class="me-3" id="bulkDeleteContainer" style="display: none;">
                                    <button type="button" id="bulkDeleteBtn" class="btn btn-danger btn-sm"
                                            data-section-id="{{ $listableSection->id }}">
                                        <i class="fas fa-trash me-1"></i> Delete Selected (<span id="selectedCount">0</span>)
                                    </button>
                                </div>

                                <div class="btn-group btn-group-sm" role="group" aria-label="Creation actions">

                                    <button class="btn btn-outline-primary" type="button" data-id="{{$listableSection->id}}"
                                            id="bulk-upload-btn" data-headers='@json($listableSection->fields->pluck("field_name"))'>
                                        <i class="fas fa-upload"></i> Bulk Upload
                                    </button>

                                    <a href="{{ route('admin.cms-builder.pages.sections.items.create', [$page->id, $listableSection->id]) }}"
                                       class="btn btn-primary">
                                        <i class="fas fa-plus"></i> Add New Item
                                    </a>
                                </div>
                            </div>
                        </div>

                        @if($listableSection->items->count())
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover table-striped">
                                    <thead class="bg-light">
                                    <tr>
                                        <th>
                                            <input type="checkbox" id="selectAll" class="form-check-input">
                                        </th>
                                        <th class="w-10">#</th>
                                        @foreach($listableSection->fields as $field)
                                            @if(!$field->is_hidden)
                                                <th>{{ $field->field_name }}</th>
                                            @endif
                                        @endforeach
                                        <th class="w-10">Order</th>
                                        <th class="w-20">Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($listableSection->items as $itemIndex => $item)
                                        <tr>
                                            <td>
                                                <input type="checkbox" class="item-checkbox form-check-input"
                                                       value="{{ $item->id }}">
                                            </td>
                                            <td>{{ $itemIndex + 1 }}</td>
                                            @foreach($listableSection->fields as $field)
                                                @if(!$field->is_hidden)
                                                    <td>
                                                        @php
                                                            $fieldData = $item->fieldData->where('page_section_field_id', $field->id)->first();
                                                            $media = $fieldData?->getFirstMedia('section-items') ?? null;
                                                            $itemMedia = $item?->getFirstMedia('section-items') ?? null;
                                                            $hasValue = $fieldData && $fieldData->value;
                                                        @endphp

                                                        @if($field->field_type === 'image' && $hasValue && (!is_null($media) || !is_null($itemMedia)))
                                                            @if (!is_null($media))
                                                                <a href="#"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#imageLightboxModal"
                                                                data-image-url="{{ $media->getUrl() }}"
                                                                data-image-title="{{ $field->field_name }}">

                                                                    <img src="{{ $media->getUrl() }}"
                                                                        alt="{{ $field->field_name }}"
                                                                        class="img-thumbnail"
                                                                        style="max-height: 50px; max-width: 80px;">
                                                                </a>
                                                            @elseif (!is_null($itemMedia))
                                                                <a href="#"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#imageLightboxModal"
                                                                data-image-url="{{ $itemMedia->getUrl() }}"
                                                                data-image-title="{{ $field->field_name }}">

                                                                    <img src="{{ $itemMedia->getUrl() }}"
                                                                        alt="{{ $field->field_name }}"
                                                                        class="img-thumbnail"
                                                                        style="max-height: 50px; max-width: 80px;">
                                                                </a>
                                                            @endif
                                                        @elseif($field->field_type === 'image' && $hasValue && is_null($media))
                                                            <span class="text-danger"
                                                                title="File not processed or link broken">
                                                                <i class="fas fa-file-image fa-2x"></i>
                                                            </span>
                                                            <small class="d-block text-muted">File/URL set</small>

                                                        @elseif($field->field_type === 'textarea' && $fieldData)
                                                            {!! Str::limit(strip_tags($fieldData->value), 80) !!}
                                                        @elseif(in_array($field->field_type, ['radio', 'checkbox']) && $hasValue)
                                                            @if($field->field_type === 'checkbox' && is_array(json_decode($fieldData->value)))
                                                                @foreach(json_decode($fieldData->value) as $selected)
                                                                    <span class="badge border text-dark fw-normal bg-light">{{ $selected }}</span>
                                                                @endforeach
                                                            @else
                                                                <span class="badge border text-dark fw-normal bg-light">{{ $fieldData->value }}</span>
                                                            @endif
                                                        @elseif($fieldData && $fieldData->value)
                                                            {{ Str::limit($fieldData->value, 50) }}
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </td>
                                                @endif
                                            @endforeach
                                            <td>
                                                <span class="badge bg-secondary">{{ $item->order }}</span>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <a href="{{ route('admin.cms-builder.pages.sections.items.edit', [$page->id, $listableSection->id, $item->id]) }}"
                                                       class="btn btn-outline-primary"
                                                       title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <button type="button"
                                                            class="btn btn-outline-danger delete-item"
                                                            data-id="{{ $item->id }}"
                                                            data-section-id="{{ $listableSection->id }}"
                                                            data-name="Item #{{ $item->id }}"
                                                            title="Delete">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                <h5>No Items Found</h5>
                                <p class="text-muted mb-4">No items have been created for this section yet.</p>
                                <a href="{{ route('admin.cms-builder.pages.sections.items.create', [$page->id, $listableSection->id]) }}"
                                   class="btn btn-primary">
                                    <i class="fas fa-plus me-1"></i> Create First Item
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="text-center py-5">
            <i class="fas fa-inbox fa-4x text-muted mb-3"></i>
            <h4>No Listable Sections</h4>
            <p class="text-muted">This page doesn't have any listable sections.</p>
            <a href="{{ route('admin.cms-builder.page.view', $page->id) }}" class="btn btn-primary">
                <i class="fas fa-arrow-left me-1"></i> Back to Page Content
            </a>
        </div>
    @endif

    <form id="delete-form" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>

@endsection

@push('custom-modals')
    <div class="modal fade" id="bulk-upload-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 900px">
            <div class="modal-content position-relative">
                <div class="position-absolute top-0 end-0 mt-2 me-2 z-1">
                    <button class="btn-close btn btn-sm btn-circle d-flex flex-center transition-base"
                            data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form id="importForm" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="section_id">
                    <div class="modal-body p-0">
                        <div class="rounded-top-3 py-3 ps-4 pe-6 bg-body-tertiary">
                            <h4 class="mb-1">Import CSV File</h4>
                        </div>
                        <div class="p-4">
                            <div class="mb-3">
                                <label class="col-form-label" for="module_file">Upload File:</label>
                                <input class="form-control form-control-sm" type="file" name="module_file"
                                       id="module_file" required>
                            </div>
                            <div class="mb-3">
                                <button type="button" id="downloadSampleBtn" class="btn btn-sm btn-outline-secondary">
                                    <i class="fas fa-download me-1"></i> Download Sample
                                </button>
                            </div>
                            <div id="samplePreviewContainer" class="mt-3"></div>

                        </div>
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Close</button>
                        <button class="btn btn-primary" type="submit">Import Data</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <div class="modal fade" id="mapping-modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Import Mapping</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="modalHtmlResponse">
                </div>
            </div>
        </div>
    </div>
@endpush

@push('custom-script')
    <script>
        $(document).ready(function () {
            var triggerTabList = [].slice.call(document.querySelectorAll('#sectionTabs button'))
            triggerTabList.forEach(function (triggerEl) {
                var tabTrigger = new bootstrap.Tab(triggerEl)

                triggerEl.addEventListener('click', function (event) {
                    event.preventDefault()
                    tabTrigger.show()
                })
            });

            $('.delete-item').on('click', function () {
                const itemId = $(this).data('id');
                const sectionId = $(this).data('section-id');
                const itemName = $(this).data('name');

                Swal.fire({
                    title: 'Are you sure?',
                    text: `You are about to delete ${itemName}. This action cannot be undone!`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        const form = $('#delete-form');
                        const deleteUrl = `{{ route('admin.cms-builder.pages.sections.items.destroy', ['page' => $page->id, 'section' => 'SECTION_ID', 'item' => 'ITEM_ID']) }}`
                            .replace('SECTION_ID', sectionId)
                            .replace('ITEM_ID', itemId);
                        form.attr('action', deleteUrl);
                        form.submit();
                    }
                });
            });

            $('.table tr').hover(
                function () {
                    $(this).addClass('table-active');
                },
                function () {
                    $(this).removeClass('table-active');
                }
            );

            // $('#bulk-upload-btn').click(function () {
            //     const section_id = $(this).data('id')
            //     const headers = $(this).data('headers');
            //     $('#bulk-upload-modal').find('#importForm input[name="section_id"]').val(section_id)
            //     $('#bulk-upload-modal').modal('show')
            //
            //     $('#downloadSampleBtn').data('headers', headers);
            // })

            // Function to generate dummy data
            function generateDummyData(headers, rowCount = 5) {
                const data = [];
                for (let i = 0; i < rowCount; i++) {
                    const row = headers.map(header => {
                        if (/image/i.test(header)) {
                            return 'https://via.placeholder.com/150x100.png?text=Image';
                        } else if (/textarea/i.test(header)) {
                            return 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.';
                        } else {
                            return `Lorem ${i + 1}`;
                        }
                    });
                    data.push(row);
                }
                return data;
            }

// Function to build HTML table
            function buildPreviewTable(headers, data) {
                let html = '<table class="table table-bordered table-sm"><thead><tr>';
                headers.forEach(header => {
                    html += `<th>${header}</th>`;
                });
                html += '</tr></thead><tbody>';

                data.forEach(row => {
                    html += '<tr>';
                    row.forEach(cell => {
                        html += `<td>${cell}</td>`;
                    });
                    html += '</tr>';
                });

                html += '</tbody></table>';
                return html;
            }

// Function to generate CSV content
            function generateCSV(headers, data) {
                let csvContent = '';
                csvContent += headers.join(',') + '\n';
                data.forEach(row => {
                    csvContent += row.join(',') + '\n';
                });
                return csvContent;
            }

// When Bulk Upload modal opens
            $('#bulk-upload-btn').click(function () {
                const section_id = $(this).data('id');
                const headers = $(this).data('headers'); // array of headers

                $('#bulk-upload-modal').find('#importForm input[name="section_id"]').val(section_id);
                $('#bulk-upload-modal').modal('show');

                // Generate dummy data and show preview immediately
                const dummyData = generateDummyData(headers);
                const tableHtml = buildPreviewTable(headers, dummyData);
                $('#samplePreviewContainer').html(tableHtml);

                // Save dummy data for CSV download
                $('#downloadSampleBtn').data('headers', headers);
                $('#downloadSampleBtn').data('dummyData', dummyData);
            });

// Download CSV when button clicked
            $('#downloadSampleBtn').click(function() {
                const headers = $(this).data('headers');
                const data = $(this).data('dummyData');

                const csvContent = generateCSV(headers, data);

                const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
                const url = URL.createObjectURL(blob);
                const link = document.createElement('a');
                link.href = url;
                link.setAttribute('download', 'sample.csv');
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
                URL.revokeObjectURL(url);
            });

            $('#importForm').on('submit', function (e) {
                e.preventDefault();

                let formData = new FormData(this);

                $.ajax({
                    url: "{{ route('admin.modules-data.import.data') }}",
                    type: "POST",
                    data: formData,
                    contentType: false,
                    processData: false,
                    beforeSend: function () {
                        console.log("Uploading...");
                    },
                    success: function (response) {
                        $('#modalHtmlResponse').html(response.data.html);

                        $('#bulk-upload-modal').modal('hide');

                        $('#mapping-modal').modal('show');
                    },
                    error: function (xhr) {
                        let errors = xhr.responseJSON.errors;
                        alert("Something went wrong! Check console.");
                        console.log(errors);
                    }
                });
            });


            $(document).on('submit', '#mappingForm', function (e) {
                e.preventDefault();

                let formData = new FormData(this);

                $.ajax({
                    url: $(this).attr('action'),
                    type: "POST",
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function (response) {
                        $('#mapping-modal').modal('hide');

                        if (response.status === 'success') {
                            Swal.fire({
                                title: 'Imported!',
                                text: 'Import successful! Total records imported: ' + response.total_imported,
                                icon: 'success'
                            }).then(() => {
                                location.reload();
                            });
                        }
                    },
                    error: function (xhr) {
                        let errorMessage = 'An error occurred during import.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        alert(errorMessage);
                        console.error("Import Error:", xhr.responseJSON);
                    }
                });
            });

            // 1. Select All Checkboxes logic
            $('#selectAll').on('click', function () {
                $('.item-checkbox').prop('checked', $(this).prop('checked'));
                toggleBulkDeleteBtn();
            });

            $('.item-checkbox').on('change', function () {
                toggleBulkDeleteBtn();
            });

            function toggleBulkDeleteBtn() {
                let selected = $('.item-checkbox:checked').length;
                $('#selectedCount').text(selected);
                selected > 0 ? $('#bulkDeleteContainer').fadeIn() : $('#bulkDeleteContainer').fadeOut();
            }

            // 2. Bulk Delete Ajax
            $('#bulkDeleteBtn').on('click', function () {
                let ids = [];
                $('.item-checkbox:checked').each(function () {
                    ids.push($(this).val());
                });

                const sectionId = $(this).data('section-id');

                const deleteUrl = `{{ route('admin.modules-data.bulk-delete', ['page' => $page->id, 'section' => 'SECTION_ID']) }}`.replace('SECTION_ID', sectionId)

                Swal.fire({
                    title: 'Are you sure?',
                    text: "You are about to delete " + ids.length + " selected items!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete them!',
                    cancelButtonText: 'No, cancel',
                }).then((result) => {

                    if (result.isConfirmed) {
                        $.ajax({
                            url: deleteUrl,
                            type: 'DELETE',
                            data: {
                                ids: ids,
                                _token: "{{ csrf_token() }}"
                            },
                            success: function (response) {
                                if (response.success) {
                                    Swal.fire({
                                        title: 'Deleted!',
                                        text: response.message,
                                        icon: 'success'
                                    }).then(() => {
                                        location.reload();
                                    });

                                } else {
                                    Swal.fire({
                                        title: 'Error!',
                                        text: response.message || 'Deletion failed on server.',
                                        icon: 'error'
                                    }).then(() => {
                                        location.reload();
                                    });
                                }
                            },
                            error: function (err) {
                                Swal.fire('Error!', 'An unexpected error occurred during the request.', 'error');
                            }
                        });
                    }
                });
            });
        });
    </script>
@endpush

@push('custom-style')
    <style>
        .nav-tabs .nav-link {
            border: 1px solid transparent;
            border-top-left-radius: 0.375rem;
            border-top-right-radius: 0.375rem;
            font-weight: 500;
            color: #6c757d;
            transition: all 0.15s ease-in-out;
        }

        .nav-tabs .nav-link:hover {
            border-color: #e9ecef #e9ecef #dee2e6;
            color: #495057;
        }

        .nav-tabs .nav-link.active {
            color: #0d6efd;
            background-color: #fff;
            border-color: #dee2e6 #dee2e6 #fff;
            font-weight: 600;
        }

        .tab-content {
            border: 1px solid #dee2e6;
            border-top: none;
            border-radius: 0 0 0.375rem 0.375rem;
            padding: 1.5rem;
            background-color: #fff;
        }

        .table th {
            border-top: none;
            font-weight: 600;
            background-color: #f8f9fa;
        }

        .img-thumbnail {
            padding: 0.25rem;
            background-color: #fff;
            border: 1px solid #dee2e6;
            border-radius: 0.375rem;
        }
    </style>
@endpush
