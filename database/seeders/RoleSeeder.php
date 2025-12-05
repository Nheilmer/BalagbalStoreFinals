<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        Role::create([
            'name' => 'Admin',
            'description' => 'Administrator with full permissions',
        ]);

        Role::create([
            'name' => 'Customer',
            'description' => 'Regular customer role',
        ]);
    }
}
