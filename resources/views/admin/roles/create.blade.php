@extends('admin.layouts.app')
@section('content')

@include('admin.layouts.partials.topbar')

<div class="card mb-3">
    <div class="card-body">
        <div class="row flex-between-center">
            <div class="col-md">
                <h5 class="mb-2 mb-md-0">Create New Role</h5>
                <p class="text-muted mb-0">Add a new role with permissions</p>
            </div>
            <div class="col-auto">
                <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Back to Roles
                </a>
            </div>
        </div>
    </div>
</div>

<form action="{{ route('admin.roles.store') }}" method="POST">
    @csrf

    <div class="card">
        <div class="card-header bg-light">
            <h6 class="mb-0">Role Details</h6>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            Role Name <span class="text-danger">*</span>
                        </label>
                        <input type="text"
                               class="form-control @error('name') is-invalid @enderror"
                               name="name"
                               value="{{ old('name') }}"
                               placeholder="Enter role name (e.g., admin, manager)"
                               required>
                        @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">
                            Use lowercase letters and underscores (e.g., admin, content_manager)
                        </small>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            Guard Name
                        </label>
                        <select class="form-select @error('guard_name') is-invalid @enderror" name="guard_name">
                            <option value="web" {{ old('guard_name', 'web') == 'web' ? 'selected' : '' }}>Web</option>
                            <option value="api" {{ old('guard_name') == 'api' ? 'selected' : '' }}>API</option>
                            <option value="admin" {{ old('guard_name') == 'admin' ? 'selected' : '' }}>Admin</option>
                        </select>
                        @error('guard_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
        <div class="card p-3 @error('permissions') border-danger @enderror">
            <div class="row">
                @foreach($permissions->groupBy(function($item) {
                    $parts = explode('.', $item->name);
                    return $parts[0] ?? 'general';
                }) as $group => $groupPermissions)
                    <div class="col-md-4 mb-4 permission-group">
                        <div class="d-flex justify-content-between align-items-center border-bottom pb-1 mb-2">
                            <h6 class="text-primary mb-0">{{ ucfirst($group) }}</h6>
                            <a href="javascript:void(0)" 
                               class="text-muted small select-all" 
                               data-group="{{ $group }}">Select All</a>
                        </div>
                        @foreach($groupPermissions as $permission)
                            <div class="form-check">
                                <input class="form-check-input permission-checkbox-{{ $group }}" 
                                       type="checkbox" 
                                       name="permissions[]" 
                                       value="{{ $permission->id }}" 
                                       id="perm-{{ $permission->id }}"
                                       {{ in_array($permission->id, old('permissions', [])) ? 'checked' : '' }}>
                                <label class="form-check-label" for="perm-{{ $permission->id }}">
                                    {{ str_replace($group . '.', '', $permission->name) }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-body">
            <div class="row justify-content-between align-items-center">
                <div class="col-md">
                    <h5 class="mb-2 mb-md-0">Create Role</h5>
                    <p class="text-muted mb-0">Save this role with selected permissions</p>
                </div>
                <div class="col-auto">
                    <button type="reset" class="btn btn-secondary me-2">
                        <i class="fas fa-undo me-1"></i> Reset
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Create Role
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
        $('input[name="name"]').on('blur', function() {
            let value = $(this).val().trim().toLowerCase();
            value = value.replace(/\s+/g, '_');
            value = value.replace(/[^a-z0-9_]/g, '');
            $(this).val(value);
        });

        $('form').on('submit', function(e) {
            const checkedCount = $('input[name="permissions[]"]:checked').length;
            
            if (checkedCount === 0) {
                e.preventDefault();

                alert('Please select at least one permission before saving.');

                $('.permission-container').addClass('border-danger');
            }
        });

        $(document).on('click', '.select-all', function(e) {
            e.preventDefault();
            
            const groupName = $(this).data('group');
            const checkboxes = $('.permission-checkbox-' + groupName);
            
            const allChecked = checkboxes.length === checkboxes.filter(':checked').length;

            checkboxes.prop('checked', !allChecked);

            $(this).text(allChecked ? 'Select All' : 'Unselect All');
        });

        $(document).on('change', 'input[name="permissions[]"]', function() {
            if ($('input[name="permissions[]"]:checked').length > 0) {
                $('.permission-container').removeClass('border-danger');
            }
        });
    });
</script>


@endpush