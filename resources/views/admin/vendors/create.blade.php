@extends('admin.layouts.app')
@section('content')
    @include('admin.layouts.partials.topbar')

    <div class="card mb-3">
        <div class="card-body">
            <div class="row flex-between-center">
                <div class="col-md">
                    <h5 class="mb-2 mb-md-0">{{ isset($vendor) ? 'Edit supplier' : 'Create New Supplier' }}</h5>
                    <p class="text-muted mb-0">{{ isset($vendor) ? 'Update supplier details' : 'Add a new supplier' }}</p>
                </div>
                <div class="col-auto">
                    <a href="{{ route('admin.suppliers.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Back to Suppliers
                    </a>
                </div>
            </div>
        </div>
    </div>
    <form action="{{ isset($vendor) ? route('admin.suppliers.update', $vendor->id) : route('admin.suppliers.store') }}"
        method="POST" id="vendorForm" enctype="multipart/form-data">
        @csrf
        @if (isset($vendor))
            @method('PUT')
        @endif

        <div class="row">
            <div class="col-lg-8">
                <div class="card mb-4">
                    <div class="card-header bg-light">
                        <h6 class="mb-0">Basic Information</h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Contact Name <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                        name="name" value="{{ old('name', $vendor->name ?? '') }}"
                                        placeholder="e.g. John Doe" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Official Business Name</label>
                                    <input type="text" class="form-control" name="business_name"
                                        value="{{ old('business_name', $vendor->business_name ?? '') }}"
                                        placeholder="e.g. Acme Corp LLC">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Email Address</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                        name="email" value="{{ old('email', $vendor->email ?? '') }}"
                                        placeholder="vendor@example.com">
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Phone Number</label>
                                    <input type="text" class="form-control" name="phone"
                                        value="{{ old('phone', $vendor->phone ?? '') }}" placeholder="+1 234 567 890">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Physical Address</label>
                                    <textarea name="address" class="form-control" rows="2" placeholder="Street, City, State, Zip Code">{{ old('address', $vendor->address ?? '') }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card mb-4">
                    <div class="card-header bg-light">
                        <h6 class="mb-0">Business & Banking Details</h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Tax Number / GST</label>
                                    <input type="text" class="form-control" name="tax_number"
                                        value="{{ old('tax_number', $vendor->tax_number ?? '') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Registration Number</label>
                                    <input type="text" class="form-control" name="registration_number"
                                        value="{{ old('registration_number', $vendor->registration_number ?? '') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Bank Name</label>
                                    <input type="text" class="form-control" name="bank_name"
                                        value="{{ old('bank_name', $vendor->bank_name ?? '') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Account Holder</label>
                                    <input type="text" class="form-control" name="account_holder"
                                        value="{{ old('account_holder', $vendor->account_holder ?? '') }}">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">IBAN / Account Number</label>
                                    <input type="text" class="form-control" name="account_number"
                                        value="{{ old('account_number', $vendor->account_number ?? '') }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card mb-4">
                    <div class="card-header bg-light">
                        <h6 class="mb-0">Logo & Branding</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Vendor Logo</label>
                            <input type="file" class="form-control @error('logo') is-invalid @enderror"
                                name="logo">
                            <small class="text-muted">Recommended: Square, Max 2MB</small>
                            @error('logo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        @if (isset($vendor) && $vendor->logo)
                            <div class="mb-3 text-center">
                                <img src="{{ asset('storage/' . $vendor->logo) }}" alt="Logo" class="img-thumbnail"
                                    style="max-height: 150px;">
                            </div>
                        @endif
                        <div class="mb-3">
                            <label class="form-label fw-bold">Website URL</label>
                            <input type="url" class="form-control" name="website"
                                value="{{ old('website', $vendor->website ?? '') }}" placeholder="https://...">
                        </div>
                        <div class="form-check form-switch mt-4">
                            <input type="hidden" name="is_active" value="0">
                            <input class="form-check-input" type="checkbox" name="is_active" id="is_active"
                                value="1" {{ old('is_active', $vendor->is_active ?? 1) ? 'checked' : '' }}>
                            <label class="form-check-label fw-bold" for="is_active">Vendor Status (Active)</label>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <button type="submit" class="btn btn-primary w-100 mb-2">
                            <i class="fas fa-save me-1"></i> {{ isset($vendor) ? 'Update Vendor' : 'Save Vendor' }}
                        </button>
                        <a href="{{ route('admin.suppliers.index') }}" class="btn btn-outline-secondary w-100">Cancel</a>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@push('custom-script')
    <script></script>
@endpush
