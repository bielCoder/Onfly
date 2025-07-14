<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
          return [
            'user_id' => 1,
            'travelling_id' => 1,
            'status' => 'pendente',
            'active' => true,
            'departure_date' => now(),
            'return_date' => now()->addDays(5)
        ];
    }
}
