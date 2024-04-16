<?php

namespace Database\Factories;

use App\Models\SupplyRequest;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductTransfer>
 */
class ProductTransferFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $status = ['pending', 'approved', 'in progress', 'delivered', 'rejected'];
        return [
            'from_location_id' => $this->faker->numberBetween(1, 10),
            'to_location_id' => $this->faker->numberBetween(1, 10),
            'supply_request_id' => SupplyRequest::all()->random()->id,
            'status' => $this->faker->randomElement($status),
            'transfer_date' => $this->faker->date,
            'from_location_type' => $this->faker->randomElement(['market', 'warehouse']),
            'to_location_type' => $this->faker->randomElement(['market', 'warehouse']),
        ];
    }
}
