<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Supplier;
use App\Models\Warehouse;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SupplyRequest>
 */
class SupplyRequestFactory extends Factory
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
            'product_id' => Product::all()->random()->id,
            'supplier_id' => Supplier::all()->random()->id,
            'warehouse_id' => Warehouse::all()->random()->id,
            'quantity' => $this->faker->numberBetween(1, 100),
            'status' => $this->faker->randomElement($status),
        ];
    }
}
