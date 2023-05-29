<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Branch;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class BranchFactory extends Factory
{
    protected $model = Branch::class;

    public function definition()
    {
        return [
            'branch_name' => $this->faker->firstNameMale(),
            'branch_address' => $this->faker->streetName(),
            'branch_contactNo' => $this->faker->randomNumber(5, true),
            'status' => $this->faker->numberBetween(0, 1)
        ];
    }
}
