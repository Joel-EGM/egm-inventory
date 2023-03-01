<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Item;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class ItemFactory extends Factory
{
    protected $model = Item::class;

    public function definition()
    {
        return [
            'item_name' => $this->faker->lastName(),
            'unit_name' => $this->faker->cityPrefix(),
            'pieces_perUnit' => $this->faker->numberBetween(0, 100),
            'fixed_unit' => $this->faker->numberBetween(0, 1),
        ];
    }
}
