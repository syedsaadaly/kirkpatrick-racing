@extends('admin.layouts.app')
@section('content')
    @include('admin.layouts.partials.topbar')
    <div class="card mb-3">
        <div class="card-body">
            <div class="row flex-between-center">
                <div class="col-md">
                    <h5 class="mb-2 mb-md-0">Vendor Management</h5>
                    <p class="text-muted mb-0">Manage vendors</p>
                </div>
                <div class="col-auto">
                    <a href="{{ route('admin.suppliers.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i> Create New Vendors
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            @if (count($vendors) > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-striped" id="vendorsTable">
                        <thead class="bg-light">
                            <tr>
                                <th width="50">#</th>
                                <th>Vendor Info</th>
                                <th>Contact Details</th>
                                <th>Status</th>
                                <th>Created At</th>
                                <th width="120">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($vendors as $vendor)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if ($vendor->logo)
                                                <img src="{{ asset('storage/' . $vendor->logo) }}" alt="logo"
                                                    class="rounded me-2" width="35" height="35"
                                                    style="object-fit: cover;">
                                            @else
                                                <div class="bg-soft-primary text-primary rounded d-flex align-items-center justify-content-center me-2"
                                                    style="width: 35px; height: 35px;">
                                                    <i class="fas fa-store fa-sm"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <strong>{{ $vendor->name }}</strong>
                                                @if ($vendor->business_name)
                                                    <br><small class="text-muted">{{ $vendor->business_name }}</small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <small>
                                            <i class="fas fa-envelope me-1 text-muted"></i> {{ $vendor->email ?? 'N/A' }}
                                            <br>
                                            <i class="fas fa-phone me-1 text-muted"></i> {{ $vendor->phone ?? 'N/A' }}
                                        </small>
                                    </td>
                                    <td>
                                        <span class="badge {{ $vendor->is_active ? 'bg-success' : 'bg-danger' }}">
                                            {{ $vendor->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td>
                                        <small class="text-muted">{{ $vendor->created_at->format('d M, Y') }}</small>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('admin.suppliers.edit', $vendor->id) }}"
                                                class="btn btn-outline-primary" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>

                                            <button type="button" class="btn btn-outline-danger delete-vendor"
                                                data-id="{{ $vendor->id }}" data-name="{{ $vendor->name }}"
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
                    <i class="fas fa-store-slash fa-4x text-muted mb-3"></i>
                    <h4>No Vendors Found</h4>
                    <p class="text-muted">You haven't added any vendors to the system yet.</p>
                    <a href="{{ route('admin.vendors.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i> Add First Vendor
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
            $('#vendorsTable').DataTable({
                "pageLength": 10,
                "responsive": true,
                "order": [
                    [0, 'asc']
                ]
            });

            $('.delete-vendor').on('click', function() {
                const vendorId = $(this).data('id');
                const vendorName = $(this).data('name');

                Swal.fire({
                    title: 'Are you sure?',
                    text: `You are about to delete the vendor "${vendorName}". This action cannot be undone!`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        const form = $('#delete-form');

                        const deleteUrl = "{{ route('admin.suppliers.destroy', ':id') }}".replace(
                            ':id',
                            vendorId);

                        form.attr('action', deleteUrl);
                        form.submit();
                    }
                });
            });
        });
    </script>
@endpush
