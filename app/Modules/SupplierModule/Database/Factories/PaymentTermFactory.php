<?php

namespace App\Modules\SupplierModule\Database\Factories;

use App\Modules\SupplierModule\Models\PaymentTerm;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentTermFactory extends Factory
{
    protected $model = PaymentTerm::class;

    public function definition(): array
    {
        return [
            'name' => fake()->unique()->bothify('Termin ###'),
            'days' => fake()->randomElement([0, 7, 14, 30, 45]),
            'description' => fake()->sentence(4),
        ];
    }
}
