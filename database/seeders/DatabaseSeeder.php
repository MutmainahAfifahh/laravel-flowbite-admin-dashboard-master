<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;
use App\Models\Supplier;
use App\Models\Product;
use App\Models\ProductAttribute;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Admin Stockify',
                'password' => Hash::make('password123'),
                'role' => 'admin',
            ]
        );

        Category::factory(5)->create();          
        Supplier::factory(5)->create();            
        Product::factory(25)->create();           
        ProductAttribute::factory(30)->create();  
    }
}