<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Supplier;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        return [
            'category_id' => Category::inRandomOrder()->first()?->id ?? Category::factory(),
            'supplier_id' => Supplier::inRandomOrder()->first()?->id ?? Supplier::factory(),
            'name' => fake()->words(3, true),
            'sku' => 'PRD-' . strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 6)),
            'description' => fake()->sentence(),
            'purchase_price' => fake()->numberBetween(10000, 500000),
            'selling_price' => fake()->numberBetween(50000, 1000000),
            'image' => 'https://picsum.photos/seed/' . fake()->uuid() . '/640/480',
            'minimum_stock' => 5,
        ];
    }
}