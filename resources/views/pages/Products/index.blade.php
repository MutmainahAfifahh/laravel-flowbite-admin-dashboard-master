@extends('layouts.dashboard')

@section('content')
<div class="p-4 bg-white block sm:flex items-center justify-between border-b border-gray-200 lg:mt-1.5 dark:bg-gray-800 dark:border-gray-700">
    <div class="w-full mb-1">
        <div class="mb-4">
            <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl dark:text-white">Manajemen Produk</h1>
        </div>
        
        <!-- Pesan Error Validation -->
        @if($errors->any())
            <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400" role="alert">
                <ul class="list-disc pl-5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Pesan Sukses -->
        @if(session('success'))
            <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400" role="alert">
                {{ session('success') }}
            </div>
        @endif

        <!-- Form Tambah Produk Baru -->
        <div class="p-4 mb-6 bg-gray-50 rounded-lg dark:bg-gray-700 border border-gray-200 dark:border-gray-600">
            <h2 class="mb-4 text-lg font-bold text-gray-900 dark:text-white">Tambah Produk Baru</h2>
            
            <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @csrf
                
                <!-- 1. Dropdown Kategori / Input Kategori Baru -->
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Kategori <span class="text-red-500">*</span></label>
                    <div class="space-y-2">
                        <select name="category_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-800 dark:border-gray-600 dark:text-white">
                            <option value="">-- Pilih Kategori yang Ada --</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                        <div class="relative flex items-center">
                            <div class="flex-grow border-t border-gray-300 dark:border-gray-600"></div>
                            <span class="flex-shrink mx-2 text-xs text-gray-500 dark:text-gray-400 font-medium">atau buat baru</span>
                            <div class="flex-grow border-t border-gray-300 dark:border-gray-600"></div>
                        </div>
                        <input type="text" name="new_category" value="{{ old('new_category') }}" placeholder="+ Ketik Nama Kategori Baru..." class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-800 dark:border-gray-600 dark:text-white">
                    </div>
                </div>

                <!-- 2. Dropdown Supplier / Input Supplier Baru -->
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Supplier <span class="text-red-500">*</span></label>
                    <div class="space-y-2">
                        <select name="supplier_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-800 dark:border-gray-600 dark:text-white">
                            <option value="">-- Pilih Supplier yang Ada --</option>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>{{ $supplier->name }}</option>
                            @endforeach
                        </select>
                        <div class="relative flex items-center">
                            <div class="flex-grow border-t border-gray-300 dark:border-gray-600"></div>
                            <span class="flex-shrink mx-2 text-xs text-gray-500 dark:text-gray-400 font-medium">atau buat baru</span>
                            <div class="flex-grow border-t border-gray-300 dark:border-gray-600"></div>
                        </div>
                        <input type="text" name="new_supplier" value="{{ old('new_supplier') }}" placeholder="+ Ketik Nama Supplier Baru..." class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-800 dark:border-gray-600 dark:text-white">
                    </div>
                </div>

                <!-- 3. Nama Produk -->
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama Produk <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-800 dark:border-gray-600 dark:text-white" placeholder="Contoh: Laptop Asus Vivobook" required>
                </div>

                <!-- 4. SKU / Kode Barang -->
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">SKU / Kode Barang</label>
                    <input type="text" name="sku" value="{{ old('sku') }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-800 dark:border-gray-600 dark:text-white" placeholder="Biarkan kosong untuk otomatis (PRD-XXXXXX)">
                </div>

                <!-- 5. Stok Minimum -->
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Stok Minimum (Alert) <span class="text-red-500">*</span></label>
                    <input type="number" name="minimum_stock" min="0" value="{{ old('minimum_stock', 5) }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-800 dark:border-gray-600 dark:text-white" required>
                </div>

                <!-- 6. Gambar Produk -->
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Gambar Produk</label>
                    <input type="file" name="image" accept="image/*" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-800 dark:border-gray-600 dark:text-white">
                </div>

                <!-- 7. Harga Beli -->
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Harga Beli (Rp) <span class="text-red-500">*</span></label>
                    <input type="number" name="purchase_price" min="0" max="999999999999" oninput="if (this.value.length > 12) this.value = this.value.slice(0, 12);" value="{{ old('purchase_price') }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-800 dark:border-gray-600 dark:text-white" placeholder="0" required>
                </div>

                <!-- 8. Harga Jual -->
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Harga Jual (Rp) <span class="text-red-500">*</span></label>
                    <input type="number" name="selling_price" min="0" max="999999999999" oninput="if (this.value.length > 12) this.value = this.value.slice(0, 12);" value="{{ old('selling_price') }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-800 dark:border-gray-600 dark:text-white" placeholder="0" required>
                </div>

                <!-- 9. Atribut Awal (Opsional) -->
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Atribut Awal: Nama <span class="text-xs text-gray-400 font-normal">(Opsional)</span></label>
                    <input type="text" name="attribute_name" value="{{ old('attribute_name') }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-800 dark:border-gray-600 dark:text-white" placeholder="Contoh: Warna, Ukuran, Garansi">
                </div>

                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Atribut Awal: Nilai <span class="text-xs text-gray-400 font-normal">(Opsional)</span></label>
                    <input type="text" name="attribute_value" value="{{ old('attribute_value') }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-800 dark:border-gray-600 dark:text-white" placeholder="Contoh: Hitam, XL, 1 Tahun">
                </div>

                <!-- 9. Deskripsi Produk (Full Width / 2 Kolom) -->
                <div class="md:col-span-2">
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Deskripsi Ringkas</label>
                    <textarea name="description" rows="2" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-800 dark:border-gray-600 dark:text-white" placeholder="Catatan atau rincian spesifikasi barang...">{{ old('description') }}</textarea>
                </div>

                <!-- Tombol Simpan -->
                <div class="md:col-span-2 text-right">
                    <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-6 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                        Simpan Produk
                    </button>
                </div>
            </form>
        </div>

        <!-- Tabel Daftar Produk -->
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th class="px-4 py-3">#</th>
                        <th class="px-4 py-3">SKU</th>
                        <th class="px-4 py-3">Nama Produk</th>
                        <th class="px-4 py-3">Kategori</th>
                        <th class="px-4 py-3">Supplier</th>
                        <th class="px-4 py-3">Harga Beli</th>
                        <th class="px-4 py-3">Harga Jual</th>
                        <th class="px-4 py-3 text-center">Stok Min</th>
                        <th class="px-4 py-3">Deskripsi</th>
                        <th class="px-4 py-3 text-center">Gambar</th>
                        <th class="px-4 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $index => $product)
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 cursor-pointer" onclick="openDetailModal({{ json_encode($product) }})" title="Klik untuk lihat detail">
                            <td class="px-4 py-4">{{ $index + 1 }}</td>
                            <td class="px-4 py-4 font-mono text-xs text-gray-600 dark:text-gray-300">{{ $product->sku }}</td>
                            <td class="px-4 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ $product->name }}
                            </td>
                            <td class="px-4 py-4">{{ $product->category->name ?? '-' }}</td>
                            <td class="px-4 py-4">{{ $product->supplier->name ?? '-' }}</td>
                            <td class="px-4 py-4">Rp {{ number_format($product->purchase_price, 0, ',', '.') }}</td>
                            <td class="px-4 py-4 font-semibold text-gray-900 dark:text-white">Rp {{ number_format($product->selling_price, 0, ',', '.') }}</td>
                            <td class="px-4 py-4 text-center">
                                <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-blue-900 dark:text-blue-300">
                                    {{ $product->minimum_stock }}
                                </span>
                            </td>
                            <td class="px-4 py-4 max-w-xs truncate" title="{{ $product->description }}">
                                {{ Str::limit($product->description, 35) ?? '-' }}
                            </td>
                            <td class="px-4 py-4 text-center">
                                @if($product->image)
                                    <img src="{{ Str::startsWith($product->image, 'http') ? $product->image : asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-10 h-10 object-cover rounded mx-auto hover:scale-110 transition-transform">
                                @else
                                    <span class="text-gray-400 text-xs">-</span>
                                @endif
                            </td>
                            <td class="px-4 py-4 text-center" onclick="event.stopPropagation()">
                                <div class="flex items-center justify-center space-x-2">
                                    <button type="button" onclick="openDetailModal({{ json_encode($product) }})" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Detail</button>
                                    <span class="text-gray-300 dark:text-gray-600">|</span>
                                    <button data-modal-target="modal-attribute-{{ $product->id }}" data-modal-toggle="modal-attribute-{{ $product->id }}" class="px-2 py-0.5 text-xs font-medium text-purple-700 bg-purple-100 rounded hover:bg-purple-200 dark:bg-purple-900/40 dark:text-purple-300 dark:hover:bg-purple-900/60">
                                        Atribut ({{ $product->attributes->count() }})
                                    </button>
                                    <span class="text-gray-300 dark:text-gray-600">|</span>
                                    <form action="{{ route('products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus produk ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="font-medium text-red-600 dark:text-red-500 hover:underline">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>

                        <!-- Modal Flowbite untuk Kelola Atribut -->
                        <div id="modal-attribute-{{ $product->id }}" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full" onclick="event.stopPropagation()">
                            <div class="relative p-4 w-full max-w-md max-h-full">
                                <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                                    
                                    <!-- Header Modal -->
                                    <div class="flex items-center justify-between p-4 border-b rounded-t dark:border-gray-600">
                                        <h3 class="text-sm font-bold text-gray-900 dark:text-white">
                                            Atribut: {{ $product->name }}
                                        </h3>
                                        <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="modal-attribute-{{ $product->id }}">
                                            ✕
                                        </button>
                                    </div>

                                    <!-- Body Modal -->
                                    <div class="p-4 space-y-4">
                                        <!-- Form Tambah Atribut Baru -->
                                        <form action="{{ route('product-attributes.store') }}" method="POST" class="flex gap-2">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                                            <input type="text" name="name" placeholder="Nama (cth: Warna)" class="w-1/2 p-2 text-xs bg-gray-50 border border-gray-300 text-gray-900 rounded-lg dark:bg-gray-800 dark:border-gray-600 dark:text-white" required>
                                            <input type="text" name="value" placeholder="Nilai (cth: Hitam)" class="w-1/2 p-2 text-xs bg-gray-50 border border-gray-300 text-gray-900 rounded-lg dark:bg-gray-800 dark:border-gray-600 dark:text-white" required>
                                            <button type="submit" class="px-3 py-2 text-xs font-medium text-white bg-blue-700 rounded-lg hover:bg-blue-800 dark:bg-blue-600 dark:hover:bg-blue-700">
                                                +Tambah
                                            </button>
                                        </form>

                                        <!-- Daftar Atribut Produk Saat Ini -->
                                        <div class="space-y-2">
                                            <p class="text-xs font-semibold text-gray-500 dark:text-gray-400">Daftar Atribut:</p>
                                            @forelse($product->attributes as $attr)
                                                <div class="flex items-center justify-between p-2 bg-gray-100 rounded-lg dark:bg-gray-600">
                                                    <span class="text-xs text-gray-900 dark:text-white">
                                                        <strong>{{ $attr->name }}:</strong> {{ $attr->value }}
                                                    </span>
                                                    <form action="{{ route('product-attributes.destroy', $attr->id) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-xs text-red-600 dark:text-red-400 hover:underline">Hapus</button>
                                                    </form>
                                                </div>
                                            @empty
                                                <p class="text-xs text-gray-400 text-center py-2">Belum ada atribut.</p>
                                            @endforelse
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    @empty
                        <tr>
                            <td colspan="11" class="px-6 py-4 text-center">Belum ada data produk. Silakan tambah produk baru di atas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</div>

