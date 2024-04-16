<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductExistence>
 */
class ProductExistenceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'product_id' => Product::all()->random()->id,
            'location_id' => $this->faker->numberBetween(1, 10),
            'location_type' => $this->faker->randomElement(['market', 'warehouse']),
            'quantity' => $this->faker->numberBetween(1, 100),
        ];
    }
}
