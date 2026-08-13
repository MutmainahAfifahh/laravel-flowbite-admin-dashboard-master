@extends('layouts.dashboard')
@section('content')
    <div class="px-4 pt-6">
        <x-notify::notify />
        <h1 class="text-2xl font-medium text-slate-800 dark:text-slate-100">{{ $title }}</h1>

        <!-- ALERT SUCCESS -->
        @if(session('success'))
            <div class="p-4 my-4 text-sm text-emerald-900 bg-emerald-100 rounded-lg dark:bg-emerald-950/80 dark:text-white dark:border dark:border-emerald-700 shadow-sm" role="alert">
                <span class="font-bold">Berhasil!</span> {{ session('success') }}
            </div>
        @endif

        <!-- ALERT ERROR -->
        @if(session('error'))
            <div class="p-4 my-4 text-sm text-rose-900 bg-rose-100 rounded-lg dark:bg-rose-950/80 dark:text-white dark:border dark:border-rose-700 shadow-sm" role="alert">
                <span class="font-bold">Gagal!</span> {{ session('error') }}
            </div>
        @endif

        <!-- BANNER INFO: Batas Minimum Qty dari Admin (Real-time Sync) -->
        <div id="min_stock_banner" class="p-4 my-4 text-sm rounded-lg border shadow-sm bg-blue-50 text-blue-800 border-blue-200 dark:bg-blue-950/60 dark:text-blue-200 dark:border-blue-700">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a.75.75 0 000 1.5h.253a.25.25 0 01.244.304l-.459 2.066A1.75 1.75 0 0010.747 15H11a.75.75 0 000-1.5h-.253a.25.25 0 01-.244-.304l.459-2.066A1.75 1.75 0 009.253 9H9z" clip-rule="evenodd" />
                </svg>
                <div>
                    <span class="font-bold">Batas Minimum Qty dari Admin:</span>
                    <span id="min_stock_display" class="inline-flex items-center px-2.5 py-0.5 ml-1 text-sm font-extrabold rounded-full bg-blue-200 text-blue-900 dark:bg-blue-800 dark:text-blue-100">
                        {{ $minimumStock ?? 5 }} pcs
                    </span>
                    <span class="ml-1 text-xs opacity-75">— Qty Masuk tidak boleh kurang dari nilai ini, dan sisa stok Keluar tidak boleh di bawah nilai ini.</span>
                </div>
            </div>
            <div id="min_stock_updated_notice" class="hidden mt-2 text-xs font-bold text-amber-700 dark:text-amber-300 animate-pulse">
                ⚡ Admin baru saja mengubah batas minimum! Nilai telah diperbarui otomatis.
            </div>
        </div>

        <!-- Form Konfirmasi Stok Baru -->
        <section class="my-5">
            <div class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm dark:border-gray-700 sm:p-6 dark:bg-gray-800">
                <h3 class="mb-4 text-lg font-bold text-gray-900 dark:text-white">Form Konfirmasi Transaksi Stok</h3>
                <form action="{{ route('stock-transactions.store') }}" method="POST" id="stock_form">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                        <!-- Pilihan Produk -->
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Pilih Produk <span class="text-red-500">*</span></label>
                            <select name="product_id" id="product_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white" required>
                                <option value="">-- Pilih Produk --</option>
                                @foreach($products as $product)
                                    @php
                                        // Mengambil batas minimum dari Admin ($minimumStock)
                                        $currentMin = $minimumStock ?? $product->min_stock ?? 5;
                                    @endphp
                                    <option value="{{ $product->id }}" 
                                            data-stock="{{ $product->stock ?? 0 }}" 
                                            data-min="{{ $currentMin }}">
                                        {{ $product->name }} (SKU: {{ $product->sku }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Supplier -->
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Supplier <span class="text-red-500">*</span></label>
                            <select name="supplier_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white" required>
                                <option value="">-- Pilih Supplier --</option>
                                @foreach($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Jenis Transaksi -->
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Jenis Transaksi <span class="text-red-500">*</span></label>
                            <select name="type" id="transaction_type" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white" required>
                                <option value="Masuk">Barang Masuk (+)</option>
                                <option value="Keluar">Barang Keluar (-)</option>
                            </select>
                        </div>

                        <!-- Jumlah (Quantity) & Pesan Peringatan -->
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Jumlah (Qty) <span class="text-red-500">*</span></label>
                            <input type="number" name="quantity" id="quantity_input" min="1" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white" required>
                            <p id="qty_warning" class="mt-1 text-xs font-bold text-rose-600 dark:text-rose-400 hidden"></p>
                        </div>

                        <!-- Tanggal Transaksi -->
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tanggal <span class="text-red-500">*</span></label>
                            <input type="date" name="date" value="{{ date('Y-m-d') }}" max="{{ date('Y-m-d') }}" oninput="if(this.value && this.value > '{{ date('Y-m-d') }}') this.value = '{{ date('Y-m-d') }}';" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white" required>
                        </div>

                        <!-- Catatan -->
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Catatan</label>
                            <input type="text" name="notes" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white" placeholder="Keterangan konfirmasi stok...">
                        </div>
                    </div>

                    <div class="text-right">
                        <button type="submit" id="submit_btn" class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 transition-colors">
                            Simpan Konfirmasi Stok
                        </button>
                    </div>
                </form>
            </div>
        </section>

        <!-- Tabel Daftar Konfirmasi Stok -->
        <section>
            <x-table.table-layout :title="'Item Check Confirmation'" :description="'Pending/Struggle data information'">
                @slot('additional')
                @endslot
                
                @slot('header')
                    <th class="px-3 py-3 text-xs font-bold tracking-wider text-center text-gray-600 uppercase !bg-transparent dark:text-gray-300 border-b border-gray-200 dark:border-gray-700">NO.</th>
                    <th class="px-3 py-3 text-xs font-bold tracking-wider text-center text-gray-600 uppercase !bg-transparent dark:text-gray-300 border-b border-gray-200 dark:border-gray-700">PRODUCT</th>
                    <th class="px-3 py-3 text-xs font-bold tracking-wider text-center text-gray-600 uppercase !bg-transparent dark:text-gray-300 border-b border-gray-200 dark:border-gray-700">CATEGORY</th>
                    <th class="px-3 py-3 text-xs font-bold tracking-wider text-center text-gray-600 uppercase !bg-transparent dark:text-gray-300 border-b border-gray-200 dark:border-gray-700">SUPPLIER</th>
                    <th class="px-3 py-3 text-xs font-bold tracking-wider text-center text-gray-600 uppercase !bg-transparent dark:text-gray-300 border-b border-gray-200 dark:border-gray-700">TYPE</th>
                    <th class="px-3 py-3 text-xs font-bold tracking-wider text-center text-gray-600 uppercase !bg-transparent dark:text-gray-300 border-b border-gray-200 dark:border-gray-700">QTY</th>
                    <th class="px-3 py-3 text-xs font-bold tracking-wider text-center text-gray-600 uppercase !bg-transparent dark:text-gray-300 border-b border-gray-200 dark:border-gray-700">DATE</th>
                    <th class="px-3 py-3 text-xs font-bold tracking-wider text-center text-gray-600 uppercase !bg-transparent dark:text-gray-300 border-b border-gray-200 dark:border-gray-700">STATUS</th>
                    <th class="px-3 py-3 text-xs font-bold tracking-wider text-center text-gray-600 uppercase !bg-transparent dark:text-gray-300 border-b border-gray-200 dark:border-gray-700">AKSI</th>
                @endslot

                @forelse ($transactions as $index => $stock)
                    @php
                        $statusClasses = [
                            'Pending'     => 'bg-amber-100 text-amber-800 border-amber-300 dark:bg-amber-900/80 dark:text-amber-200 dark:border-amber-700',
                            'Completed'   => 'bg-emerald-100 text-emerald-800 border-emerald-300 dark:bg-emerald-900/80 dark:text-emerald-200 dark:border-emerald-700',
                            'Diterima'    => 'bg-emerald-100 text-emerald-800 border-emerald-300 dark:bg-emerald-900/80 dark:text-emerald-200 dark:border-emerald-700',
                            'Ditolak'     => 'bg-rose-500 text-black border-rose-600 dark:bg-rose-700 dark:text-white dark:border-rose-600',
                            'Dikeluarkan' => 'bg-amber-100 text-amber-900 border-amber-300 dark:bg-amber-950 dark:text-amber-300 dark:border-amber-800',
                        ];

                        $statusClass = $statusClasses[$stock->status] ?? 'bg-gray-100 text-gray-800 border-gray-300 dark:bg-gray-800 dark:text-gray-200 dark:border-gray-700';
                    @endphp

                    <tr class="!bg-transparent dark:!bg-transparent border-b border-gray-200/60 dark:border-gray-700/60 hover:bg-gray-500/10 transition-colors">
                        <td class="px-3 py-2.5 text-xs text-center font-semibold !bg-transparent text-gray-800 dark:text-gray-200">
                            {{ $loop->iteration }}
                        </td>
                        
                        <td class="px-3 py-2.5 text-xs text-center font-bold !bg-transparent text-gray-900 dark:text-white">
                            {{ $stock->product->name ?? '-' }}
                        </td>
                        
                        <td class="px-3 py-2.5 text-xs text-center !bg-transparent text-gray-700 dark:text-gray-300">
                            {{ $stock->product->category->name ?? '-' }}
                        </td>
                        
                        <td class="px-3 py-2.5 text-xs text-center !bg-transparent">
                            <span class="bg-blue-100 text-blue-900 border border-blue-200 dark:bg-blue-200 dark:text-black dark:border-blue-300 text-xs font-bold px-2.5 py-1 rounded-md inline-block max-w-[160px] truncate shadow-sm">
                                {{ $stock->product->supplier->name ?? 'Tanpa Supplier' }}
                            </span>
                        </td>
                        
                        <td class="px-3 py-2.5 text-xs text-center !bg-transparent">
                            @if(strtolower($stock->type) === 'masuk')
                                <span class="bg-teal-100 text-teal-800 border border-teal-200 dark:bg-teal-900/80 dark:text-teal-200 dark:border-teal-700 text-xs font-bold px-2.5 py-1 rounded-md shadow-sm">
                                    Masuk
                                </span>
                            @else
                                <span class="bg-orange-100 text-orange-800 border border-orange-200 dark:bg-orange-900/80 dark:text-orange-200 dark:border-orange-700 text-xs font-bold px-2.5 py-1 rounded-md shadow-sm">
                                    Keluar
                                </span>
                            @endif
                        </td>
                        
                        <td class="px-3 py-2.5 text-xs text-center !bg-transparent">
                            <span class="bg-purple-100 text-purple-800 border border-purple-200 dark:bg-purple-900/80 dark:text-purple-200 dark:border-purple-700 font-extrabold px-2.5 py-0.5 rounded-md text-xs shadow-sm">
                                {{ $stock->quantity }}
                            </span>
                        </td>
                        
                        <td class="px-3 py-2.5 text-xs text-center !bg-transparent text-gray-700 dark:text-gray-300 whitespace-nowrap font-medium">
                            {{ \Carbon\Carbon::parse($stock->date)->translatedFormat('d M Y') }}
                        </td>
                        
                        <td class="px-3 py-2.5 text-xs text-center !bg-transparent whitespace-nowrap">
                            <span class="px-3 py-1 rounded-full text-xs font-bold border shadow-sm {{ $statusClass }}">
                                {{ $stock->status }}
                            </span>
                        </td>
                        
                        <td class="px-3 py-2.5 text-xs text-center !bg-transparent whitespace-nowrap">
                            <div class="inline-flex items-center justify-center gap-1.5">
                                <form action="{{ route('stock.confirm-status', $stock->id) }}" method="POST" class="inline">
                                    @csrf
                                    <input type="hidden" name="status" value="Diterima" />
                                    <button type="submit" class="inline-flex items-center px-2.5 py-1 text-xs font-bold rounded shadow-sm transition-all text-black dark:text-white bg-emerald-500 hover:bg-emerald-600 dark:bg-emerald-600 dark:hover:bg-emerald-700" title="Terima Barang">
                                        <x-heroicon-o-check-circle class="w-4 h-4 mr-1 text-black dark:text-white" />
                                        Terima
                                    </button>
                                </form>

                                <form action="{{ route('stock.confirm-status', $stock->id) }}" method="POST" class="inline">
                                    @csrf
                                    <input type="hidden" name="status" value="Ditolak" />
                                    <button type="submit" class="inline-flex items-center px-2.5 py-1 text-xs font-bold rounded shadow-sm transition-all text-black dark:text-white bg-rose-500 hover:bg-rose-600 dark:bg-rose-600 dark:hover:bg-rose-700" title="Tolak Barang">
                                        <x-heroicon-o-x-circle class="w-4 h-4 mr-1 text-black dark:text-white" />
                                        Tolak
                                    </button>
                                </form>

                                <form action="{{ route('stock.confirm-status', $stock->id) }}" method="POST" class="inline">
                                    @csrf
                                    <input type="hidden" name="status" value="Dikeluarkan" />
                                    <button type="submit" class="inline-flex items-center px-2.5 py-1 text-xs font-bold rounded shadow-sm transition-all text-black dark:text-white bg-amber-500 hover:bg-amber-600 dark:bg-amber-600 dark:hover:bg-amber-700" title="Proses Dikeluarkan">
                                        <x-heroicon-o-arrow-up-tray class="w-4 h-4 mr-1 text-black dark:text-white" />
                                        Keluarkan
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="px-3 py-4 text-xs text-center font-medium italic text-gray-500 dark:text-gray-400">
                            Pending Stock Item is Empty!
                        </td>
                    </tr>
                @endforelse
            </x-table.table-layout>
        </section>
    </div>

    <!-- SCRIPT VALIDASI REALTIME + POLLING SYNC ADMIN -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const productSelect = document.getElementById('product_id');
            const typeSelect = document.getElementById('transaction_type');
            const qtyInput = document.getElementById('quantity_input');
            const warningMsg = document.getElementById('qty_warning');
            const submitBtn = document.getElementById('submit_btn');
            const minStockDisplay = document.getElementById('min_stock_display');
            const minStockNotice = document.getElementById('min_stock_updated_notice');

            // Nilai minimum stok saat ini (dari server saat page load)
            let currentMinStock = {{ $minimumStock ?? 5 }};

            function checkStockLimit() {
                if (!productSelect.value) {
                    warningMsg.classList.add('hidden');
                    submitBtn.disabled = false;
                    submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                    return;
                }

                const selectedOption = productSelect.options[productSelect.selectedIndex];
                const currentStock = parseInt(selectedOption.getAttribute('data-stock')) || 0;
                const minStockAdmin = parseInt(selectedOption.getAttribute('data-min')) || 0;
                const type = typeSelect.value;
                const enteredQty = parseInt(qtyInput.value) || 0;

                warningMsg.classList.add('hidden');
                submitBtn.disabled = false;
                submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');

                // VALIDASI 1: QTY YANG DIINPUT KURANG DARI MINIMUM STOK ADMIN (KHUSUS BARANG MASUK)
                if (type === 'Masuk' && enteredQty < minStockAdmin) {
                    warningMsg.innerText = `⛔ TIDAK BISA DISIMPAN: Qty barang masuk yang diinput (${enteredQty} pcs) kurang dari batas minimum Admin (${minStockAdmin} pcs)!`;
                    warningMsg.classList.remove('hidden');
                    submitBtn.disabled = true;
                    submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
                    return;
                }

                // VALIDASI 2: PENGECEKAN STOK SAAT INI (KHUSUS BARANG KELUAR)
                // Fitur ini dinonaktifkan sesuai permintaan agar form tetap bisa disimpan
                /*
                if (type === 'Keluar') {
                    const remainingStock = currentStock - enteredQty;
                    if (remainingStock < minStockAdmin) {
                        const maxAllowed = Math.max(0, currentStock - minStockAdmin);
                        warningMsg.innerText = `⛔ TIDAK BISA DISIMPAN: Sisa stok tidak boleh di bawah batas minimum Admin (${minStockAdmin} pcs). Maksimal barang keluar: ${maxAllowed} pcs.`;
                        warningMsg.classList.remove('hidden');
                        submitBtn.disabled = true;
                        submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
                    }
                }
                */
            }

            // ============================================================
            // REAL-TIME SYNC: Polling API setiap 30 detik
            // Mengambil nilai minimum stok terbaru dari Admin
            // ============================================================
            function pollMinimumStock() {
                fetch('{{ route("api.stock.minimum") }}', {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    credentials: 'same-origin',
                })
                .then(response => response.json())
                .then(data => {
                    const newMin = parseInt(data.minimum_stock) || 0;

                    // Jika minimum berubah dari Admin
                    if (newMin !== currentMinStock) {
                        currentMinStock = newMin;

                        // 1. Update banner display
                        minStockDisplay.textContent = newMin + ' pcs';

                        // 2. Update data-min di semua option produk
                        const options = productSelect.querySelectorAll('option[data-min]');
                        options.forEach(opt => {
                            opt.setAttribute('data-min', newMin);
                        });

                        // 3. Tampilkan notifikasi perubahan
                        minStockNotice.classList.remove('hidden');
                        setTimeout(() => {
                            minStockNotice.classList.add('hidden');
                        }, 8000);

                        // 4. Re-validasi input qty yang sedang aktif
                        checkStockLimit();
                    }
                })
                .catch(err => {
                    // Silent fail — jangan ganggu user
                    console.warn('Gagal polling minimum stok:', err);
                });
            }

            // Polling setiap 30 detik
            setInterval(pollMinimumStock, 30000);

            // Event listeners untuk validasi realtime
            productSelect.addEventListener('change', checkStockLimit);
            typeSelect.addEventListener('change', checkStockLimit);
            qtyInput.addEventListener('input', checkStockLimit);
        });
    </script>
@endsection