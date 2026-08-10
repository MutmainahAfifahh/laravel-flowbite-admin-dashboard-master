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
            'image' => fake()->randomElement([
                'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=500&q=80',
                'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=500&q=80',
                'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=500&q=80',
                'https://images.unsplash.com/photo-1560343090-f0409e92791a?w=500&q=80',
                'https://images.unsplash.com/photo-1583394838336-acd977736f90?w=500&q=80',
            ]),
            'minimum_stock' => 5,
        ];
    }
}