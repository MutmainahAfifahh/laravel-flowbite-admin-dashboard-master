@extends('layouts.dashboard')

@section('content')
<div class="p-4 bg-white block sm:flex items-center justify-between border-b border-gray-200 lg:mt-1.5 dark:bg-gray-800 dark:border-gray-700">
    <div class="w-full mb-1">
        <div class="flex items-center justify-between mb-4">
            <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl dark:text-white">Manajemen Produk</h1>
            <div class="flex gap-2">
                <a href="{{ route('products.export') }}" class="inline-flex items-center justify-center text-white bg-blue-700 hover:bg-emerald-700 focus:ring-4 focus:ring-emerald-300 font-medium rounded-lg text-xs px-4 py-2.5 dark:bg-emerald-600 dark:hover:bg-emerald-700 dark:focus:ring-emerald-800 shadow-sm transition-colors">
                    <span class="mr-1.5">📥</span> Export CSV Produk
                </a>
                <button type="button" onclick="document.getElementById('importModal').classList.toggle('hidden')" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-xs px-4 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700">
                    📤 Import CSV Produk
                </button>
            </div>
        </div>

        <!-- Modal Import CSV Produk -->
        <div id="importModal" class="hidden p-4 mb-4 bg-blue-50 rounded-lg border border-blue-200 dark:bg-gray-800 dark:border-gray-700">
            <h3 class="mb-2 text-sm font-bold text-gray-900 dark:text-white">Upload File CSV Data Produk</h3>
            <form action="{{ route('products.import') }}" method="POST" enctype="multipart/form-data" class="flex items-center gap-4">
                @csrf
                <input type="file" name="file" accept=".csv,.txt" class="bg-white border border-gray-300 text-gray-900 text-xs rounded-lg block p-2 dark:bg-gray-700 dark:border-gray-600 dark:text-white" required>
                <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 font-medium rounded-lg text-xs px-4 py-2 dark:bg-blue-600">
                    Upload & Import
                </button>
                <button type="button" onclick="document.getElementById('importModal').classList.add('hidden')" class="text-gray-500 hover:text-gray-700 text-xs">
                    Batal
                </button>
            </form>
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
                
                <!-- 1. Dropdown Kategori -->
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Kategori <span class="text-red-500">*</span></label>
                    <select name="category_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-800 dark:border-gray-600 dark:text-white">
                        <option value="">-- Pilih Kategori yang Ada --</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- 2. Dropdown Supplier -->
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Supplier <span class="text-red-500">*</span></label>
                    <select name="supplier_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-800 dark:border-gray-600 dark:text-white">
                        <option value="">-- Pilih Supplier yang Ada --</option>
                        @foreach($suppliers as $supplier)
                            <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>{{ $supplier->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- 3. Nama Produk -->
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama Produk <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-800 dark:border-gray-600 dark:text-white" placeholder="Contoh: Laptop Asus Vivobook" required>
                </div>

                <!-- 4. SKU / Kode Barang -->
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">SKU / Kode Barang <span class="text-red-500">*</span></label>
                    <input type="text" name="sku" value="{{ old('sku') }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-800 dark:border-gray-600 dark:text-white" placeholder="Contoh: PRD-123456" required>
                </div>

                <!-- 5. Stok Minimum -->
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Stok Minimum (Alert) <span class="text-red-500">*</span></label>
                    <input type="number" name="minimum_stock" min="0" value="{{ old('minimum_stock') }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-800 dark:border-gray-600 dark:text-white" required>
                </div>

                <!-- 6. Gambar Produk -->
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Gambar Produk</label>
                    <input type="file" name="image" accept="image/*" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-800 dark:border-gray-600 dark:text-white">
                </div>

                <!-- 7. Harga Beli -->
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Harga Beli (Rp) <span class="text-red-500">*</span></label>
                    <input type="text" name="purchase_price" oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/\B(?=(\d{3})+(?!\d))/g, '.');" value="{{ old('purchase_price') }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-800 dark:border-gray-600 dark:text-white" placeholder="0" required>
                </div>

                <!-- 8. Harga Jual -->
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Harga Jual (Rp) <span class="text-red-500">*</span></label>
                    <input type="text" name="selling_price" oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/\B(?=(\d{3})+(?!\d))/g, '.');" value="{{ old('selling_price') }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-800 dark:border-gray-600 dark:text-white" placeholder="0" required>
                </div>

                <!-- 9. Deskripsi Produk -->
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
        <div class="relative overflow-x-auto shadow-sm sm:rounded-lg border border-gray-200 dark:border-gray-700">
            <table class="w-full text-xs text-left text-gray-800 dark:text-gray-200">
                <thead class="text-[11px] text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-300 font-bold border-b dark:border-gray-600">
                    <tr>
                        <th class="px-2.5 py-2 text-center w-8">#</th>
                        <th class="px-2.5 py-2 w-24">SKU</th>
                        <th class="px-2.5 py-2">Nama Produk</th>
                        <th class="px-2.5 py-2">Kategori</th>
                        <th class="px-2.5 py-2">Supplier</th>
                        <th class="px-2.5 py-2">H. Beli</th>
                        <th class="px-2.5 py-2">H. Jual</th>
                        <th class="px-2.5 py-2 text-center">Stok Min</th>
                        <th class="px-2.5 py-2">Deskripsi</th>
                        <th class="px-2.5 py-2 text-center">Gambar</th>
                        <th class="px-2.5 py-2 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $index => $product)
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer" onclick="openDetailModal({{ json_encode($product) }})" title="Klik untuk lihat detail">
                            <td class="px-2.5 py-2 text-center font-semibold text-gray-900 dark:text-gray-100">{{ $index + 1 }}</td>
                            <td class="px-2.5 py-2 font-mono text-[11px] text-gray-700 dark:text-gray-300">{{ $product->sku }}</td>
                            <td class="px-2.5 py-2 font-bold text-gray-900 whitespace-nowrap dark:text-white">
                                {{ $product->name }}
                            </td>
                            <td class="px-2.5 py-2 text-gray-700 dark:text-gray-300 whitespace-nowrap">{{ $product->category->name ?? '-' }}</td>
                            <td class="px-2.5 py-2 text-gray-700 dark:text-gray-300 whitespace-nowrap">{{ $product->supplier->name ?? '-' }}</td>
                            <td class="px-2.5 py-2 whitespace-nowrap">Rp {{ number_format($product->purchase_price, 0, ',', '.') }}</td>
                            <td class="px-2.5 py-2 font-semibold text-gray-900 dark:text-white whitespace-nowrap">Rp {{ number_format($product->selling_price, 0, ',', '.') }}</td>
                            <td class="px-2.5 py-2 text-center">
                                <span class="bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 text-[10px] font-bold px-2 py-0.5 rounded">
                                    {{ $product->minimum_stock }}
                                </span>
                            </td>
                            <td class="px-2.5 py-2 max-w-[150px] truncate text-gray-600 dark:text-gray-400" title="{{ $product->description }}">
                                {{ Str::limit($product->description, 25) ?? '-' }}
                            </td>
                            <td class="px-2.5 py-2 text-center">
                                @if($product->image_url)
                                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-7 h-7 object-cover rounded mx-auto hover:scale-110 transition-transform">
                                @else
                                    <span class="text-gray-400 text-[10px]">-</span>
                                @endif
                            </td>
                            <td class="px-2.5 py-2 text-center" onclick="event.stopPropagation()">
                                <div class="flex items-center justify-center space-x-1.5">
                                    <button type="button" onclick="openDetailModal({{ json_encode($product) }})" class="text-[11px] font-medium text-blue-600 dark:text-blue-400 hover:underline">Detail</button>
                                    <span class="text-gray-300 dark:text-gray-600">|</span>
                                    <a href="{{ route('products.edit', $product->id) }}" class="text-[11px] font-medium text-yellow-600 dark:text-white hover:underline">
                                        Edit
                                    </a>
                                    <span class="text-gray-300 dark:text-gray-600">|</span>
                                    <form action="{{ route('products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus produk ini?')" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-[11px] font-medium text-red-600 dark:text-red-400 hover:underline">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" class="px-4 py-3 text-center text-gray-500 dark:text-gray-400">Belum ada data produk. Silakan tambah produk baru di atas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
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
                    <div class="sm:col-span-4 space-y-2">
                        <div class="flex flex-col items-center justify-center bg-gray-50 dark:bg-gray-700 p-2 rounded-lg border border-gray-200 dark:border-gray-600 min-h-[120px]">
                            <img id="modal_image" src="" alt="Gambar Produk" class="max-h-32 w-auto object-contain rounded shadow-sm">
                            <span id="modal_no_image" class="hidden text-gray-400 text-xs py-4">Tidak ada gambar</span>
                        </div>
                    </div>

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
        document.getElementById('modal_minimum_stock').textContent = (product.minimum_stock ?? 0) + ' Unit';
        document.getElementById('modal_description').textContent = product.description || 'Tidak ada deskripsi.';
        
        let imgElement = document.getElementById('modal_image');
        let noImgElement = document.getElementById('modal_no_image');
        
        // Membaca property `image_url` dari Model Accessor
        if (product.image_url) {
            imgElement.src = product.image_url;
            imgElement.classList.remove('hidden');
            noImgElement.classList.add('hidden');
        } else {
            imgElement.src = '';
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