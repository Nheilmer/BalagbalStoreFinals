<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customer>
 */
class CustomerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // 'username' => $this->faker->unique()->userName(),
            // 'email' => $this->faker->unique()->safeEmail(),
            // 'password_hash' => bcrypt('password'),
            'user_id' => User::factory(), // automatically create a linked User
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'date_of_birth' => $this->faker->date(),
            'phone_number' => $this->faker->phoneNumber(),
            'address' => $this->faker->address(),
        ];
    }
}
