@extends('admin.layouts.app')
@section('content')
    @include('admin.layouts.partials.topbar')

    <div class="card">
        <div class="card-body text-center py-5">
            <h3 class="mb-2">Welcome back, {{ $user->name }}!</h3>
            @if ($roleName)
                <span class="badge bg-primary text-capitalize fs-10">{{ $roleName }}</span>
            @endif
        </div>
    </div>
@endsection
