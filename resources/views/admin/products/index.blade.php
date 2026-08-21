@extends('admin.layouts.app')
@section('content')

@include('admin.layouts.partials.topbar')

<div class="card mb-3">
    <div class="card-body">
        <div class="row flex-between-center">
            <div class="col-md">
                <h5 class="mb-2 mb-md-0">Products Management</h5>
                <p class="text-muted mb-0">Manage all products in your store</p>
            </div>
            <div class="col-auto">
                <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i> Create New Product
                </a>
            </div>
        </div>
    </div>
</div>

<div class="card mb-3" id="bulkActionsCard" style="display: none;">
    <div class="card-body">
        <div class="row align-items-center">
            <div class="col-md">
                <h6 class="mb-0" id="selectedCount">0 products selected</h6>
            </div>
            <div class="col-auto">
                <div class="btn-group">
                    <button type="button" class="btn btn-success btn-sm" id="bulkActivateBtn">
                        <i class="fas fa-check-circle me-1"></i> Activate
                    </button>
                    <button type="button" class="btn btn-warning btn-sm" id="bulkDeactivateBtn">
                        <i class="fas fa-times-circle me-1"></i> Deactivate
                    </button>
                    <button type="button" class="btn btn-info btn-sm" id="bulkFeatureBtn">
                        <i class="fas fa-star me-1"></i> Feature
                    </button>
                    <button type="button" class="btn btn-secondary btn-sm" id="bulkUnfeatureBtn">
                        <i class="fas fa-star-half-alt me-1"></i> Unfeature
                    </button>
                    <button type="button" class="btn btn-danger btn-sm" id="bulkDeleteBtn">
                        <i class="fas fa-trash me-1"></i> Delete
                    </button>
                    <button type="button" class="btn btn-outline-secondary btn-sm" id="clearSelectionBtn">
                        <i class="fas fa-times me-1"></i> Clear
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        @if(count($products) > 0)
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped" id="productsTable">
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
                        <th>SKU</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Categories</th>
                        <th>Variations</th>
                        <th>Status</th>
                        <th>Featured</th>
                        <th width="120">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $product)
                    <tr data-product-id="{{ $product->id }}">
                        <td>
                            <div class="form-check">
                                <input class="form-check-input product-checkbox" type="checkbox" value="{{ $product->id }}">
                            </div>
                        </td>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            @if($product->main_image)
                            <img src="{{ $product->main_image }}"
                                 alt="{{ $product->name }}"
                                 class="img-thumbnail"
                                 style="max-height: 50px; max-width: 80px;">
                            @else
                            <span class="text-muted">No Image</span>
                            @endif
                        </td>
                        <td>
                            <strong>{{ $product->name }}</strong>
                            <br>
                            <small class="text-muted">{{ Str::limit($product->short_description, 50) }}</small>
                        </td>
                        <td>
                            <code>{{ $product->sku }}</code>
                        </td>
                        <td>
                            @if ($product->variations->isNotEmpty())
                                @php
                                    $variationPrices = $product->variations->map(fn ($v) => $v->sale_price ?? $v->price);
                                @endphp
                                <strong class="text-primary">${{ number_format($variationPrices->min(), 2) }} &ndash; ${{ number_format($variationPrices->max(), 2) }}</strong>
                                <br><small class="text-muted">across {{ $product->variations->count() }} variants</small>
                            @else
                                <strong class="text-primary">${{ number_format($product->base_price, 2) }}</strong>
                                @if($product->sale_price)
                                <br>
                                <small class="text-danger text-decoration-line-through">
                                    ${{ number_format($product->sale_price, 2) }}
                                </small>
                                @endif
                            @endif
                        </td>
                        <td>
                            @php
                                $displayStock = $product->variations->isNotEmpty()
                                    ? $product->variations->sum('stock_quantity')
                                    : $product->stock_quantity;
                            @endphp
                            <span class="badge {{ $displayStock > 10 ? 'bg-success' : ($displayStock > 0 ? 'bg-warning' : 'bg-danger') }}">
                                {{ $displayStock }} in stock
                            </span>
                            @if ($product->variations->isNotEmpty())
                                <br><small class="text-muted">across {{ $product->variations->count() }} variants</small>
                            @endif
                        </td>
                        <td>
                            @foreach($product->categories->take(2) as $category)
                            <span class="badge bg-info mb-1">{{ $category->name }}</span>
                            @endforeach
                            @if($product->categories->count() > 2)
                            <span class="badge bg-secondary">+{{ $product->categories->count() - 2 }} more</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-primary">{{ $product->variations_count ?? $product->variations->count() }}</span>
                        </td>
                        <td>
                            <span class="badge {{ $product->is_active ? 'bg-success' : 'bg-secondary' }}">
                                {{ $product->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td>
                            @if($product->is_featured)
                            <i class="fas fa-star text-warning" title="Featured"></i>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('admin.products.edit', $product->id) }}"
                                   class="btn btn-outline-primary"
                                   title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button"
                                        class="btn btn-outline-danger delete-product"
                                        data-id="{{ $product->id }}"
                                        data-name="{{ $product->name }}"
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
            <i class="fas fa-box-open fa-4x text-muted mb-3"></i>
            <h4>No Products Found</h4>
            <p class="text-muted">No products have been created yet.</p>
            <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i> Create First Product
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
        var table = $('#productsTable').DataTable({
            "pageLength": 10,
            "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
            "responsive": true,
            "dom": '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rt<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
            "language": {
                "search": "_INPUT_",
                "searchPlaceholder": "Search products...",
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
                { "orderable": false, "targets": [0, 2, 7, 8, 9, 10, 11] },
                { "searchable": false, "targets": [0, 2, 5, 6, 7, 8, 9, 10, 11] }
            ]
        });

        // Bulk actions functionality
        $('#selectAll').on('change', function() {
            $('.product-checkbox').prop('checked', this.checked);
            updateBulkActions();
        });

        $(document).on('change', '.product-checkbox', function() {
            updateBulkActions();
        });

        function updateBulkActions() {
            const selectedCount = $('.product-checkbox:checked').length;
            const bulkActionsCard = $('#bulkActionsCard');
            const selectedCountText = $('#selectedCount');

            if (selectedCount > 0) {
                selectedCountText.text(selectedCount + ' product' + (selectedCount === 1 ? '' : 's') + ' selected');
                bulkActionsCard.slideDown();
                $('#selectAll').prop('checked', selectedCount === $('.product-checkbox').length);
            } else {
                bulkActionsCard.slideUp();
                $('#selectAll').prop('checked', false);
            }
        }

        $('#clearSelectionBtn').on('click', function() {
            $('.product-checkbox').prop('checked', false);
            $('#selectAll').prop('checked', false);
            updateBulkActions();
        });

        // Bulk action handlers
        $('#bulkActivateBtn').on('click', function() {
            performBulkAction('activate', 'activate');
        });

        $('#bulkDeactivateBtn').on('click', function() {
            performBulkAction('deactivate', 'deactivate');
        });

        $('#bulkFeatureBtn').on('click', function() {
            performBulkAction('feature', 'feature');
        });

        $('#bulkUnfeatureBtn').on('click', function() {
            performBulkAction('unfeature', 'unfeature');
        });

        $('#bulkDeleteBtn').on('click', function() {
            performBulkAction('delete', 'delete');
        });

        function performBulkAction(action, actionText) {
            const selectedIds = getSelectedIds();
            if (selectedIds.length > 0) {
                const actionMessages = {
                    'activate': 'activate',
                    'deactivate': 'deactivate',
                    'feature': 'feature',
                    'unfeature': 'unfeature',
                    'delete': 'delete'
                };

                const message = action === 'delete'
                    ? 'You are about to delete ' + selectedIds.length + ' product' + (selectedIds.length === 1 ? '' : 's') + '. This action cannot be undone!'
                    : 'You are about to ' + actionText + ' ' + selectedIds.length + ' product' + (selectedIds.length === 1 ? '' : 's');

                Swal.fire({
                    title: actionText.charAt(0).toUpperCase() + actionText.slice(1) + ' Products?',
                    text: message,
                    icon: action === 'delete' ? 'warning' : 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, ' + actionText + ' them!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        submitBulkAction(action, selectedIds);
                    }
                });
            }
        }

        function getSelectedIds() {
            const selectedIds = [];
            $('.product-checkbox:checked').each(function() {
                selectedIds.push($(this).val());
            });
            return selectedIds;
        }

        function submitBulkAction(action, ids) {
            const form = $('#bulk-action-form');
            form.find('#bulkAction').val(action);
            form.find('#bulkIds').val(ids.join(','));

            $.ajax({
                url: '{{ route("admin.products.bulk.action") }}',
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

        $('.delete-product').on('click', function() {
            const productId = $(this).data('id');
            const productName = $(this).data('name');

            Swal.fire({
                title: 'Are you sure?',
                text: `You are about to delete "${productName}". This action cannot be undone!`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = $('#delete-form');
                    const deleteUrl = "{{ route('admin.products.destroy', ':id') }}".replace(':id', productId);
                    form.attr('action', deleteUrl);
                    form.submit();
                }
            });
        });

        $('#productsTable').on('mouseenter', 'tbody tr', function() {
            $(this).addClass('table-active');
        }).on('mouseleave', 'tbody tr', function() {
            $(this).removeClass('table-active');
        });
    });
</script>
@endpush
