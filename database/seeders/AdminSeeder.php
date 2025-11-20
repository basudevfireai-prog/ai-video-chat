<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use App\Models\Admin;

class AdminSeeder extends Seeder
{
    public function run()
    {
        // Ensure admin role exists
        $adminRole = Role::firstOrCreate(['name' => 'admin']);

        // Create admin user
        $admin = Admin::firstOrCreate(
            ['email' => 'neazmorshed407@gmail.com'],
            [
                'name'     => 'Super Admin',
                'password' => Hash::make('password'),
                'mobile'   => '01700000000',   // optional
            ]
        );

        // Assign role
        if (!$admin->hasRole('admin')) {
            $admin->assignRole($adminRole);
        }

        echo "Admin user created & admin role assigned successfully.\n";
    }
}
