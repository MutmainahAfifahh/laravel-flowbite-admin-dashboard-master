<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\StockTransaction;

class StockTransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        StockTransaction::factory()->count(7)->create();
    }
}
