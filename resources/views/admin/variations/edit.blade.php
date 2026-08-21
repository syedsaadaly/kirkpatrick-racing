@extends('admin.layouts.app')
@section('content')
    @include('admin.layouts.partials.topbar')

    <div class="card mb-3">
        <div class="card-body">
            <div class="row flex-between-center">
                <div class="col-md">
                    <h5 class="mb-2 mb-md-0">Edit Variation: {{ $variation->name }}</h5>
                    <p class="text-muted mb-0">Update this product variation type</p>
                </div>
                <div class="col-auto">
                    <a href="{{ route('admin.variations.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Back to Variations
                    </a>
                </div>
            </div>
        </div>
    </div>

    @include('admin.variations.partials.form', ['variation' => $variation])
@endsection

@push('custom-script')
    @include('admin.variations.partials.form-script')
@endpush
