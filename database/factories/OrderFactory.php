<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\Employee;
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
            'client_id' => Client::all()->random()->id,
            'employee_id' => Employee::all()->random()->id,
            'order_date' => $this->faker->date,
            'status' => $this->faker->word,
            'total' => $this->faker->numberBetween(500, 10000),
            'payment_method' => $this->faker->word,
            'is_credit' => $this->faker->boolean,
            'is_paid' => $this->faker->boolean
        ];
    }
}
