@extends('admin.layouts.app')
@section('content')

    @include('admin.layouts.partials.topbar')

    {{-- === PAGE HEADER / ADD NEW BUTTON === --}}
    <div class="card mb-3">
        <div class="card-body">
            <div class="row flex-between-center">
                <div class="col-md">
                    <h5 class="mb-2 mb-md-0">Export History and Logs</h5>
                </div>

                <div class="col-auto">
                    <a href="{{ route('admin.export.logs.import.create') }}" class="btn btn-falcon-default" role="button">
                        <i class="fas fa-file-import me-1"></i> Import Existing Backup
                    </a>
                </div>
                <div class="col-auto">
                    <a href="{{ route('admin.export.logs.selective.create') }}" class="btn btn-success" role="button">
                        <i class="fas fa-file-export me-1"></i> Run New Export
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- === MAIN LOGS TABLE === --}}
    <div class="card">
        <div class="card-body">
            @if(session('success'))
                @include('admin.layouts.partials.alert', ['type' => 'success', 'message' => session('success')])
            @endif
            @if(session('error'))
                @include('admin.layouts.partials.alert', ['type' => 'danger', 'message' => session('error')])
            @endif

            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th>#ID</th>
                        <th>Exported At</th>
                        <th>User</th>
                        <th>Media Handling</th>
                        <th>Selected Tables</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($exportLogs as $log)
                        <tr>
                            <td>{{ $log->id }}</td>
                            <td>{{ $log->exported_at->format('d M Y H:i:s') }}</td>
                            <td>{{ $log->user->name ?? 'N/A' }}</td>
                            <td>
                                {{-- Use a badge for quick visual status --}}
                                @if($log->media_handling === 'files')
                                    <span class="badge bg-success">Files Included</span>
                                @else
                                    <span class="badge bg-info">Data Only</span>
                                @endif
                            </td>
                            <td>
                                {{-- Display a few tables, and use a link/tooltip for details --}}
                                <button type="button" class="btn btn-link p-0" data-bs-toggle="modal"
                                        data-bs-target="#logDetailsModal-{{ $log->id }}">
                                    {{ count(json_decode($log->selected_tables)) }} Tables Selected
                                </button>
                            </td>
                            <td>
                                <div class="btn-group">
                                    @if($log->is_backup_available && $log->backup_file_path)
                                        <a href="{{ route('admin.export.logs.download', $log->id) }}"
                                           class="btn btn-success btn-sm me-1" title="Download Original Backup">
                                            <i class="fas fa-download"></i>
                                        </a>
                                    @else
                                        <button class="btn btn-secondary btn-sm me-1" disabled
                                                title="Backup File Deleted">
                                            <i class="fas fa-download"></i>
                                        </button>
                                    @endif

                                    <form action="{{ route('admin.export.logs.re-export', $log->id) }}" method="POST"
                                          class="d-inline me-1">
                                        @csrf
                                        <button type="submit" class="btn btn-warning btn-sm"
                                                title="Re-run export with these selections"
                                                onclick="return confirm('Are you sure you want to run a NEW export based on this log\'s selections?')">
                                            <i class="fas fa-redo"></i>
                                        </button>
                                    </form>

                                    <button type="button"
                                            class="btn btn-danger btn-sm delete-log-btn"
                                            data-log-id="{{ $log->id }}"
                                            data-delete-url="{{ route('admin.export.logs.destroy', $log->id) }}"
                                            title="Delete Log and Backup File">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">No export logs found.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            {{ $exportLogs->links() }}
        </div>
    </div>

    {{-- Include Modals for Log Details (one for each log if implemented simply) --}}
    @foreach($exportLogs as $log)
        @include('admin.export.logs.partials.details_modal', ['log' => $log])
    @endforeach

@endsection

@push('custom-modals')

@endpush

{{-- === JAVASCRIPT FOR SWEETALERT === --}}
@push('custom-script')
    <script>
        document.querySelectorAll('.delete-log-btn').forEach(button => {
            button.addEventListener('click', function (e) {
                const deleteUrl = this.getAttribute('data-delete-url');
                const logId = this.getAttribute('data-log-id');

                Swal.fire({
                    title: 'Are you sure?',
                    html: "Deleting log ID <b>#" + logId + "</b> will permanently remove the associated backup file and the log entry. This action is irreversible. Proceed?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Create a temporary form to submit the DELETE request
                        const form = document.createElement('form');
                        form.action = deleteUrl;
                        form.method = 'POST';
                        form.style.display = 'none';

                        // Add CSRF token
                        const csrfInput = document.createElement('input');
                        csrfInput.type = 'hidden';
                        csrfInput.name = '_token';
                        csrfInput.value = '{{ csrf_token() }}';
                        form.appendChild(csrfInput);

                        // Add method spoofing for DELETE
                        const methodInput = document.createElement('input');
                        methodInput.type = 'hidden';
                        methodInput.name = '_method';
                        methodInput.value = 'DELETE';
                        form.appendChild(methodInput);

                        document.body.appendChild(form);
                        form.submit();
                    }
                });
            });
        });
    </script>
@endpush
