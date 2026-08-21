<div class="modal fade" id="logDetailsModal-{{ $log->id }}" tabindex="-1" aria-labelledby="logDetailsModalLabel-{{ $log->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-body-tertiary">
                <h5 class="modal-title" id="logDetailsModalLabel-{{ $log->id }}">
                    <i class="fas fa-info-circle me-1"></i> Export Log Details: #{{ $log->id }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                @php
                    $tables = json_decode($log->selected_tables, true) ?? [];
                    $isAvailable = (bool)$log->is_backup_available;
                    $mediaText = ($log->media_handling == 'files') ? 'Files & Data' : 'Data Only';
                @endphp

                <div class="row">

                    {{-- Left Column: Summary and Status --}}
                    <div class="col-md-5 border-end">
                        <h6 class="text-primary mb-3"><i class="fas fa-file-invoice me-1"></i> Export Summary</h6>

                        <dl class="row mb-0 small">
                            <dt class="col-sm-6 text-truncate">User:</dt>
                            <dd class="col-sm-6">{{ $log->user->name ?? 'System' }}</dd>

                            <dt class="col-sm-6 text-truncate">Exported On:</dt>
                            <dd class="col-sm-6">{{ $log->exported_at->format('Y-m-d H:i:s') }}</dd>

                            <dt class="col-sm-6 text-truncate">Media Handling:</dt>
                            <dd class="col-sm-6">{{ $mediaText }}</dd>

                            <dt class="col-sm-6 text-truncate">File Path:</dt>
                            <dd class="col-sm-6 text-truncate" title="{{ $log->backup_file_path ?? 'N/A' }}">{{ basename($log->backup_file_path) ?? 'N/A' }}</dd>

                            <dt class="col-sm-6 text-truncate">Status:</dt>
                            <dd class="col-sm-6">
                                <span class="badge @if($isAvailable) bg-success @else bg-danger @endif">
                                    {{ $isAvailable ? 'Available' : 'Deleted / Missing' }}
                                </span>
                            </dd>
                        </dl>

                        <h6 class="text-primary mt-3 mb-2"><i class="fas fa-sticky-note me-1"></i> Notes</h6>
                        <p class="small text-muted">{{ $log->notes ?? 'No specific notes recorded.' }}</p>
                    </div>

                    {{-- Right Column: Selected Tables --}}
                    <div class="col-md-7">
                        <h6 class="text-success mb-3"><i class="fas fa-table me-1"></i> Selected Tables ({{ count($tables) }})</h6>

                        <div id="modal-selected-tables-list" class="row">
                            @forelse($tables as $table)
                                <div class="col-sm-6 col-md-4 mb-2">
                                    <span class="badge bg-secondary-subtle text-secondary fw-medium">{{ $table }}</span>
                                </div>
                            @empty
                                <div class="col-12"><p class="text-muted">No tables recorded.</p></div>
                            @endforelse
                        </div>
                    </div>

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
