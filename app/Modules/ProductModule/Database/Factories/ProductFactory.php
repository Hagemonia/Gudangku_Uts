<?php

namespace App\Modules\ProductModule\Database\Factories;

use App\Modules\ProductModule\Models\Product;
use App\Modules\ProductModule\Models\Unit;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        return [
            'sku' => strtoupper(fake()->unique()->bothify('BRG-####??')),
            'name' => ucfirst(fake()->unique()->words(3, true)),
            'description' => fake()->sentence(),
            'unit_id' => Unit::factory(),
            'min_stock' => fake()->numberBetween(5, 20),
            'stock' => 0,
            'is_active' => true,
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn () => ['is_active' => false]);
    }
}
