@extends('admin.layouts.app')
@section('content')

    @include('admin.layouts.partials.topbar')

    <div class="container-fluid mt-4">
        <div class="row">
            {{-- Left Column: Table Breakdown --}}
            <div class="col-md-8">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white py-3 d-flex align-items-center">
                        <h6 class="mb-0 fw-bold"><i class="fas fa-database me-2 text-primary"></i> Imported Records Breakdown</h6>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                <tr>
                                    <th class="ps-4">Database Table</th>
                                    <th class="text-center">Records Processed</th>
                                    <th class="text-end pe-4">Status</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php
                                    // Accessing the casted array directly from the model
                                    $stats = $importLog->extras['stats'] ?? [];
                                    ksort($stats); // Optional: Sort by table name alphabetically
                                @endphp

                                @forelse($stats as $tableName => $count)
                                    <tr>
                                        <td class="ps-4">
                                            <div class="d-flex align-items-center">
                                                <div class="bg-light rounded p-2 me-3">
                                                    <i class="fas fa-table text-muted"></i>
                                                </div>
                                                <div>
                                                    <span class="fw-bold d-block text-dark">{{ strtoupper(str_replace('_', ' ', $tableName)) }}</span>
                                                    <small class="text-muted">{{ $tableName }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2">
                                                {{ number_format($count) }} Rows
                                            </span>
                                        </td>
                                        <td class="text-end pe-4">
                                            <span class="text-success small fw-bold">
                                                <i class="fas fa-check-circle me-1"></i> Success
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center py-5">
                                            <img src="https://cdn-icons-png.flaticon.com/512/7486/7486744.png" width="50" class="opacity-25 mb-3">
                                            <p class="text-muted">No table statistics were found in this log.</p>
                                        </td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right Column: Media & Meta --}}
            <div class="col-md-4">
                {{-- Media Stats Card --}}
                <div class="card shadow-sm border-0 mb-4 overflow-hidden">
                    <div class="card-body p-0">
                        <div class="bg-success p-4 text-center text-white">
                            <i class="fas fa-images fa-3x mb-2 opacity-50"></i>
                            <h2 class="display-6 fw-bold mb-0">
                                @if(is_array($importLog->extras['media_processed'] ?? null))
                                    {{ array_sum($importLog->extras['media_processed']) }}
                                @else
                                    {{ $importLog->extras['media_processed'] ?? 0 }}
                                @endif
                            </h2>
                            <p class="text-uppercase small fw-bold mb-0">Media Files Imported</p>
                        </div>
                        <div class="p-3 bg-white text-center">
                            <small class="text-muted">Total images and documents attached to records</small>
                        </div>
                    </div>
                </div>

                {{-- Summary Card --}}
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white py-3">
                        <h6 class="mb-0 fw-bold">Import Summary</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted small">Total Tables:</span>
                            <span class="fw-bold small">{{ count($stats) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted small">Total Records:</span>
                            <span class="fw-bold small">{{ number_format(array_sum($stats)) }}</span>
                        </div>
                        <hr>
                        <label class="text-muted small d-block mb-1">Process Note:</label>
                        <div class="p-2 bg-light rounded small italic">
                            {{ $importLog->note ?? 'No additional notes provided.' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
