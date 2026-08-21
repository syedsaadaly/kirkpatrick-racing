<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('admin!@#'),

        ]);
        $admin->assignRole('admin');
        $admin = User::create([
            'name' => 'User',
            'email' => 'user@user.com',
            'password' => Hash::make('user!@#'),

        ]);
        $admin->assignRole('user');
    }
}
