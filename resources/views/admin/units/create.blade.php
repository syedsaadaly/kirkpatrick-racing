@extends('admin.layouts.app')
@section('content')
    @include('admin.layouts.partials.topbar')

    <div class="card mb-3">
        <div class="card-body">
            <div class="row flex-between-center">
                <div class="col-md">
                    <h5 class="mb-2 mb-md-0">{{ isset($unit) ? 'Edit Unit' : 'Create New Unit' }}</h5>
                    <p class="text-muted mb-0">{{ isset($unit) ? 'Update unit details' : 'Add a new unit' }}</p>
                </div>
                <div class="col-auto">
                    <a href="{{ route('admin.units.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Back to Units
                    </a>
                </div>
            </div>
        </div>
    </div>

    <form action="{{ isset($unit) ? route('admin.units.update', $unit->id) : route('admin.units.store') }}" method="POST"
        id="unitForm">
        @csrf
        @if (isset($unit))
            @method('PUT')
        @endif

        <div class="row">
            <div class="col-lg-12">
                <div class="card mb-4">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">Unit Details</h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">
                                        Unit Name <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                        name="name" value="{{ old('name', $unit->name ?? '') }}"
                                        placeholder="e.g., Kilogram, Piece, Liter" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">
                                        Short Name <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control @error('short_name') is-invalid @enderror"
                                        name="short_name" value="{{ old('short_name', $unit->short_name ?? '') }}"
                                        placeholder="e.g., kg, pc, ltr" required>
                                    @error('short_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer bg-light text-end">
                        <button type="submit" class="btn btn-primary">
                            {{ isset($unit) ? 'Update Unit' : 'Save Unit' }}
                        </button>
                        <a href="{{ route('admin.units.index') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@push('custom-script')
    <script></script>
@endpush
