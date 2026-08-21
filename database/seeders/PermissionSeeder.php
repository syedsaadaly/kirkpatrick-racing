<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run()
    {
        $modules = [
            'dashboard',
            'calendar',
            'pagebuilder',
            'cms',
            'settings',
            'categories',
            'products',
            'orders',
            'invoices'
        ];

        $actions = ['view', 'create', 'edit', 'delete', 'manage'];

        foreach ($modules as $module) {
            foreach ($actions as $action) {
                Permission::firstOrCreate(
                    ['name' => "$module.$action", 'guard_name' => 'web']
                );
            }
        }
    }
}
