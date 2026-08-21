<form id="mappingForm" method="POST" action="{{ route('admin.modules-data.process-import', [$pageSection->id]) }}">
    @csrf

    <input type="hidden" name="file_path" value="{{ $filePath }}">
    <input type="hidden" name="section_id" value="{{ $pageSection->id }}">

    <h5 class="mb-3">
        <i class="fas fa-link me-2 text-primary"></i> Column Mapping for: <span
            class="text-success">{{ $sectionName ?? 'N/A' }}</span>
    </h5>

    <div class="alert alert-info border-0 d-flex align-items-center" role="alert">
        <div class="me-2">
            <i class="fas fa-file-csv fa-lg"></i>
        </div>
        <div>
            You are mapping the file:
            <strong class="text-dark">{{ $fileName ?? 'N/A' }}</strong>.
            <br>
            Please match the columns from your uploaded file (Left) to the database fields (Right).
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead class="bg-light">
            <tr>
                <th width="50%">Database Field (Required)</th>
                <th width="50%">CSV Column Header</th>
            </tr>
            </thead>
            <tbody>
            @foreach($dbFields as $field)
                <tr>
                    <td>
                        <label class="fw-bold">{{ ucwords($field['field_name']) }}</label>
                        @if($field['field_type'] === 'image')
                            <div class="form-check mt-1">
                                <input class="form-check-input"
                                       type="checkbox"
                                       value="1"
                                       id="download-check-{{ $field['id'] }}"
                                       name="mappings[{{ $field['id'] }}][download_media]">
                                <label class="form-check-label text-info small" for="download-check-{{ $field['id'] }}">
                                    Download image from URL (CSV column must contain a direct URL)
                                </label>
                            </div>
                        @endif

                        <input type="hidden" name="mappings[{{ $field['id'] }}][db_field_name]"
                               value="{{ $field['field_name'] }}">
                        <input type="hidden" name="mappings[{{ $field['id'] }}][db_field_type]"
                               value="{{ $field['field_type'] }}">
                    </td>
                    <td>
                        <select name="mappings[{{ $field['id'] }}][csv_header]"
                                class="form-select form-select-sm"
                                required>
                            <option value="">--- Select CSV Column ---</option>

                            @foreach($csvHeaders as $header)
                                <option value="{{ $header }}"
                                        @if(strtolower(trim($header)) === strtolower(trim($field['field_name']))) selected @endif>
                                    {{ $header }}
                                </option>
                            @endforeach
                            <option value="skip_column">--- Skip This Field ---</option>
                        </select>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-end mt-4">
        <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-success">Start Import</button>
    </div>
</form>
