@extends('admin.layouts.app')
@section('content')

    @include('admin.layouts.partials.topbar')
    
    <div class="card mb-3">
        <div class="card-body">
            <div class="row flex-between-center">
                <div class="col-md">
                    <h5 class="mb-2 mb-md-0">Permissions List</h5>
                </div>
                <div class="col-auto">
                    <a href="{{ route('admin.permissions.create') }}" class="btn btn-primary" role="button">Add New Permission</a>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="permissionsTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Permission Name</th>
                            <th>Guard Name</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($permissions as $permission)
                            <tr>
                                <td>{{ $permission->id }}</td>
                                <td>{{ $permission->name }}</td>
                                <td>{{ $permission->guard_name }}</td>
                                <td>{{ $permission->created_at->format('d M Y') }}</td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('admin.permissions.edit', $permission->id) }}"
                                           class="btn btn-warning btn-sm" style="margin-right: 5px">Edit</a>
                                        <form action="{{ route('admin.permissions.destroy', $permission->id) }}"
                                              method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm"
                                                    onclick="return confirm('Are you sure you want to delete this permission?')">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">No permissions found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection

@push('custom-script')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const deleteForms = document.querySelectorAll('form[action*="permissions.destroy"]');
        
        deleteForms.forEach(form => {
            form.addEventListener('submit', function(e) {
                if (!confirm('Are you sure you want to delete this permission? This action cannot be undone.')) {
                    e.preventDefault();
                }
            });
        });

        var table = $('#permissionsTable').DataTable({
            "pageLength": 10,
            "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
            "responsive": true,
            "dom": '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rt<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
            "language": {
                "search": "_INPUT_",
                "searchPlaceholder": "Search permissions...",
                "lengthMenu": "Show _MENU_ entries",
                "info": "Showing _START_ to _END_ of _TOTAL_ entries",
                "infoEmpty": "Showing 0 to 0 of 0 entries",
                "infoFiltered": "(filtered from _MAX_ total entries)",
                "paginate": {
                    "first": "First",
                    "last": "Last",
                    "next": "Next",
                    "previous": "Previous"
                }
            },
            "order": [[0, 'asc']], 
            "columnDefs": [
                { 
                    "orderable": false, 
                    "targets": [4] 
                },
                { 
                    "searchable": false, 
                    "targets": [0, 3, 4] 
                },
                { 
                    "className": "dt-center", 
                    "targets": [0, 2, 3, 4]
                },
                {
                    "width": "10%",
                    "targets": [0]
                },
                {
                    "width": "15%",
                    "targets": [2, 3] 
                },
                {
                    "width": "20%",
                    "targets": [4]
                }
            ],
            "initComplete": function() {
                $('.dataTables_length select').addClass('form-select form-select-sm');
                $('.dataTables_filter input').addClass('form-control form-control-sm');
                
                $('.dataTables_info').addClass('text-start');
            }
        });
    });
    
</script>
@endpush