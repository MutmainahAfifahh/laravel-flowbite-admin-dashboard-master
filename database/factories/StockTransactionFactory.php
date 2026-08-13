<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\User;
use App\Models\Supplier;
use App\Models\StockTransaction;
use Illuminate\Database\Eloquent\Factories\Factory;

class StockTransactionFactory extends Factory
{
    protected $model = StockTransaction::class;

    public function definition(): array
    {
        $notes = [
            'Penyesuaian stok hasil perhitungan fisik gudang (Stock Opname)',
            'Pemeriksaan berkala kondisi fisik barang di rak gudang',
            'Restock rutin pasokan barang dari supplier',
            'Pengeluaran stok barang untuk pengiriman pesanan toko',
            'Penerimaan pasokan barang tambahan kuartal ini',
            'Pengeluaran barang kebutuhan distribusi cabang',
            'Penyesuaian selisih kuantitas fisik dan sistem',
        ];

        return [
            'product_id'  => Product::inRandomOrder()->first()?->id ?? Product::factory(),
            'supplier_id' => Supplier::inRandomOrder()->first()?->id ?? null,
            'user_id'     => User::inRandomOrder()->first()?->id ?? User::factory(),
            'type'        => fake()->randomElement(['Masuk', 'Keluar']),
            'quantity'   => fake()->numberBetween(1, 50),
            'date'       => fake()->dateTimeBetween('-1 month', 'now')->format('Y-m-d'),
            'status'     => fake()->randomElement(['Completed', 'Pending', 'Cancelled']),
            'notes'      => fake()->randomElement($notes),
        ];
    }
}
