<?php

namespace Database\Factories;

use App\Models\StockIn;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\StockIn>
 */
class StockInFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    protected $model = StockIn::class;
    public function definition(): array
    {
        $originalPrice = $this->faker->numberBetween(500, 30000);
        $quantity = $this->faker->numberBetween(1, 5000);
        $subTotal = $originalPrice * $quantity;
        $grandTotal = $subTotal;

        return [
            'warehouse_id' => $this->faker->numberBetween(1, 2),
            'product_id' => $this->faker->numberBetween(1, 100),
            'unit_id' => $this->faker->numberBetween(1, 2),
            'scale_id' => $this->faker->numberBetween(1, 2),
            'original_price' => $originalPrice,
            'quantity' => $quantity,
            'sub_total' => $subTotal,
            'grand_total' => $grandTotal,
            'convert_weight' => $this->faker->numberBetween(1, 10),
            'converted_weight' => $this->faker->numberBetween(1, 10),
            'volume' => $this->faker->numberBetween(1, 10),
            'green' => $this->faker->numberBetween(1, 10),
            'yellow' => $this->faker->numberBetween(1, 10),
            'red' => $this->faker->numberBetween(1, 5),
            'line' => $this->faker->numberBetween(1, 100),
            'level' => $this->faker->numberBetween(1, 100),
            'stand' => $this->faker->numberBetween(1, 100),
        ];
    }
}
