<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Travel;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        return [
            'user_id' => 4,          // RELACIONAMENTO AUTOMÁTICO
            'travelling_id' => 1,  // RELACIONAMENTO AUTOMÁTICO
            'status' => 'pendente',
            'active' => true,
            'departure_date' => now(),
            'return_date' => now()->addDays(5),
        ];
    }
}
