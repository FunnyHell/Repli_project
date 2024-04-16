<?php

namespace Database\Factories;

use App\Models\Feature;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductFeature>
 */
class ProductFeatureFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'feature_id' => Feature::all()->random()->id,
            'product_id' => Product::all()->random()->id,
            'value' => $this->faker->sentence
        ];
    }
}
