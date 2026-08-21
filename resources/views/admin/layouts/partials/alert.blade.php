@php
    $alertType = (isset($type) && $type === 'error') ? 'danger' : $type;
    $alertType = $alertType ?? 'info';
    $message = $message ?? 'An unknown alert occurred.';
@endphp

<div class="alert alert-{{ $alertType }} alert-dismissible fade show" role="alert">
    {{ $message }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
