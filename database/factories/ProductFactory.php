<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Product::class;
    public function definition(): array
    {
        return [
            'country_id' => $this->faker->numberBetween(1, 242),
            'category_id' => $this->faker->numberBetween(1, 10),
            'sub_category_id' => $this->faker->numberBetween(1, 10),
            'name_en' => $this->faker->words(2, true),
            'name_mm' => $this->faker->words(2, true),
            'sku' => strtoupper($this->faker->bothify('???-#####')),
        ];
    }
}
