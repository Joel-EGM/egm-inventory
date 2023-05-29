<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\OrderDetail;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class OrderDetailFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [

        'supplier_id' => $this->faker->numberBetween(1, 203),
        'item_id' => $this->faker->numberBetween(1, 10),
        'order_id'=> $this->faker->numberBetween(1, 20),
        'unit_id'=> $this->faker->numberBetween(1, 10),
        'quantity' =>$this->faker->numberBetween(1, 20),
        'price' => $this->faker->numberBetween(100, 200),
        'total_amount' => $this->faker->numberBetween(1000, 200),
        'order_status' => $this->faker->randomElement(['pending','received']),
        'is_received' => $this->faker->numberBetween(0, 1),
        ];
    }
}
