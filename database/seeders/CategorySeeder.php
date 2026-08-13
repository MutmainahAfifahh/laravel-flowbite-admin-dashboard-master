<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $existingCount = Category::count();

        if ($existingCount >= 8) {
            return;
        }

        $defaults = [
            ['name' => 'Elektronik', 'description' => 'Kategori elektronik'],
            ['name' => 'Peralatan Kantor', 'description' => 'Kategori peralatan kantor'],
            ['name' => 'Aksesoris Komputer', 'description' => 'Kategori aksesoris komputer'],
            ['name' => 'Perlengkapan Rumah', 'description' => 'Kategori perlengkapan rumah'],
            ['name' => 'Pakaian & Fashion', 'description' => 'Kategori fashion'],
            ['name' => 'Komponen Komputer', 'description' => 'Kategori komponen komputer'],
            ['name' => 'Gadget & Smartphone', 'description' => 'Kategori gadget'],
            ['name' => 'Suku Cadang', 'description' => 'Kategori suku cadang'],
        ];

        foreach ($defaults as $item) {
            Category::firstOrCreate(['name' => $item['name']], $item);
        }
    }
}
