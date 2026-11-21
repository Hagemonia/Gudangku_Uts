<?php

namespace App\Modules\ProductModule\Database\Factories;

use App\Modules\ProductModule\Models\Unit;
use Illuminate\Database\Eloquent\Factories\Factory;

class UnitFactory extends Factory
{
    protected $model = Unit::class;

    public function definition(): array
    {
        return [
            'name' => ucfirst(fake()->unique()->word()),
            'symbol' => fake()->lexify('???'),
        ];
    }
}
