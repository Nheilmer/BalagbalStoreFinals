<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin user
        $admin = User::create([
            'role_id' => 1, // Admin role
            'username' => 'admin',
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
        ]);

        // Customer user
        $customerUser = User::create([
            'role_id' => 2, // Customer role
            'username' => 'breanna',
            'name' => 'Breanna Cruickshank',
            'email' => 'breanna@example.com',
            'password' => bcrypt('password'),
        ]);
    }
}
