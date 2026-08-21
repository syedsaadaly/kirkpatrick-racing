@extends('admin.layouts.app')
@section('content')
    @include('admin.layouts.partials.topbar')

    <div class="card mb-3">
        <div class="card-body">
            <div class="row flex-between-center">
                <div class="col-md">
                    <h5 class="mb-2 mb-md-0">Profile Settings</h5>
                </div>
            </div>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Please fix the following:</strong>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.settings.profile.update') }}" method="POST" enctype="multipart/form-data" id="profileForm">
        @csrf
        @method('PUT')

        <div class="card">
            <div class="card-header bg-light">
                <ul class="nav nav-tabs card-header-tabs" id="profileTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile"
                            type="button" role="tab" aria-controls="profile" aria-selected="true">
                            <i class="fas fa-user me-1"></i> Profile
                        </button>
                    </li>
                </ul>
            </div>

            <div class="card-body">
                <div class="tab-content" id="profileTabsContent">
                    <div class="tab-pane fade show active" id="profile" role="tabpanel" aria-labelledby="profile-tab"
                        tabindex="0">

                        <div class="mb-3">
                            <label class="form-label fw-semibold">User Name</label>
                            <input type="text" class="form-control @error('user_name') is-invalid @enderror" name="user_name" placeholder="Enter User Name"
                                value="{{ old('user_name', auth()->user()->name) }}">
                            @error('user_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">User Email</label>
                            <input type="email" class="form-control @error('user_email') is-invalid @enderror" name="user_email" placeholder="Enter Email"
                                value="{{ old('user_email', auth()->user()->email) }}">
                            @error('user_email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Password</label>
                            <input type="password" id="password" class="form-control @error('password') is-invalid @enderror" name="password"
                                placeholder="Leave blank to keep current password" value="">
                            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Confirm Password</label>
                            <input type="password" class="form-control" id="password_confirmation"
                                name="password_confirmation" placeholder="Confirm Password" value="">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Profile Image</label>
                            <input type="file" class="form-control @error('profile_image') is-invalid @enderror" name="profile_image" accept="image/*">
                            @error('profile_image')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            @php
                                $user = auth()->user();
                                $profileImage = $user->getFirstMediaUrl('profile');
                            @endphp
                            @if ($profileImage)
                                <img src="{{ $profileImage }}" class="img-thumbnail mt-2"
                                    style="max-height:80px;">
                            @else
                                <img src="{{ asset('admin/assets/img/team/3-thumb.png') }}" class="img-thumbnail mt-2" style="max-height:120px;">
                            @endif
                        </div>

                    </div>
                </div>
            </div>
            <div class="card-footer bg-light">
                <div class="row justify-content-between align-items-center">
                    <div class="col-md">
                        <h5 class="mb-2 mb-md-0">Update Profile</h5>
                    </div>
                    <div class="col-auto">
                        <button type="reset" class="btn btn-secondary me-2">Reset</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Update
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <style>
        .card.border {
            border-color: #e3e6f0 !important;
        }

        .card-header.bg-light {
            background-color: #f8f9fc !important;
        }

        .text-danger {
            color: #e63757 !important;
        }

        .disabled-toggle {
            opacity: 0.6;
            cursor: not-allowed !important;
        }

        .disabled-toggle-label {
            color: #6c757d !important;
        }
    </style>
@endsection

@push('custom-script')
    <script>
        const password = document.getElementById('password');
        const confirm = document.getElementById('password_confirmation');

        function validatePassword() {
            if (password.value !== confirm.value) {
                confirm.setCustomValidity("Passwords do not match");
            } else {
                confirm.setCustomValidity('');
            }
        }

        password.addEventListener('keyup', validatePassword);
        confirm.addEventListener('keyup', validatePassword);
    </script>
@endpush
