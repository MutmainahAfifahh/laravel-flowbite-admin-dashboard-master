<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\ProductAttribute;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductAttributeFactory extends Factory
{
    protected $model = ProductAttribute::class;

    public function definition(): array
    {
        $name = fake()->randomElement(['Warna', 'Ukuran', 'Berat', 'Garansi', 'Bahan']);
        $value = match ($name) {
            'Warna' => fake()->randomElement(['Hitam', 'Putih', 'Merah', 'Biru', 'Silver', 'Gold']),
            'Ukuran' => fake()->randomElement(['S', 'M', 'L', 'XL', 'XXL', '14 Inci', '15.6 Inci']),
            'Berat' => fake()->numberBetween(1, 10) . ' kg',
            'Garansi' => fake()->randomElement(['1 Tahun', '2 Tahun', '6 Bulan', 'Garansi Resmi']),
            'Bahan' => fake()->randomElement(['Aluminium', 'Plastik ABS', 'Katun', 'Kulit Sintetis', 'Stainless Steel']),
        };

        return [
            'product_id' => Product::inRandomOrder()->first()?->id ?? Product::factory(),
            'name' => $name,
            'value' => $value,
        ];
    }
}