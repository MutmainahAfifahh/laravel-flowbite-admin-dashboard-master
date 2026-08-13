<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Supplier>
 */
class SupplierFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        try {
            $name = fake()->unique()->randomElement([
                'PT Tech Utama Indonesia',
                'CV Jaya Elektronik',
                'PT Logistic Express Nusantara',
                'PT Indo Distribusi Sarana',
                'CV Sumber Makmur Jaya',
                'PT Global Niaga Sejahtera'
            ]);
        } catch (\Throwable $e) {
            $name = 'PT ' . fake()->company();
        }

        return [
            'name' => $name,
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'address' => fake()->address(),
        ];
    }
}