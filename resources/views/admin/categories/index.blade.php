@extends('admin.layouts.app')
@section('content')

@include('admin.layouts.partials.topbar')

<div class="card mb-3">
    <div class="card-body">
        <div class="row flex-between-center">
            <div class="col-md">
                <h5 class="mb-2 mb-md-0">Categories Management</h5>
                <p class="text-muted mb-0">Manage all product categories</p>
            </div>
            <div class="col-auto">
                <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i> Create New Category
                </a>
            </div>
        </div>
    </div>
</div>

<div class="card mb-3" id="bulkActionsCard" style="display: none;">
    <div class="card-body">
        <div class="row align-items-center">
            <div class="col-md">
                <h6 class="mb-0" id="selectedCount">0 categories selected</h6>
            </div>
            <div class="col-auto">
                <div class="btn-group">
                    <button type="button" class="btn btn-success btn-sm" id="bulkActivateBtn">
                        <i class="fas fa-check-circle me-1"></i> Activate
                    </button>
                    <button type="button" class="btn btn-warning btn-sm" id="bulkDeactivateBtn">
                        <i class="fas fa-times-circle me-1"></i> Deactivate
                    </button>
                    <button type="button" class="btn btn-danger btn-sm" id="bulkDeleteBtn">
                        <i class="fas fa-trash me-1"></i> Delete
                    </button>
                    <button type="button" class="btn btn-secondary btn-sm" id="clearSelectionBtn">
                        <i class="fas fa-times me-1"></i> Clear
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        @if(count($categories) > 0)
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped" id="categoriesTable">
                <thead class="bg-light">
                    <tr>
                        <th width="40">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="selectAll">
                            </div>
                        </th>
                        <th width="50">#</th>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Slug</th>
                        <th>Parent Category</th>
                        <th>Children Count</th>
                        <th>Status</th>
                        <th width="120">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($categories as $category)
                    <tr data-category-id="{{ $category->id }}">
                        <td>
                            <div class="form-check">
                                <input class="form-check-input category-checkbox" type="checkbox" value="{{ $category->id }}">
                            </div>
                        </td>
                        <td>
                            {{ $loop->iteration }}
                        </td>
                        <td>
                            @if($category->getFirstMediaUrl('categories'))
                            <img src="{{ $category->getFirstMediaUrl('categories') }}"
                                 alt="{{ $category->name }}"
                                 class="img-thumbnail"
                                 style="max-height: 50px; max-width: 80px;">
                            @else
                            <span class="text-muted">No Image</span>
                            @endif
                        </td>
                        <td>
                            @if(isset($category->level) && $category->level > 0)
                                <i class="fas fa-level-down-alt text-muted me-1"></i>
                            @endif
                            <strong>{{ $category->name }}</strong>
                        </td>
                        <td>
                            <code>{{ $category->slug }}</code>
                        </td>
                        <td>
                            @if($category->parent)
                            <span class="badge bg-info">{{ $category->parent->name }}</span>
                            @else
                            <span class="text-muted">Root Category</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-primary">{{ $category->children_count }}</span>
                        </td>
                        <td>
                            <span class="badge {{ $category->is_active ? 'bg-success' : 'bg-secondary' }}">
                                {{ $category->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('admin.categories.edit', $category->id) }}"
                                   class="btn btn-outline-primary"
                                   title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button"
                                        class="btn btn-outline-danger delete-category"
                                        data-id="{{ $category->id }}"
                                        data-name="{{ $category->name }}"
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
            <i class="fas fa-folder-open fa-4x text-muted mb-3"></i>
            <h4>No Categories Found</h4>
            <p class="text-muted">No categories have been created yet.</p>
            <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i> Create First Category
            </a>
        </div>
        @endif
    </div>
</div>

<form id="delete-form" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<form id="bulk-action-form" method="POST" style="display: none;">
    @csrf
    <input type="hidden" name="action" id="bulkAction">
    <input type="hidden" name="ids" id="bulkIds">
</form>


@endsection

@push('custom-script')

