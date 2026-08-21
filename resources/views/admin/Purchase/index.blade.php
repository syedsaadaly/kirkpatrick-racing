@extends('admin.layouts.app')
@section('content')
    @include('admin.layouts.partials.topbar')
    <div class="card mb-3">
        <div class="card-body">
            <div class="row flex-between-center">
                <div class="col-md">
                    <h5 class="mb-2 mb-md-0">Purchases Management</h5>
                    <p class="text-muted mb-0">Manage Purchases</p>
                </div>
                <div class="col-auto">
                    <a href="{{ route('admin.purchase.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i> Create New Purchases
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            @if (count($purchases) > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-striped" id="purchasesTable">
                        <thead class="bg-light">
                            <tr>
                                <th width="50">#</th>
                                <th>Ref No.</th>
                                <th>Supplier / Vendor</th>
                                <th>Date</th>
                                <th>Unit</th>
                                <th class="text-end">Total Amount</th>
                                <th width="120">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($purchases as $purchase)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <span class="fw-bold text-primary">{{ $purchase->reference_no }}</span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="bg-soft-info text-info rounded d-flex align-items-center justify-content-center me-2"
                                                style="width: 30px; height: 30px;">
                                                <i class="fas fa-user-tag fa-xs"></i>
                                            </div>
                                            <div>
                                                <strong>{{ $purchase->vendor->name ?? 'Direct Purchase' }}</strong>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <small class="text-muted">
                                            <i class="fas fa-calendar-alt me-1"></i>
                                            {{ \Carbon\Carbon::parse($purchase->purchase_date)->format('d M, Y') }}
                                        </small>
                                    </td>
                                    <td>
                                        <span class="badge bg-soft-secondary text-secondary">
                                            {{ $purchase->unit->short_name ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td class="text-end fw-bold">
                                        {{ number_format($purchase->grand_total, 2) }}
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('admin.purchase.show', $purchase->id) }}"
                                                class="btn btn-outline-info" title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.purchase.edit', $purchase->id) }}"
                                                class="btn btn-outline-primary" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" class="btn btn-outline-danger delete-purchase"
                                                data-id="{{ $purchase->id }}" data-ref="{{ $purchase->reference_no }}"
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
                    <i class="fas fa-file-invoice-dollar fa-4x text-muted mb-3"></i>
                    <h4>No Purchases Found</h4>
                    <p class="text-muted">You haven't recorded any purchases yet.</p>
                    <a href="{{ route('admin.purchase.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i> Record First Purchase
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
            $('#purchasesTable').DataTable({
                "pageLength": 10,
                "responsive": true,
                "order": [
                    [0, 'asc']
                ]
            });

            $('.delete-purchase').on('click', function() {
                const purchaseId = $(this).data('id');
                const purchaseRef = $(this).data('ref');

                Swal.fire({
                    title: 'Are you sure?',
                    text: `You are about to delete purchase "${purchaseRef}". This will reverse any stock increments associated with this order!`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        const form = $('#delete-form');
                        const deleteUrl = "{{ route('admin.purchase.destroy', ':id') }}".replace(
                            ':id', purchaseId);

                        form.attr('action', deleteUrl);
                        form.submit();
                    }
                });
            });
        });
    </script>
@endpush
