@extends('admin.layouts.app')
@section('content')

@include('admin.layouts.partials.topbar')

<div class="card mb-3">
    <div class="card-body">
        <div class="row flex-between-center">
            <div class="col-md">
                <h5 class="mb-2 mb-md-0">Edit Role</h5>
                <p class="text-muted mb-0">Update role details and permissions</p>
            </div>
            <div class="col-auto">
                <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrowleft me-1"></i> Back to Roles
                </a>
            </div>
        </div>
    </div>
</div>

<form action="{{ route('admin.roles.update', $role->id) }}" method="POST">
    @csrf
    @method('PUT')

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
                               value="{{ old('name', $role->name) }}"
                               placeholder="Enter role name"
                               required>
                        @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            Guard Name
                        </label>
                        <select class="form-select @error('guard_name') is-invalid @enderror" name="guard_name">
                            <option value="web" {{ old('guard_name', $role->guard_name) == 'web' ? 'selected' : '' }}>Web</option>
                            <option value="api" {{ old('guard_name', $role->guard_name) == 'api' ? 'selected' : '' }}>API</option>
                            <option value="admin" {{ old('guard_name', $role->guard_name) == 'admin' ? 'selected' : '' }}>Admin</option>
                        </select>
                        @error('guard_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- <div class="col-12">
                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            Assign Permissions <span class="text-danger">*</span>
                        </label>
                        
                        @error('permissions')
                        <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                        
                        @php
                            $oldPermissions = old('permissions', $role->permissions->pluck('id')->toArray());
                        @endphp
                        
                        <select class="form-select @error('permissions') is-invalid @enderror" 
                                name="permissions[]" 
                                id="permissions-select" 
                                multiple="multiple" 
                                required>
                            @foreach($permissions->groupBy(function($item) {
                                $parts = explode('.', $item->name);
                                return $parts[0] ?? 'general';
                            }) as $group => $groupPermissions)
                                <optgroup label="{{ ucfirst($group) }}">
                                    @foreach($groupPermissions as $permission)
                                        <option value="{{ $permission->id }}" 
                                                {{ in_array($permission->id, $oldPermissions) ? 'selected' : '' }}>
                                            {{ $permission->name }}
                                        </option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                        @error('permissions')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">
                            Select one or more permissions. You can search by permission name.
                        </small>
                    </div>
                </div> --}}
                <div class="col-12">
                    <label class="form-label fw-bold mb-3">
                        Assign Permissions <span class="text-danger">*</span>
                    </label>
                
                    <div class="card p-4 @error('permissions') border-danger @enderror shadow-sm">
                        <div class="row">
                            @php
                                $oldPermissions = old('permissions', isset($role) ? $role->permissions->pluck('id')->toArray() : []);
                            @endphp
                
                            @foreach($permissions->groupBy(function($item) {
                                $parts = explode('.', $item->name);
                                return $parts[0] ?? 'general';
                            }) as $group => $groupPermissions)
                                
                                <div class="col-md-4 mb-4 permission-group">
                                    <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-3">
                                        <h6 class="text-primary fw-bold mb-0 text-uppercase small">
                                            <i class="fas fa-folder-open me-1"></i> {{ $group }}
                                        </h6>
                                        <label class="btn btn-xs btn-light-primary py-0 px-2 m-0 small border cursor-pointer" 
                                               style="font-size: 10px; cursor: pointer;">
                                            <input type="checkbox" class="d-none group-checker" data-group-target="{{ $group }}">
                                            Select All
                                        </label>
                                    </div>
                
                                    <div class="permission-list" style="max-height: 250px; overflow-y: auto;">
                                        @foreach($groupPermissions as $permission)
                                            <div class="form-check mb-2">
                                                <input class="form-check-input permission-checkbox-{{ $group }} checkbox-item" 
                                                       type="checkbox" 
                                                       name="permissions[]" 
                                                       value="{{ $permission->id }}" 
                                                       id="perm-{{ $permission->id }}"
                                                       {{ in_array($permission->id, $oldPermissions) ? 'checked' : '' }}>
                                                <label class="form-check-label small cursor-pointer" for="perm-{{ $permission->id }}">
                                                    {{ ucwords(str_replace(['.', '_', $group], [' ', ' ', ''], $permission->name)) }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                
                        @error('permissions')
                            <div class="text-danger small mt-2">
                                <i class="fas fa-exclamation-circle me-1"></i> {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            Created At
                        </label>
                        <input type="text"
                               class="form-control"
                               value="{{ $role->created_at->format('d M Y, h:i A') }}"
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
                               value="{{ $role->updated_at->format('d M Y, h:i A') }}"
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
                    <h5 class="mb-2 mb-md-0">Update Role</h5>
                    <p class="text-muted mb-0">Save changes to this role</p>
                </div>
                <div class="col-auto">
                    <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary me-2">
                        <i class="fas fa-times me-1"></i> Cancel
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Update Role
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>

@endsection

@push('custom-script')
<!-- Include Select2 CSS and JS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $(document).ready(function() {
        // Initialize Select2
        $('#permissions-select').select2({
            theme: 'bootstrap-5',
            placeholder: 'Select permissions...',
            allowClear: true,
            width: '100%',
            closeOnSelect: false,
            templateResult: formatPermission,
            templateSelection: formatPermissionSelection
        });

        // Format permission display in dropdown
        function formatPermission(permission) {
            if (!permission.id) {
                return permission.text;
            }
            
            var $permission = $(
                '<div class="permission-item">' + 
                    '<span>' + permission.text + '</span>' +
                '</div>'
            );
            return $permission;
        }

        // Format selected permission display
        function formatPermissionSelection(permission) {
            if (!permission.id) {
                return permission.text;
            }
            
            // Show only the permission name without group label
            var text = permission.text;
            // Remove any group prefix if present
            text = text.split('›').pop() || text;
            return text.trim();
        }

        // Auto-format role name
        $('input[name="name"]').on('blur', function() {
            let value = $(this).val().trim().toLowerCase();
            value = value.replace(/\s+/g, '_');
            value = value.replace(/[^a-z0-9_]/g, '');
            $(this).val(value);
        });

        // Form validation
        $('form').on('submit', function(e) {
            const permissions = $('#permissions-select').val();
            if (!permissions || permissions.length === 0) {
                e.preventDefault();
                $('#permissions-select').addClass('is-invalid');
                $('#permissions-select').after('<div class="invalid-feedback d-block">Please select at least one permission.</div>');
                $('#permissions-select').focus();
            }
        });

        // Remove validation on change
        $('#permissions-select').on('change', function() {
            $(this).removeClass('is-invalid');
            $(this).next('.invalid-feedback').remove();
        });

        document.querySelectorAll('.group-checker').forEach(checker => {
            checker.addEventListener('change', function() {
                const group = this.getAttribute('data-group-target');
                const checkboxes = document.querySelectorAll('.permission-checkbox-' + group);
                checkboxes.forEach(cb => cb.checked = this.checked);
            });
        });
    });
</script>

{{-- <style>
    /* Custom Select2 styling */
    .select2-container--bootstrap-5 .select2-selection--multiple {
        min-height: 46px;
        padding: 5px;
    }
    
    .select2-container--bootstrap-5 .select2-selection--multiple .select2-selection__choice {
        background-color: #0d6efd;
        border-color: #0d6efd;
        color: white;
        margin: 3px;
        padding: 5px 10px;
        border-radius: 4px;
    }
    
    .select2-container--bootstrap-5 .select2-selection--multiple .select2-selection__choice__remove {
        color: white;
        margin-right: 5px;
    }
    
    .select2-container--bootstrap-5 .select2-dropdown {
        border: 1px solid #dee2e6;
        border-radius: 0.375rem;
    }
    
    .select2-container--bootstrap-5 .select2-search--inline .select2-search__field {
        color: #212529;
    }
    
    /* Permission item styling in dropdown */
    .permission-item {
        padding: 8px 12px;
    }
    
    .permission-item:hover {
        background-color: #f8f9fa;
    }
    
    /* Optgroup styling */
    .select2-results__option[role="group"] {
        padding: 0;
    }
    
    .select2-results__option[role="group"] .select2-results__group {
        background-color: #f8f9fa;
        padding: 8px 12px;
        font-weight: 600;
        color: #495057;
        border-bottom: 1px solid #dee2e6;
    }
    
    .select2-results__option[role="group"] .select2-results__options--nested {
        padding-left: 20px;
    }
</style> --}}
@endpush