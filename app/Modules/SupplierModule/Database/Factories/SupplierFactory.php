<?php

namespace App\Modules\SupplierModule\Database\Factories;

use App\Modules\SupplierModule\Models\PaymentTerm;
use App\Modules\SupplierModule\Models\Supplier;
use Illuminate\Database\Eloquent\Factories\Factory;

class SupplierFactory extends Factory
{
    protected $model = Supplier::class;

    public function definition(): array
    {
        return [
            'code' => fake()->unique()->numerify('SUP-####'),
            'name' => fake()->unique()->company(),
            'email' => fake()->unique()->companyEmail(),
            'phone' => fake()->numerify('08##########'),
            'address' => fake()->address(),
            'payment_term_id' => PaymentTerm::factory(),
            'is_active' => true,
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn () => ['is_active' => false]);
    }
}
