@extends('admin.layouts.app')
@section('content')

    @include('admin.layouts.partials.topbar')

    <div class="container-fluid mt-4">
        <div class="card mb-3">
            <div class="card-body d-flex justify-content-between align-items-center">
                <h5><i class="fas fa-search me-2"></i> Review Import Data</h5>
                <div>
                    <a href="{{ route('admin.export.logs.index') }}" class="btn btn-secondary btn-sm">Cancel</a>
                    <button type="submit" form="confirm-import-form" class="btn btn-success btn-sm">
                        <i class="fas fa-check-circle me-1"></i> Confirm & Import
                    </button>
                </div>
            </div>
        </div>

        <div class="row">
            {{-- Left: Table List --}}
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-light">
                        <h6 class="mb-0">Tables Found in Backup</h6>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-sm table-hover mb-0">
                            <thead class="table-dark">
                            <tr>
                                <th>Table Name</th>
                                <th class="text-center">Records</th>
                                <th>File Name</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($tableFiles as $file)
                                <tr>
                                    <td class="fw-bold text-primary">{{ $file['table'] }}</td>
                                    <td class="text-center">
                                        <span class="badge bg-info">{{ $file['count'] }}</span>
                                    </td>
                                    <td class="text-muted small">{{ $file['file'] }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                            <tfoot class="table-light">
                            <tr>
                                <th>Total Tables: {{ count($tableFiles) }}</th>
                                <th class="text-center">Total: {{ $totalRecords }}</th>
                                <th></th>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Right: Settings & Warning --}}
            <div class="col-md-4">
                <div class="card border-warning mb-3">
                    <div class="card-header bg-warning text-dark">
                        <h6 class="mb-0"><i class="fas fa-exclamation-triangle me-1"></i> Warning</h6>
                    </div>
                    <div class="card-body">
                        <p class="small">Restoring this backup will <strong>overwrite</strong> or <strong>duplicate</strong> data in the tables listed on the left.</p>
                        <ul class="small">
                            <li>Uploaded: {{ $importLog->uploaded_at->diffForHumans() }}</li>
                            <li>Overwrite Media: <strong>{{ $importLog->overwrite_media ? 'Yes' : 'No' }}</strong></li>
                        </ul>
                    </div>
                </div>

                <form action="{{ route('admin.export.logs.import.confirm', $importLog->id) }}" method="POST" id="confirm-import-form">
                    @csrf
                    <div class="card">
                        <div class="card-body text-center">
                            <div class="form-check form-switch mb-3 d-flex justify-content-center">
                                <input class="form-check-input me-2" type="checkbox" name="data_only" id="dataOnlySwitch" value="1">
                                <label class="form-check-label" for="dataOnlySwitch">Import Data Only (Skip Media/Files)</label>
                            </div>
                            <p class="text-muted small">Are you sure you want to proceed with the restoration?</p>
                            <button type="submit" class="btn btn-success w-100">Start Restoration</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