<script>
    $(document).ready(function() {
        var table = $('#categoriesTable').DataTable({
            "pageLength": 8,
            "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
            "responsive": true,
            "dom": '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rt<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
            "language": {
                "search": "_INPUT_",
                "searchPlaceholder": "Search categories...",
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
            "order": [[1, 'asc']],
            "columnDefs": [
                { "orderable": false, "targets": [0, 2, 7] },
                { "searchable": false, "targets": [0, 2, 5, 6, 7] },
                { "className": "dt-center", "targets": [0, 1, 2, 6, 7] }
            ]
        });

        $('#selectAll').on('change', function() {
            $('.category-checkbox').prop('checked', this.checked);
            updateBulkActions();
        });

        $(document).on('change', '.category-checkbox', function() {
            updateBulkActions();
        });

        function updateBulkActions() {
            const selectedCount = $('.category-checkbox:checked').length;
            const bulkActionsCard = $('#bulkActionsCard');
            const selectedCountText = $('#selectedCount');

            if (selectedCount > 0) {
                selectedCountText.text(selectedCount + ' categor' + (selectedCount === 1 ? 'y' : 'ies') + ' selected');
                bulkActionsCard.slideDown();
                $('#selectAll').prop('checked', selectedCount === $('.category-checkbox').length);
            } else {
                bulkActionsCard.slideUp();
                $('#selectAll').prop('checked', false);
            }
        }

        $('#clearSelectionBtn').on('click', function() {
            $('.category-checkbox').prop('checked', false);
            $('#selectAll').prop('checked', false);
            updateBulkActions();
        });

        $('#bulkActivateBtn').on('click', function() {
            const selectedIds = getSelectedIds();
            if (selectedIds.length > 0) {
                Swal.fire({
                    title: 'Activate Categories?',
                    text: 'You are about to activate ' + selectedIds.length + ' categor' + (selectedIds.length === 1 ? 'y' : 'ies'),
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, activate them!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        performBulkAction('activate', selectedIds);
                    }
                });
            }
        });

        $('#bulkDeactivateBtn').on('click', function() {
            const selectedIds = getSelectedIds();
            if (selectedIds.length > 0) {
                Swal.fire({
                    title: 'Deactivate Categories?',
                    text: 'You are about to deactivate ' + selectedIds.length + ' categor' + (selectedIds.length === 1 ? 'y' : 'ies'),
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, deactivate them!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        performBulkAction('deactivate', selectedIds);
                    }
                });
            }
        });

        $('#bulkDeleteBtn').on('click', function() {
            const selectedIds = getSelectedIds();
            if (selectedIds.length > 0) {
                Swal.fire({
                    title: 'Delete Categories?',
                    text: 'You are about to delete ' + selectedIds.length + ' categor' + (selectedIds.length === 1 ? 'y' : 'ies') + '. This action cannot be undone!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete them!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        performBulkAction('delete', selectedIds);
                    }
                });
            }
        });

        function getSelectedIds() {
            const selectedIds = [];
            $('.category-checkbox:checked').each(function() {
                selectedIds.push($(this).val());
            });
            return selectedIds;
        }

        function performBulkAction(action, ids) {
            const form = $('#bulk-action-form');
            form.find('#bulkAction').val(action);
            form.find('#bulkIds').val(ids.join(','));

            $.ajax({
                url: '{{ route("admin.categories.bulk.action") }}',
                type: 'POST',
                data: form.serialize(),
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            title: 'Success!',
                            text: response.message,
                            icon: 'success',
                            confirmButtonColor: '#3085d6'
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            title: 'Error!',
                            text: response.message,
                            icon: 'error',
                            confirmButtonColor: '#3085d6'
                        });
                    }
                },
                error: function(xhr) {
                    Swal.fire({
                        title: 'Error!',
                        text: 'An error occurred while processing your request.',
                        icon: 'error',
                        confirmButtonColor: '#3085d6'
                    });
                }
            });
        }

        $('.delete-category').on('click', function() {
            const categoryId = $(this).data('id');
            const categoryName = $(this).data('name');

            Swal.fire({
                title: 'Are you sure?',
                text: `You are about to delete "${categoryName}". This action cannot be undone!`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = $('#delete-form');
                    const deleteUrl = "{{ route('admin.categories.destroy', ':id') }}".replace(':id', categoryId);
                    form.attr('action', deleteUrl);
                    form.submit();
                }
            });
        });

        $('#categoriesTable').on('mouseenter', 'tbody tr', function() {
            $(this).addClass('table-active');
        }).on('mouseleave', 'tbody tr', function() {
            $(this).removeClass('table-active');
        });
    });
</script>
@endpush
