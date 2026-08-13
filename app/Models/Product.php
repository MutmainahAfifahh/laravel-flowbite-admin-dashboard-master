<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory, \App\Traits\LogsActivity;

    protected $table = 'products';

    protected $fillable = [
        'category_id',
        'supplier_id',
        'name',
        'sku',
        'description',
        'purchase_price', 
        'selling_price',
        'image',
        'minimum_stock',
    ];

    /**
     * Otomatis menyertakan atribut virtual 'image_url' 
     * saat model di-convert ke Array atau JSON (misal via json_encode)
     */
    protected $appends = [
        'image_url',
        'stock',
    ];

    /**
     * Accessor untuk menghitung stok aktual berdasarkan riwayat transaksi yang disetujui
     */
    public function getStockAttribute(): int
    {
        $masuk = $this->transactions()
            ->where('type', 'Masuk')
            ->whereIn('status', ['Completed', 'Diterima'])
            ->sum('quantity');

        $keluar = $this->transactions()
            ->where('type', 'Keluar')
            ->whereIn('status', ['Completed', 'Dikeluarkan'])
            ->sum('quantity');

        return $masuk - $keluar;
    }

    /**
     * Accessor untuk menghasilkan URL lengkap gambar produk
     */
    public function getImageUrlAttribute(): ?string
    {
        if (empty($this->image)) {
            return null;
        }

        // Jika path berupa URL luar (misal dari seeder/Unsplash/HTTP)
        if (Str::startsWith($this->image, ['http://', 'https://'])) {
            return $this->image;
        }

        // Hapus 'storage/' atau slash di depan jika ada agar tidak double
        $cleanPath = ltrim(str_replace('storage/', '', $this->image), '/');

        // Mengembalikan URL publik yang presisi
        return asset('storage/' . $cleanPath);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    public function attributes(): HasMany
    {
        return $this->hasMany(ProductAttribute::class, 'product_id');
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(StockTransaction::class, 'product_id');
    }
}