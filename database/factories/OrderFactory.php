<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Order;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'branch_id' =>$this->faker->numberBetween(1, 4),
            'order_date' => $this->faker->dateTimeBetween('-1 year', '+1 week'),
            'order_status'=> $this->faker->randomElement(['pending','incomplete','received']),
            'created_by' => $this->faker->numberBetween(1, 3)
        ];
    }
}