<!-- Modal Detail Produk Popup -->
<div id="productDetailModal" class="fixed inset-0 z-50 hidden bg-gray-900/60 dark:bg-gray-900/80 backdrop-blur-sm overflow-y-auto p-3 sm:p-4 flex justify-center items-start sm:items-center" onclick="closeDetailModal()">
    <div class="relative w-full max-w-md bg-white rounded-lg shadow-xl dark:bg-gray-800 p-4 border border-gray-200 dark:border-gray-700 my-auto" onclick="event.stopPropagation()">
        
        <!-- Header Modal -->
        <div class="flex items-center justify-between pb-2 mb-2.5 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-sm font-bold text-gray-900 dark:text-white flex items-center gap-2">
                <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
                Detail Produk
            </h3>
            <button type="button" onclick="closeDetailModal()" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-7 h-7 inline-flex justify-center items-center dark:hover:bg-gray-700 dark:hover:text-white">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <!-- Body Modal -->
        <div class="grid grid-cols-1 sm:grid-cols-12 gap-3">
            <!-- Kolom Kiri: Gambar & Atribut -->
            <div class="sm:col-span-4 space-y-2">
                <div class="flex flex-col items-center justify-center bg-gray-50 dark:bg-gray-700 p-2 rounded-lg border border-gray-200 dark:border-gray-600 min-h-[120px]">
                    <img id="modal_image" src="" alt="Gambar Produk" class="max-h-32 w-auto object-contain rounded shadow-sm">
                    <span id="modal_no_image" class="hidden text-gray-400 text-xs py-4">Tidak ada gambar</span>
                </div>

                <div class="bg-gray-50 dark:bg-gray-700 p-2 rounded-lg border border-gray-200 dark:border-gray-600 text-xs">
                    <span class="text-[10px] text-gray-500 dark:text-gray-400 block font-semibold mb-1">Atribut Produk</span>
                    <div id="modal_attributes_list" class="flex flex-wrap gap-1"></div>
                </div>
            </div>

            <!-- Kolom Kanan: Detail & Deskripsi -->
            <div class="sm:col-span-8 space-y-2">
                <div>
                    <h4 id="modal_name" class="text-sm font-bold text-gray-900 dark:text-white leading-tight"></h4>
                    <span id="modal_sku" class="inline-block mt-0.5 font-mono text-[11px] font-semibold px-2 py-0.5 rounded bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300"></span>
                </div>

                <div class="grid grid-cols-2 gap-1.5 text-xs">
                    <div class="bg-gray-50 dark:bg-gray-700 p-1.5 rounded border border-gray-200 dark:border-gray-600">
                        <span class="text-[10px] text-gray-500 dark:text-gray-400 block">Kategori</span>
                        <strong id="modal_category" class="text-gray-900 dark:text-white text-xs truncate block"></strong>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700 p-1.5 rounded border border-gray-200 dark:border-gray-600">
                        <span class="text-[10px] text-gray-500 dark:text-gray-400 block">Supplier</span>
                        <strong id="modal_supplier" class="text-gray-900 dark:text-white text-xs truncate block"></strong>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700 p-1.5 rounded border border-gray-200 dark:border-gray-600">
                        <span class="text-[10px] text-gray-500 dark:text-gray-400 block">Harga Beli</span>
                        <strong id="modal_purchase_price" class="text-gray-900 dark:text-white text-xs block"></strong>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700 p-1.5 rounded border border-gray-200 dark:border-gray-600">
                        <span class="text-[10px] text-gray-500 dark:text-gray-400 block">Harga Jual</span>
                        <strong id="modal_selling_price" class="text-gray-900 dark:text-white font-bold text-xs block"></strong>
                    </div>
                </div>

                <div class="bg-gray-50 dark:bg-gray-700 p-1.5 rounded border border-gray-200 dark:border-gray-600 text-xs">
                    <span class="text-[10px] text-gray-500 dark:text-gray-400 block">Stok Minimum Alert</span>
                    <span id="modal_minimum_stock" class="font-bold text-gray-900 dark:text-white text-xs"></span>
                </div>

                <div class="bg-gray-50 dark:bg-gray-700 p-2.5 rounded border border-gray-200 dark:border-gray-600 text-xs">
                    <span class="text-[10px] text-gray-500 dark:text-gray-400 block font-semibold mb-0.5">Deskripsi Lengkap</span>
                    <p id="modal_description" class="text-gray-700 dark:text-gray-300 whitespace-pre-line leading-relaxed text-xs"></p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function openDetailModal(product) {
        document.getElementById('modal_name').textContent = product.name || '-';
        document.getElementById('modal_sku').textContent = product.sku ? 'SKU: ' + product.sku : 'SKU: -';
        document.getElementById('modal_category').textContent = product.category ? product.category.name : '-';
        document.getElementById('modal_supplier').textContent = product.supplier ? product.supplier.name : '-';
        
        document.getElementById('modal_purchase_price').textContent = 'Rp ' + Number(product.purchase_price || 0).toLocaleString('id-ID');
        document.getElementById('modal_selling_price').textContent = 'Rp ' + Number(product.selling_price || 0).toLocaleString('id-ID');
        document.getElementById('modal_minimum_stock').textContent = product.minimum_stock + ' Unit';
        document.getElementById('modal_description').textContent = product.description || 'Tidak ada deskripsi.';
        
        let attrContainer = document.getElementById('modal_attributes_list');
        attrContainer.innerHTML = '';
        if (product.attributes && product.attributes.length > 0) {
            product.attributes.forEach(function(attr) {
                let badge = document.createElement('span');
                badge.className = 'px-2 py-0.5 text-[11px] font-medium bg-purple-100 text-purple-800 dark:bg-purple-900/40 dark:text-purple-300 rounded';
                badge.textContent = attr.name + ': ' + attr.value;
                attrContainer.appendChild(badge);
            });
        } else {
            attrContainer.innerHTML = '<span class="text-gray-400 text-xs">Belum ada atribut.</span>';
        }
        
        let imgSrc = product.image ? (product.image.startsWith('http') ? product.image : '/storage/' + product.image) : '';
        let imgElement = document.getElementById('modal_image');
        let noImgElement = document.getElementById('modal_no_image');
        
        if (imgSrc) {
            imgElement.src = imgSrc;
            imgElement.classList.remove('hidden');
            noImgElement.classList.add('hidden');
        } else {
            imgElement.classList.add('hidden');
            noImgElement.classList.remove('hidden');
        }
        
        document.getElementById('productDetailModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeDetailModal() {
        document.getElementById('productDetailModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeDetailModal();
        }
    });
</script>
@endsection