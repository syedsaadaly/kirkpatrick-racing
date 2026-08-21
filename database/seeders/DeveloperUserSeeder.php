<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DeveloperUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $role = Role::firstOrCreate(
            ['name' => 'developer', 'guard_name' => 'web']
        );

        $developerUser = User::firstOrCreate(
            ['email' => 'developer@example.com'],
            [
                'name'     => 'Developer User',
                'password' => Hash::make('developer!@#'),
            ]
        );

        if (!$developerUser->hasRole('developer')) {
            $developerUser->assignRole($role);
            $developerUser->assignRole('admin');
        }
    }
}
