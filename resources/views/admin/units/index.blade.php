@extends('admin.layouts.app')
@section('content')
    @include('admin.layouts.partials.topbar')
    <div class="card mb-3">
        <div class="card-body">
            <div class="row flex-between-center">
                <div class="col-md">
                    <h5 class="mb-2 mb-md-0">Unit Management</h5>
                    <p class="text-muted mb-0">Manage units</p>
                </div>
                <div class="col-auto">
                    <a href="{{ route('admin.units.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i> Create New Unit
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            @if (count($units) > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-striped" id="unitsTable">
                        <thead class="bg-light">
                            <tr>
                                <th width="50">#</th>
                                <th>Unit Name</th>
                                <th>Short Name (Symbol)</th>
                                <th>Created At</th>
                                <th width="120">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($units as $unit)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <strong>{{ $unit->name }}</strong>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary">{{ $unit->short_name }}</span>
                                    </td>
                                    <td>
                                        <small class="text-muted">{{ $unit->created_at->format('d M, Y') }}</small>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('admin.units.edit', $unit->id) }}"
                                                class="btn btn-outline-primary" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" class="btn btn-outline-danger delete-unit"
                                                data-id="{{ $unit->id }}" data-name="{{ $unit->name }}"
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
                    <i class="fas fa-balance-scale fa-4x text-muted mb-3"></i>
                    <h4>No Units Found</h4>
                    <p class="text-muted">No units of measurement have been created yet.</p>
                    <a href="{{ route('admin.units.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i> Create First Unit
                    </a>
                </div>
            @endif
        </div>
    </div>
    <form id="delete-form" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>
@endsection

@push('custom-script')
    <script>
        $(document).ready(function() {
            $('#unitsTable').DataTable({
                "pageLength": 10,
                "responsive": true,
                "order": [
                    [0, 'asc']
                ]
            });

            $('.delete-unit').on('click', function() {
                const unitId = $(this).data('id');
                const unitName = $(this).data('name');

                Swal.fire({
                    title: 'Are you sure?',
                    text: `You are about to delete the unit "${unitName}". This action cannot be undone!`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        const form = $('#delete-form');

                        const deleteUrl = "{{ route('admin.units.destroy', ':id') }}".replace(':id',
                            unitId);

                        form.attr('action', deleteUrl);
                        form.submit();
                    }
                });
            });
        });
    </script>
@endpush
