<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Supplier;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class SupplierFactory extends Factory
{
    protected $model = Supplier::class;

    public function definition()
    {
        return [
            'suppliers_name' => $this->faker->firstNameMale(),
            'suppliers_email' => $this->faker->email(),
            'suppliers_contact' => $this->faker->randomNumber(5, true)
        ];
    }
}
