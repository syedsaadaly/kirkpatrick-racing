@extends('admin.layouts.app')
@section('content')

    @include('admin.layouts.partials.topbar')

    <div class="card mb-3">
        <div class="card-body d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fas fa-history me-2"></i> Import History & Logs</h5>
            <a href="{{ route('admin.export.logs.import.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-upload me-1"></i> New Import
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Date</th>
                        <th>User</th>
                        <th>Status</th>
                        <th>Media Overwrite</th>
                        <th>Notes</th>
                        <th>Details</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($importLogs as $log)
                        <tr>
                            <td>#{{ $log->id }}</td>
                            <td>
                                <span class="d-block small fw-bold">{{ $log->created_at->format('M d, Y') }}</span>
                                <span class="text-muted small">{{ $log->created_at->format('h:i A') }}</span>
                            </td>
                            <td>{{ $log->user->name ?? 'System' }}</td>
                            <td>
                                @if($log->status == 'COMPLETED')
                                    <span class="badge bg-success-subtle text-success border border-success-subtle">Completed</span>
                                @elseif($log->status == 'PROCESSING' || $log->status == 'QUEUED')
                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle">
                                        <i class="fas fa-spinner fa-spin me-1"></i> {{ $log->status }}
                                    </span>
                                @elseif($log->status == 'FAILED')
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle"
                                          title="{{ $log->error_message }}">Failed</span>
                                @else
                                    <span
                                        class="badge bg-secondary-subtle text-secondary border border-secondary-subtle">{{ $log->status }}</span>
                                @endif
                            </td>
                            <td>
                                <i class="fas {{ $log->overwrite_media ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' }}"></i>
                            </td>
                            <td>
                                <span class="text-truncate d-inline-block" style="max-width: 150px;"
                                      title="{{ $log->note }}">
                                    {{ $log->note ?? '---' }}
                                </span>
                            </td>
                            <td>
                                <button type="button" class="btn btn-outline-danger btn-xs"
                                        title="View Error">
                                    <a href="{{ route('admin.export.logs.import.detail', ['importLog' => $log->id]) }}">Log Details</a>
                                </button>
                            </td>
                            <td>
                                @if($log->status == 'FAILED')
                                    <button type="button" class="btn btn-outline-danger btn-xs"
                                            data-bs-toggle="modal" data-bs-target="#errorModal{{ $log->id }}"
                                            title="View Error">
                                        <i class="fas fa-exclamation-circle"></i> Details
                                    </button>

                                    {{-- Retry Button --}}
                                    @if($log->canBeRetried())
                                        <form action="{{ route('admin.export.logs.import.retry', $log->id) }}"
                                              method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-primary btn-xs"
                                                    title="Retry Import">
                                                <i class="fas fa-redo"></i> Retry
                                            </button>
                                        </form>
                                    @endif
                                @endif

                                @if($log->status == 'PENDING_PREVIEW')
                                    <a href="{{ route('admin.export.logs.import.preview', $log->id) }}"
                                       class="btn btn-warning btn-xs">Resume</a>
                                @endif
                            </td>
                        </tr>

                        @push('custom-modals')
                            <div class="modal fade" id="errorModal{{ $log->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header bg-danger text-white">
                                            <h5 class="modal-title">Import Error Details (Log #{{ $log->id }})</h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="alert alert-danger mb-0">
                                                <h6 class="fw-bold"><i class="fas fa-bug me-1"></i> Exception Message:</h6>
                                                <p class="mb-0"><code>{{ $log->error_message }}</code></p>
                                            </div>

                                            <div class="mt-3">
                                                <h6><i class="fas fa-info-circle me-1"></i> Troubleshooting Steps:</h6>
                                                <ul class="small">
                                                    <li>Check if the <code>storage/app/private/{{ $log->temp_file_path }}</code> folder still exists.</li>
                                                    <li>Ensure the database user has <code>TRUNCATE</code> and <code>INSERT</code> permissions.</li>
                                                    <li>Check <code>laravel.log</code> for full stack trace.</li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="modal-footer text-muted small">
                                            Attempted at: {{ $log->updated_at->format('Y-m-d H:i:s') }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endpush
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">No import logs found.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($importLogs->hasPages())
            <div class="card-footer">
                {{ $importLogs->links() }}
            </div>
        @endif
    </div>

@endsection
