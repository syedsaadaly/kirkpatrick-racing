<?php

namespace App\Models;

use Spatie\Permission\Models\Role as SpatieRole;
use Spatie\Permission\Contracts\Role as RoleContract;
use Exception;

class Role extends SpatieRole implements RoleContract
{
    protected $guard_name = 'web'; 

    protected static $protectedRoles = ['admin', 'developer'];

    protected static function booted()
    {
        static::deleting(function ($role) {
            if (in_array($role->name, self::$protectedRoles)) {
                throw new Exception("The '{$role->name}' role is protected.");
            }
        });
    }
}
