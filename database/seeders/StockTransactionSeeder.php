<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\StockTransaction;

class StockTransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = Product::all();
        $suppliers = Supplier::all();

        if ($products->isEmpty()) {
            return;
        }

        $inboundNotes = [
            'Penerimaan barang dari supplier (Restock rutin)',
            'Restock pasokan barang bulanan dari distributor',
            'Penerimaan barang tambahan untuk memenuhi pesanan',
            'Penerimaan pesanan barang baru kondisi segel aman',
            'Penerimaan produk dari supplier utama',
            'Stok masuk hasil pengadaan barang kuartal',
            'Penerimaan kelengkapan barang stok gudang',
        ];

        $outboundNotes = [
            'Pengiriman barang untuk permintaan unit toko cabang',
            'Pengeluaran barang untuk kebutuhan inventaris operasional',
            'Pengeluaran produk untuk memenuhi pesanan pelanggan',
            'Pengiriman barang pesanan grosir',
            'Pengeluaran stok barang sesuai nota kirim',
            'Pengeluaran barang distribusi cabang area Jabodetabek',
            'Pengeluaran sampel barang untuk pameran',
        ];

        foreach ($products as $index => $product) {
            $randomSupplierId = $suppliers->isEmpty() ? null : $suppliers->random()->id;
            
            StockTransaction::factory()->create([
                'product_id' => $product->id,
                'supplier_id' => $randomSupplierId,
                'type' => 'Masuk',
                'quantity' => rand(10, 50),
                'status' => 'Completed',
                'notes' => $inboundNotes[$index % count($inboundNotes)],
            ]);

            StockTransaction::factory()->create([
                'product_id' => $product->id,
                'supplier_id' => $randomSupplierId,
                'type' => 'Keluar',
                'quantity' => rand(1, 15),
                'status' => 'Completed',
                'notes' => $outboundNotes[$index % count($outboundNotes)],
            ]);
        }
    }
}
