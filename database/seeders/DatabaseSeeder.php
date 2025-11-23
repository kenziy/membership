<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create admin user
        User::create([
            'first_name' => 'System',
            'last_name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'username' => 'admin',
            'phone_number' => '09123456785',
            'status' => 2,
            'member_id' => 'MBR-ADMIN-001',
        ]);

        // Create sample users
        // User::factory(2)->create();
    }
}