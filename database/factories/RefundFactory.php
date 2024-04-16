<?php

namespace Database\Factories;

use App\Models\Market;
use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Refund>
 */
class RefundFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'order_id' => Order::all()->random()->id,
            'market_id' => Market::all()->random()->id,
            'reason' => $this->faker->sentence,
            'status' => $this->faker->sentence,
            'refund_date' => $this->faker->date
        ];
    }
}
