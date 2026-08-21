@extends('admin.layouts.app')
@section('content')

@include('admin.layouts.partials.topbar')

<div class="card mb-3">
    <div class="card-body">
        <div class="row flex-between-center">
            <div class="col-md">
                <h5 class="mb-2 mb-md-0">Variations Management</h5>
                <p class="text-muted mb-0">Manage product variations and options</p>
            </div>
            <div class="col-auto">
                <a href="{{ route('admin.variations.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i> Create New Variation
                </a>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        @if(count($variations) > 0)
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped" id="variationsTable">
                <thead class="bg-light">
                    <tr>
                        <th width="50">#</th>
                        <th>Name</th>
                        <th>Slug</th>
                        <th>Options</th>
                        <th>Status</th>
                        <th width="120">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($variations as $variation)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            <strong>{{ $variation->name }}</strong>
                            @if($variation->description)
                            <br>
                            <small class="text-muted">{{ Str::limit($variation->description, 50) }}</small>
                            @endif
                        </td>
                        <td>
                            <code>{{ $variation->slug }}</code>
                        </td>
                        <td>
                            @foreach($variation->activeOptions as $option)
                            <span class="badge bg-primary mb-1">
                                {{ $option->value }}
                                @if($option->color)
                                <i class="fas fa-circle ms-1" style="color: {{ $option->color }}"></i>
                                @endif
                            </span>
                            @endforeach
                        </td>
                        <td>
                            <span class="badge {{ $variation->is_active ? 'bg-success' : 'bg-secondary' }}">
                                {{ $variation->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('admin.variations.edit', $variation->id) }}"
                                   class="btn btn-outline-primary"
                                   title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button"
                                        class="btn btn-outline-danger delete-variation"
                                        data-id="{{ $variation->id }}"
                                        data-name="{{ $variation->name }}"
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
            <i class="fas fa-list-alt fa-4x text-muted mb-3"></i>
            <h4>No Variations Found</h4>
            <p class="text-muted">No variations have been created yet.</p>
            <a href="{{ route('admin.variations.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i> Create First Variation
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
        $('#variationsTable').DataTable({
            "pageLength": 10,
            "responsive": true,
            "order": [[0, 'asc']]
        });

        $('.delete-variation').on('click', function() {
            const variationId = $(this).data('id');
            const variationName = $(this).data('name');

            Swal.fire({
                title: 'Are you sure?',
                text: `You are about to delete "${variationName}". This will also delete all its options!`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = $('#delete-form');
                    const deleteUrl = "{{ route('admin.variations.destroy', ':id') }}".replace(':id', variationId);
                    form.attr('action', deleteUrl);
                    form.submit();
                }
            });
        });
    });
</script>
@endpush
