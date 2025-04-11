<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

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
            'order_number' => $this->faker->unique()->numerify('ORD-#####'),
            'customer_name' => $this->faker->name(),
            'order_date' => $this->faker->date(),
            'status' => $this->faker->randomElement(['pending', 'completed', 'canceled']),
            'total_amount' => $this->faker->randomFloat(2, 10, 1000),
        ];
    }
}
