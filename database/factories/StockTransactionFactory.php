<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\User;
use App\Models\StockTransaction;
use Illuminate\Database\Eloquent\Factories\Factory;

class StockTransactionFactory extends Factory
{
    protected $model = StockTransaction::class;

    public function definition(): array
    {
        return [
            'product_id' => Product::inRandomOrder()->first()?->id ?? Product::factory(),
            'user_id'    => User::inRandomOrder()->first()?->id ?? User::factory(),
            'type'       => fake()->randomElement(['Masuk', 'Keluar']),
            'quantity'   => fake()->numberBetween(1, 50),
            'date'       => fake()->dateTimeBetween('-1 month', 'now')->format('Y-m-d'),
            'status'     => fake()->randomElement(['Completed', 'Pending', 'Cancelled']),
            'notes'      => fake()->sentence(),
        ];
    }
}
