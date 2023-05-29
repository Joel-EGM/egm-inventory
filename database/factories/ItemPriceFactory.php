<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\ItemPrice;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ItemPrice>
 */
class ItemPriceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'supplier_id' => $this->faker->numberBetween(1, 202),
            'item_id' => $this->faker->numberBetween(1, 202),
            'price_perUnit' => $this->faker->numberBetween(50, 100),
            'price_perPieces' => $this->faker->numberBetween(5, 100),
        ];
    }
}
