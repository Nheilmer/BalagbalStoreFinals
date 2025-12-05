<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $costPrice = $this->faker->numberBetween(500, 5000);
        $markup = $this->faker->numberBetween(20, 60);
        $unitPrice = $costPrice * (1 + $markup / 100);

        return [
            'name' => $this->faker->words(3, true),
            'description' => $this->faker->paragraph(),
            'category_id' => $this->faker->numberBetween(1, 3),
            'cost_price' => $costPrice,
            'unit_price' => round($unitPrice, 2),
            'is_active' => $this->faker->boolean(85),
        ];
    }
}
