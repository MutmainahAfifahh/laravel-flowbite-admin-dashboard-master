<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;
use App\Models\Supplier;
use App\Models\Product;
use App\Models\ProductAttribute;
use App\Models\StockTransaction;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Akun Pengguna (Sesuai ENUM role: Admin, Manajer Gudang, Staff Gudang)
        User::firstOrCreate(
            ['email' => 'admin@stockify.com'],
            [
                'name' => 'Admin Stockify',
                'role' => 'Admin',
                'password' => Hash::make('password'),
            ]
        );

        User::firstOrCreate(
            ['email' => 'manajer@stockify.com'],
            [
                'name' => 'Manajer Gudang',
                'role' => 'Manajer Gudang',
                'password' => Hash::make('password'),
            ]
        );

        User::firstOrCreate(
            ['email' => 'staff@stockify.com'],
            [
                'name' => 'Staff Gudang',
                'role' => 'Staff Gudang',
                'password' => Hash::make('password'),
            ]
        );

        User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Admin Stockify (Gmail)',
                'role' => 'Admin',
                'password' => Hash::make('password123'),
            ]
        );

        // 2. Data Dummy Master & Transaksi
        Category::factory(5)->create();          
        Supplier::factory(5)->create();            
        Product::factory(25)->create();           
        ProductAttribute::factory(30)->create();  
        StockTransaction::factory(20)->create();
    }
}