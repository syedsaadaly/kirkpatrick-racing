@extends('account.layouts.app')
@section('content')
    <div class="card mb-3">
        <div class="card-body">
            <div class="row flex-between-center">
                <div class="col-md">
                    <h5 class="mb-2 mb-md-0">Profile Settings</h5>
                    <p class="text-muted mb-0">Update your account details</p>
                </div>
            </div>
        </div>
    </div>

    <form action="{{ route('account.profile.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="card">
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Full Name</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                           name="name" value="{{ old('name', auth()->user()->name) }}">
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Email Address</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                           name="email" value="{{ old('email', auth()->user()->email) }}">
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">New Password</label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror"
                           name="password" placeholder="Leave blank to keep current password">
                    @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Confirm New Password</label>
                    <input type="password" class="form-control" name="password_confirmation">
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Profile Image</label>
                    <input type="file" class="form-control @error('profile_image') is-invalid @enderror"
                           name="profile_image" accept="image/*">
                    @error('profile_image')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    @php $profileImage = auth()->user()->getFirstMediaUrl('profile'); @endphp
                    @if ($profileImage)
                        <img src="{{ $profileImage }}" class="img-thumbnail mt-2" style="max-height:80px;">
                    @endif
                </div>
            </div>
            <div class="card-footer bg-light">
                <div class="row justify-content-between align-items-center">
                    <div class="col-auto">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Update Profile
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
