@extends('admin.layouts.app')
@section('content')

    @include('admin.layouts.partials.topbar')

    {{-- === Page Header === --}}
    <div class="card mb-3">
        <div class="card-body">
            <div class="row flex-between-center">
                <div class="col-md">
                    <h5 class="mb-2 mb-md-0">
                        Restore Database from Backup
                    </h5>
                </div>
                <div class="col-auto">
                    <a href="{{ route('admin.export.logs.index') }}"
                       class="btn btn-link text-secondary p-0 me-3 fw-medium">Cancel</a>
                    <button class="btn btn-primary" form="import-form" type="submit">
                        <i class="fas fa-upload me-1"></i> Upload & Preview
                    </button>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        @include('admin.layouts.partials.alert', ['type' => 'success', 'message' => session('success')])
    @elseif(session('error'))
        @include('admin.layouts.partials.alert', ['type' => 'danger', 'message' => session('error')])
    @endif

    {{-- === Main Form Structure === --}}
    <form action="{{ route('admin.export.logs.import.store') }}" method="POST" id="import-form"
          enctype="multipart/form-data">
        @csrf
        <div class="row g-0">
            <div class="col-lg-8 pe-lg-2">
                <div class="card mb-3">
                    <div class="card-header bg-body-tertiary">
                        <h5 class="mb-0"><i class="fas fa-file-archive me-1"></i> Select Backup ZIP File</h5>
                    </div>
                    <div class="card-body">

                        <p class="text-muted small">
                            Please upload the ZIP file previously created by the Selective Data Export tool.
                            The file should contain `data/` directories with JSON/CSV files and optionally a `media/`
                            directory.
                        </p>

                        <div class="mb-3">
                            <label for="backup_file" class="form-label fw-medium">Upload ZIP File</label>
                            <input class="form-control @error('backup_file') is-invalid @enderror"
                                   type="file"
                                   id="backup_file"
                                   name="backup_file"
                                   accept=".zip"
                                   required>
                            @error('backup_file')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Max file size: 100MB. Only .zip files are accepted.</div>
                        </div>

                        <div class="alert alert-warning small mt-4">
                            <strong>Warning:</strong> Restoring data will overwrite existing records in the database.
                            Proceed with caution.
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 ps-lg-2">
                <div class="sticky-sidebar">
                    <div class="card mb-3">
                        <div class="card-header bg-body-tertiary">
                            <h6 class="mb-0"><i class="fas fa-cog me-1"></i> Import Settings</h6>
                        </div>
                        <div class="card-body">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="overwrite_records"
                                       name="overwrite_records" value="1">
                                <label class="form-check-label" for="overwrite_records">Overwrite existing Records?</label>
                            </div>
                            <p class="text-muted small">If unchecked, media files with the same name will be skipped
                                during import.</p>

                            <hr class="my-3">

                            <div class="text-end">
                                <button class="btn btn-primary" type="submit">
                                    <i class="fas fa-search me-1"></i> Preview File Contents
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
