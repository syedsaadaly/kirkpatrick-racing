@extends('admin.layouts.app')
@section('content')

    @include('admin.layouts.partials.topbar')

    {{-- === Page Header: Reusable Logic === --}}
    <div class="card mb-3">
        <div class="card-body">
            <div class="row flex-between-center">
                <div class="col-md">
                    {{-- Heading ko context ke hisaab se adjust karein --}}
                    <h5 class="mb-2 mb-md-0">
                        {{-- Agar hum is form ko 'edit' route se include karte, toh heading change ho jati --}}
                        @if(isset($exportLog))
                            Re-run Export for Log #{{ $exportLog->id }}
                        @else
                            Run New Selective Data Export
                        @endif
                    </h5>
                </div>
                <div class="col-auto">
                    {{-- Discard button ko Logs Index par bhejte hain --}}
                    <a href="{{ route('admin.export.logs.index') }}" class="btn btn-link text-secondary p-0 me-3 fw-medium">Discard</a>
                    {{-- Form submission button --}}
                    <button class="btn btn-primary" form="selective-export-form" type="submit">
                        <i class="fas fa-file-export me-1"></i> Export Data
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- === Main Form Structure === --}}
    <form action="{{ route('admin.export.logs.selective.store') }}" method="POST" id="selective-export-form">
        @csrf
        <div class="row g-0">
            {{-- === LEFT COLUMN: TABLE SELECTION (7/12) === --}}
            <div class="col-lg-7 pe-lg-2">
                <div class="card mb-3 mb-lg-0">
                    <div class="card-header bg-body-tertiary">
                        <h5 class="mb-0"><i class="fas fa-database me-1"></i> 1. Select Data Tables (Domain Grouping)</h5>
                    </div>
                    <div class="card-body">

                        {{-- Loop through the $tableGroups data passed from the Controller --}}
                        @foreach($tableGroups as $groupName => $tables)
                            <div class="mb-4 p-3 border rounded {{ isset($tables['pages']) ? '' : 'd-none' }}">
                                <h6 class="mb-3 text-primary">{{ $groupName }}</h6>

                                {{-- Checkbox for Select All (Custom JS will handle this) --}}
                                <div class="form-check form-switch mb-2">
                                    <input class="form-check-input select-all-group" data-group="{{ Str::slug($groupName) }}" type="checkbox" role="switch" id="select-{{ Str::slug($groupName) }}">
                                    <label class="form-check-label fw-medium" for="select-{{ Str::slug($groupName) }}">Select All in this Group</label>
                                </div>

                                <hr class="my-2">

                                {{-- Individual Table Checkboxes --}}
                                <div class="row">
                                    @foreach($tables as $dbTableName => $label)
                                        <div class="col-md-6 col-lg-4 {{ $label !== 'CMS Pages' ? 'd-none' : '' }}">
                                            <div class="form-check">
                                                <input class="form-check-input table-checkbox table-{{ Str::slug($groupName) }}" type="checkbox"
                                                       name="selected_tables[]"
                                                       value="{{ $dbTableName }}"
                                                       id="table-{{ $dbTableName }}"
                                                       data-group="{{ Str::slug($groupName) }}"
                                                       {{-- Reusability: Agar $exportLog mein yeh table selected tha, toh checked rakho --}}
{{--                                                       @if(isset($exportLog) && in_array($dbTableName, json_decode($exportLog->selected_tables, true))) checked @endif>--}}
                                                       >

                                                <label class="form-check-label" for="table-{{ $dbTableName }}">
                                                    {{ $label }}
                                                </label>

                                                {{-- Dependency Cue (Hidden by default, shown/locked by JS) --}}
                                                <span class="text-danger small ms-1 dependency-cue" id="dep-cue-{{ $dbTableName }}" style="display:none;">
                                                    (Required)
                                                </span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- === RIGHT COLUMN: SETTINGS & ACTION (5/12) === --}}
            <div class="col-lg-5 ps-lg-2">
                <div class="sticky-sidebar">

                    {{-- 2. Media Handling Option --}}
                    <div class="card mb-3">
                        <div class="card-header bg-body-tertiary">
                            <h6 class="mb-0"><i class="fas fa-image me-1"></i> 2. Media File Handling</h6>
                        </div>
                        <div class="card-body">
                            <p class="text-muted small">Choose how related media files (Spatie Media Library) should be included:</p>

                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="media_handling" value="files" id="media-include" required
                                       {{-- Default selection or old log's selection --}}
                                       @if(isset($exportLog) && $exportLog->media_handling == 'files') checked @elseif(!isset($exportLog)) checked @endif>
                                <label class="form-check-label fw-medium" for="media-include">
                                    Include Physical Media Files
                                </label>
                                <p class="text-muted small mt-1 mb-0">Exports the actual files (images, PDFs). Increases archive size significantly.</p>
                            </div>

                            <div class="form-check mt-2">
                                <input class="form-check-input" type="radio" name="media_handling" value="data_only" id="media-data-only" required
                                       @if(isset($exportLog) && $exportLog->media_handling == 'data_only') checked @endif>
                                <label class="form-check-label fw-medium" for="media-data-only">
                                    Export Data Records Only
                                </label>
                                <p class="text-muted small mt-1 mb-0">Exports database records/links but skips physical files. Faster export.</p>
                            </div>
                        </div>
                    </div>

                    {{-- 3. Review & Action --}}
                    <div class="card mb-3">
                        <div class="card-header bg-body-tertiary">
                            <h6 class="mb-0"><i class="fas fa-check-circle me-1"></i> 3. Review & Export</h6>
                        </div>
                        <div class="card-body">

                            {{-- START: NOTES FIELD ADDITION --}}
                            <div class="mb-3">
                                <label for="export-notes" class="form-label fw-medium">Export Notes (Optional)</label>
                                <textarea class="form-control" id="export-notes" name="notes" rows="3" placeholder="e.g., Backup before API update or Export for QA testing in staging environment.">{{ old('notes', $exportLog->notes ?? '') }}</textarea>
                                <div class="form-text">These notes will be saved in the export log record.</div>
                            </div>
                            {{-- END: NOTES FIELD ADDITION --}}


                            <div class="alert alert-info py-2" role="alert">
                                Total Tables Selected: <span id="total-tables-count" class="fw-bold">0</span>
                            </div>

                            <div id="dependency-summary" class="alert alert-warning py-2" style="display:none;">
                                <small class="fw-medium">Required tables auto-included. Use Advanced Mode to deselect.</small>
                            </div>

                            {{-- Advanced Dependency Option (Part C) --}}
                            <div class="form-check form-switch mt-3">
                                <input class="form-check-input" type="checkbox" id="advanced-mode-toggle" role="switch">
                                <label class="form-check-label" for="advanced-mode-toggle">Unlock Advanced Dependency Mode</label>
                            </div>
                            <p class="text-muted small">Allows manual unchecking of required tables (not recommended).</p>

                            <div class="col-12 text-end mt-3">
                                <button class="btn btn-primary" type="submit">
                                    <i class="fas fa-rocket me-1"></i> Start Export
                                </button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </form>

    {{-- Bottom Action Bar (Optional, for continuity) --}}
    <div class="card mt-3">
        <div class="card-body">
            <div class="row justify-content-between align-items-center">
                <div class="col-md">
                    <h5 class="mb-2 mb-md-0">Confirm your selections to proceed.</h5>
                </div>
                <div class="col-auto">
                    <a href="{{ route('admin.export.logs.index') }}" class="btn btn-link text-secondary p-0 me-3 fw-medium">Discard</a>
                    <button class="btn btn-primary" form="selective-export-form" type="submit">
                        <i class="fas fa-file-export me-1"></i> Export Data
                    </button>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('custom-script')
    <script>
        $(document).ready(function () {

            const REQUIRED_DEPENDENCIES = @json($requiredDependencies);

            updateSummaryAndDependencies();

            // === Dependency Logic ===
            function updateSummaryAndDependencies() {
                let selectedCount = 0;
                const requiredIncluded = new Set();
                const checkedTables = [];

                $('.table-checkbox:checked').each(function() {
                    const tableName = $(this).val();
                    checkedTables.push(tableName);
                    selectedCount++;
                });

                $('.table-checkbox').prop('checked', false);

                let finalSelection = new Set();

                checkedTables.forEach(primaryTable => {
                    finalSelection.add(primaryTable);

                    if (REQUIRED_DEPENDENCIES[primaryTable]) {
                        REQUIRED_DEPENDENCIES[primaryTable].forEach(depTable => {
                            finalSelection.add(depTable); // Include dependency
                            if (!checkedTables.includes(depTable)) {
                                requiredIncluded.add(depTable);
                            }
                        });
                    }
                });

                $('.table-checkbox').each(function() {
                    const tableName = $(this).val();
                    if (finalSelection.has(tableName)) {
                        $(this).prop('checked', true);

                        if (requiredIncluded.has(tableName)) {
                            $(`#dep-cue-${tableName}`).show();
                            if (!$('#advanced-mode-toggle').is(':checked')) {
                                $(this).prop('disabled', true);
                            } else {
                                $(this).prop('disabled', false);
                            }
                        } else {
                            $(`#dep-cue-${tableName}`).hide();
                            $(this).prop('disabled', false);
                        }
                    } else {
                        $(`#dep-cue-${tableName}`).hide();
                        $(this).prop('disabled', false);
                    }
                });

                $('#total-tables-count').text(finalSelection.size);

                if (requiredIncluded.size > 0) {
                    $('#dependency-summary').show();
                } else {
                    $('#dependency-summary').hide();
                }
            }

            // === Event Listeners ===
            $(document).on('change', '.table-checkbox', function () {
                if (!$(this).is(':checked') && !$('#advanced-mode-toggle').is(':checked')) {
                    if ($(`#dep-cue-${$(this).val()}`).is(':visible')) {
                        $(this).prop('checked', true); // Re-check it
                        alert('This table is a required dependency and cannot be deselected unless Advanced Mode is unlocked.');
                        return;
                    }
                }
                updateSummaryAndDependencies();
            });

            $(document).on('change', '.select-all-group', function () {
                const group = $(this).data('group');
                const isChecked = $(this).is(':checked');

                $(`.table-${group}`).prop('checked', isChecked);

                updateSummaryAndDependencies();
            });

            $(document).on('change', '#advanced-mode-toggle', function () {
                updateSummaryAndDependencies();

                if ($(this).is(':checked')) {
                    alert('WARNING: Advanced Mode is active! You can now manually deselect required dependencies, which may result in an incomplete or broken export file.');
                }
            });
        });

        $(document).ready(function () {
            $('#table-pages')
                .prop('checked', true)
                .trigger('change');
        });
    </script>
@endpush
