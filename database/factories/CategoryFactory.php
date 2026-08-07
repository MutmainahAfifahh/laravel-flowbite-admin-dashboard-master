<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class  CategoryFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        try {
            $name = fake()->unique()->randomElement([
                'Elektronik',
                'Peralatan Kantor',
                'Aksesoris Komputer',
                'Perlengkapan Rumah',
                'Pakaian & Fashion',
                'Komponen Komputer',
                'Gadget & Smartphone',
                'Suku Cadang'
            ]);
        } catch (\OverflowException $e) {
            $name = 'Kategori ' . fake()->unique()->numberBetween(100, 999);
        }

        return [
            'name' => $name,
            'description' => 'Kategori produk untuk memenuhi kebutuhan barang persediaan.',
        ];
    }
}