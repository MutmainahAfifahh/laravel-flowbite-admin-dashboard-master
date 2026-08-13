@extends('layouts.dashboard')

@section('content')
<div class="p-4 bg-white block border-b border-gray-200 lg:mt-1.5 dark:bg-gray-800 dark:border-gray-700">
    <div class="w-full mb-4">
        <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl dark:text-white mb-4">Edit Produk: {{ $product->name }}</h1>

        @if ($errors->any())
            <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400" role="alert">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="p-4 bg-gray-50 rounded-lg dark:bg-gray-700 max-w-3xl">
            <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Nama Produk -->
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama Produk <span class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $product->name) }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-800 dark:border-gray-600 dark:text-white" required>
                    </div>

                    <!-- Kode SKU -->
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">SKU (Kode Produk)</label>
                        <input type="text" name="sku" value="{{ old('sku', $product->sku) }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-800 dark:border-gray-600 dark:text-white" required>
                    </div>

                    <!-- Dropdown Kategori -->
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Kategori <span class="text-red-500">*</span></label>
                        <div class="space-y-2">
                            <select name="category_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-800 dark:border-gray-600 dark:text-white">
                                <option value="">-- Pilih Kategori --</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ (string) old('category_id', $product->category_id) === (string) $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            <input type="text" name="new_category" value="{{ old('new_category') }}" placeholder="+ Buat Kategori Baru (Opsional)..." class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-800 dark:border-gray-600 dark:text-white">
                        </div>
                    </div>

                    <!-- Dropdown Supplier -->
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Supplier <span class="text-red-500">*</span></label>
                        <div class="space-y-2">
                            <select name="supplier_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-800 dark:border-gray-600 dark:text-white">
                                <option value="">-- Pilih Supplier --</option>
                                @foreach($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}" {{ (string) old('supplier_id', $product->supplier_id) === (string) $supplier->id ? 'selected' : '' }}>
                                        {{ $supplier->name }}
                                    </option>
                                @endforeach
                            </select>
                            <input type="text" name="new_supplier" value="{{ old('new_supplier') }}" placeholder="+ Buat Supplier Baru (Opsional)..." class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-800 dark:border-gray-600 dark:text-white">
                        </div>
                    </div>

                    <!-- Harga Beli -->
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Harga Beli (Rp) <span class="text-red-500">*</span></label>
                        <input type="text" name="purchase_price" value="{{ old('purchase_price', number_format($product->purchase_price, 0, ',', '.')) }}" oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/\B(?=(\d{3})+(?!\d))/g, '.');" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-800 dark:border-gray-600 dark:text-white" required>
                    </div>

                    <!-- Harga Jual -->
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Harga Jual (Rp) <span class="text-red-500">*</span></label>
                        <input type="text" name="selling_price" value="{{ old('selling_price', number_format($product->selling_price, 0, ',', '.')) }}" oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/\B(?=(\d{3})+(?!\d))/g, '.');" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-800 dark:border-gray-600 dark:text-white" required>
                    </div>

                    <!-- Stok Minimum -->
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Stok Minimum <span class="text-red-500">*</span></label>
                        <input type="number" name="minimum_stock" value="{{ old('minimum_stock', $product->minimum_stock) }}" min="0" max="999999" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-800 dark:border-gray-600 dark:text-white" required>
                    </div>

                    <!-- Gambar Produk -->
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Gambar Produk (Opsional)</label>
                        <input type="file" name="image" accept="image/*" class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-800 dark:border-gray-600">
                        @if($product->image_url)
                            <div class="mt-2 flex items-center space-x-2">
                                <span class="text-xs text-gray-500 dark:text-gray-400">Gambar saat ini:</span>
                                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-10 h-10 object-cover rounded border">
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Deskripsi Produk -->
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Deskripsi Ringkas</label>
                    <textarea name="description" rows="3" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-800 dark:border-gray-600 dark:text-white">{{ old('description', $product->description) }}</textarea>
                </div>

                <!-- Tombol Aksi -->
                <div class="flex items-center space-x-3 pt-2">
                    <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Update Produk</button>
                    <a href="{{ route('products.index') }}" class="text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
