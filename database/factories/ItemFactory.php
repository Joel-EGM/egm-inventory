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
            'item_name' => $this->faker->word,
            'unit_name' => $this->faker->word,
            'pieces_perUnit' => $this->faker->randomDigit(5),
        ];
    }
}
