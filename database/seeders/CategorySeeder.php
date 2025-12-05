<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::create([
            'name' => 'Electronics',
            'description' => 'Electronic devices and gadgets',
            'is_active' => true,
        ]);

        Category::create([
            'name' => 'Accessories',
            'description' => 'Tech accessories and peripherals',
            'is_active' => true,
        ]);

        Category::create([
            'name' => 'Office',
            'description' => 'Office equipment and supplies',
            'is_active' => true,
        ]);
    }
}
