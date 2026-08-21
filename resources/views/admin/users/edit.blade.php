@extends('admin.layouts.app')
@section('content')

@include('admin.layouts.partials.topbar')

<div class="card mb-3">
    <div class="card-body">
        <div class="row flex-between-center">
            <div class="col-md">
                <h5 class="mb-2 mb-md-0">Edit User</h5>
                <p class="text-muted mb-0">Update user details</p>
            </div>
            <div class="col-auto">
                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Back to Users
                </a>
            </div>
        </div>
    </div>
</div>

<form action="{{ route('admin.users.update', $user->id) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="card">
        <div class="card-header bg-light">
            <h6 class="mb-0">User Details</h6>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            Full Name <span class="text-danger">*</span>
                        </label>
                        <input type="text"
                               class="form-control @error('name') is-invalid @enderror"
                               name="name"
                               value="{{ old('name', $user->name) }}"
                               placeholder="Enter user's full name"
                               required>
                        @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            Email Address <span class="text-danger">*</span>
                        </label>
                        <input type="email"
                               class="form-control @error('email') is-invalid @enderror"
                               name="email"
                               value="{{ old('email', $user->email) }}"
                               placeholder="Enter email address"
                               required>
                        @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            New Password
                        </label>
                        <input type="password"
                               class="form-control @error('password') is-invalid @enderror"
                               name="password"
                               id="password"
                               placeholder="Leave blank to keep current password">
                        @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">
                            Minimum 8 characters (optional)
                        </small>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            Confirm Password
                        </label>
                        <input type="password"
                               class="form-control @error('password_confirmation') is-invalid @enderror"
                               name="password_confirmation"
                               id="password_confirmation"
                               placeholder="Confirm new password">
                        <div class="invalid-feedback" id="password-error"></div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            Assign Role <span class="text-danger">*</span>
                        </label>
                        @php
                            $oldRole = old('role', $user->roles->first()?->id);
                        @endphp
                        <select class="form-select @error('role') is-invalid @enderror" 
                                name="role" 
                                id="role-select" 
                                required>
                            <option value="">Select Role</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}" 
                                        {{ $oldRole == $role->id ? 'selected' : '' }}>
                                    {{ $role->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('role')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">
                            Select a role for this user
                        </small>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            Account Status
                        </label>
                        <div class="form-check form-switch">
                            <input class="form-check-input"
                                   type="checkbox"
                                   name="is_active"
                                   value="1"
                                   id="is_active"
                                   {{ old('is_active', $user->is_active ?? true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                Active Account
                            </label>
                        </div>
                        <div class="form-check form-switch mt-2">
                            <input class="form-check-input"
                                   type="checkbox"
                                   name="email_verified"
                                   value="1"
                                   id="email_verified"
                                   {{ old('email_verified', $user->email_verified_at ? true : false) ? 'checked' : '' }}>
                            <label class="form-check-label" for="email_verified">
                                Email Verified
                            </label>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            Created At
                        </label>
                        <input type="text"
                               class="form-control"
                               value="{{ $user->created_at->format('d M Y, h:i A') }}"
                               readonly>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            Last Updated
                        </label>
                        <input type="text"
                               class="form-control"
                               value="{{ $user->updated_at->format('d M Y, h:i A') }}"
                               readonly>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-body">
            <div class="row justify-content-between align-items-center">
                <div class="col-md">
                    <h5 class="mb-2 mb-md-0">Update User</h5>
                    <p class="text-muted mb-0">Save changes to this user</p>
                </div>
                <div class="col-auto">
                    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary me-2">
                        <i class="fas fa-times me-1"></i> Cancel
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Update User
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>

@endsection

@push('custom-script')
<script>
    $(document).ready(function() {
        $('#role-select').select2({
            theme: 'bootstrap-5',
            placeholder: 'Select a role...',
            allowClear: true,
            width: '100%'
        });

        $('#password_confirmation').on('keyup', function() {
            const password = $('#password').val();
            const confirmPassword = $(this).val();
            const errorDiv = $('#password-error');
            
            if (password !== '' && confirmPassword !== '' && password !== confirmPassword) {
                $(this).addClass('is-invalid');
                errorDiv.text('Passwords do not match');
            } else {
                $(this).removeClass('is-invalid');
                errorDiv.text('');
            }
        });

        $('form').on('submit', function(e) {
            const password = $('#password').val();
            const confirmPassword = $('#password_confirmation').val();
            const role = $('#role-select').val();
            
            if (password !== '') {
                if (password !== confirmPassword) {
                    e.preventDefault();
                    $('#password_confirmation').addClass('is-invalid');
                    $('#password-error').text('Passwords do not match');
                    $('#password_confirmation').focus();
                    return false;
                }
                
                if (password.length < 8) {
                    e.preventDefault();
                    $('#password').addClass('is-invalid');
                    $('#password').focus();
                    return false;
                }
            }
            
            if (!role) {
                e.preventDefault();
                $('#role-select').addClass('is-invalid');
                $('#role-select').focus();
                return false;
            }
        });
    });
</script>
@endpush