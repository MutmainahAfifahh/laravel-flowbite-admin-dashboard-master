@extends('layouts.dashboard')

@section('content')
<!-- Style khusus untuk format Cetak / PDF -->
<style>
    @media print {
        @page {
            size: A4 portrait;
            margin: 10mm;
        }

        html, body, div, main, section, article {
            height: auto !important;
            min-height: 0 !important;
            margin: 0 !important;
            padding: 0 !important;
            background: #ffffff !important;
            box-shadow: none !important;
            border: none !important;
            overflow: visible !important;
        }

        /* Sembunyikan elemen non-cetak termasuk kolom aksi & form */
        .no-print, nav, header, aside, sidebar, footer, .form-pencatatan-area, .col-aksi, dialog {
            display: none !important;
        }

        body * {
            visibility: hidden !important;
        }

        #printableTableArea, #printableTableArea * {
            visibility: visible !important;
        }

        #printableTableArea {
            position: absolute !important;
            left: 0 !important;
            top: 0 !important;
            width: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
        }

        #printableTableArea *,
        #printableTableArea table,
        #printableTableArea th,
        #printableTableArea td,
        #printableTableArea h2,
        #printableTableArea p,
        #printableTableArea span {
            color: #000000 !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        #printableTableArea span {
            border: 1px solid #000000 !important;
            background-color: transparent !important;
            padding: 2px 6px !important;
        }

        #printableTableArea table {
            border: 1.5px solid #000000 !important;
            border-collapse: collapse !important;
            width: 100% !important;
        }

        #printableTableArea th,
        #printableTableArea td {
            border: 1px solid #333333 !important;
            padding: 5px 8px !important;
            font-size: 10.5pt !important;
        }

        #printableTableArea thead th {
            background-color: #e5e7eb !important;
            font-weight: bold !important;
            border-bottom: 2px solid #000000 !important;
        }

        #printableTableArea tbody tr:nth-child(even) td {
            background-color: #f9fafb !important;
        }

        tr {
            page-break-inside: avoid !important;
        }
    }

    dialog::backdrop {
        background-color: rgba(0, 0, 0, 0.5);
    }
</style>

<div class="p-4 sm:p-6 bg-gray-50 dark:bg-gray-900 min-h-screen">
    <!-- Judul Halaman -->
    <div class="mb-6 no-print">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Riwayat Transaksi Stok</h1>
        <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 mt-0.5">Catat transaksi penerimaan/pengeluaran barang dan pantau laporan riwayat stok.</p>
    </div>

    <!-- Alert Sukses / Error -->
    @if(session('success'))
        <div class="p-4 mb-6 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400 border border-green-200 dark:border-green-800 no-print" role="alert">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="p-4 mb-6 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400 border border-red-200 dark:border-red-800 no-print" role="alert">
            {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="p-4 mb-6 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400 border border-red-200 dark:border-red-800 no-print">
            <ul class="list-disc pl-5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if(auth()->check() && auth()->user()->role === 'Manajer Gudang')
    <!-- 1. FORM CATAT TRANSAKSI STOK BARU -->
    <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm p-6 mb-8 no-print form-pencatatan-area">
        <div class="mb-4 pb-3 border-b border-gray-200 dark:border-gray-700">
            <h2 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                Catat Transaksi Stok Baru
            </h2>
            <p class="text-xs text-gray-500 dark:text-gray-400">Penerimaan barang dari supplier atau pengeluaran barang gudang</p>
        </div>

        <form action="{{ route('stock.store') }}" method="POST">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <!-- Tipe Transaksi -->
                <div>
                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-2">Tipe Transaksi *</label>
                    <select name="type" id="txTypeSelect" onchange="toggleSupplierField()" required class="w-full text-xs sm:text-sm p-2.5 rounded-lg border border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-blue-500 focus:border-blue-500">
                        <option value="Masuk">Masuk (Penerimaan Barang)</option>
                        <option value="Keluar">Keluar (Pengeluaran Barang)</option>
                    </select>
                </div>

                <!-- Pilih Produk -->
                <div>
                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-2">Pilih Produk *</label>
                    <select name="product_id" required class="w-full text-xs sm:text-sm p-2.5 rounded-lg border border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-blue-500 focus:border-blue-500">
                        <option value="">-- Pilih Produk --</option>
                        @foreach($products ?? [] as $product)
                            <option value="{{ $product->id }}">{{ $product->name }} (Stok Fisik: {{ $product->stock }})</option>
                        @endforeach
                    </select>
                </div>

                <!-- Pilih Supplier (Selalu Tampil) -->
                <div id="supplierWrapper">
                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-2">
                        Pilih Supplier / Pemasok <span id="supplierRequiredMark">*</span>
                    </label>
                    <select name="supplier_id" id="supplierSelect" required class="w-full text-xs sm:text-sm p-2.5 rounded-lg border border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-blue-500 focus:border-blue-500">
                        <option value="">-- Pilih Supplier --</option>
                        @foreach($suppliers ?? [] as $supplier)
                            <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Jumlah (Qty) -->
                <div>
                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-2">
                        Jumlah (Qty) * 
                        @if(isset($minimumStock) && $minimumStock > 0)
                            <span class="text-blue-600 dark:text-blue-400 font-normal">(Min. Admin: {{ $minimumStock }} pcs)</span>
                        @endif
                    </label>
                    <input type="number" 
                           min="{{ $minimumStock ?? 1 }}" 
                           name="quantity" 
                           required 
                           placeholder="Misal {{ $minimumStock ?? 60 }}" 
                           class="w-full text-xs sm:text-sm p-2.5 rounded-lg border border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-blue-500 focus:border-blue-500">
                </div>

                <!-- Tanggal Transaksi -->
                <div>
                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-2">Tanggal Transaksi *</label>
                    <input type="date" name="date" value="{{ date('Y-m-d') }}" max="{{ date('Y-m-d') }}" oninput="if(this.value && this.value > '{{ date('Y-m-d') }}') this.value = '{{ date('Y-m-d') }}';" required class="w-full text-xs sm:text-sm p-2.5 rounded-lg border border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-blue-500 focus:border-blue-500">
                </div>

                <!-- Catatan Opsional -->
                <div>
                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-2">Catatan / Keterangan</label>
                    <input type="text" name="notes" placeholder="Contoh: Penerimaan No. Faktur #1029" class="w-full text-xs sm:text-sm p-2.5 rounded-lg border border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>

            <!-- Tombol Submit Form -->
            <div class="mt-4 flex justify-end">
                <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 text-xs sm:text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded-lg shadow focus:ring-4 focus:ring-blue-300 transition duration-75">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Simpan Transaksi Stok
                </button>
            </div>
        </form>
    </div>
    @endif

    <!-- 2. TABEL RIWAYAT TRANSAKSI STOK -->
    <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm overflow-hidden p-6">
        
        <!-- Header Container & Filter Bar -->
        <div class="mb-6 no-print flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">

            <!-- Sub-Judul Kiri -->
            <div>
                <h2 class="text-lg font-bold text-gray-900 dark:text-white">Daftar Transaksi Stok</h2>
                <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 mt-0.5">Riwayat Keseluruhan Transaksi Masuk/Keluar Barang</p>
            </div>

            <!-- Filter & Tombol Cetak PDF -->
            <div class="flex flex-wrap items-center gap-3">
                <span class="text-xs font-semibold text-gray-700 dark:text-white">Filter berdasarkan:</span>

                <!-- Form Filter -->
                <form action="{{ route('transactions.history') }}" method="GET" id="filterForm">
                    <div class="relative min-w-[180px]">
                        <select name="type" onchange="document.getElementById('filterForm').submit()" class="bg-gray-50 border border-gray-300 text-gray-900 text-xs sm:text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            <option value="">Semua Tipe Transaksi</option>
                            <option value="Masuk" {{ request('type') == 'Masuk' ? 'selected' : '' }}>Masuk</option>
                            <option value="Keluar" {{ request('type') == 'Keluar' ? 'selected' : '' }}>Keluar</option>
                        </select>
                    </div>
                </form>

                <!-- Tombol Cetak PDF -->
                <button type="button" onclick="window.print()" class="inline-flex items-center gap-2 px-4 py-2.5 text-xs sm:text-sm font-medium text-white bg-blue-700 hover:bg-blue-800 rounded-lg focus:ring-4 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 transition duration-75">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                    </svg>
                    Cetak PDF
                </button>
            </div>
        </div>

        <!-- Area Yang Akan Tercetak Ke PDF (#printableTableArea) -->
        <div id="printableTableArea">
            <div class="hidden print:block mb-4 text-center">
                <h2 class="text-xl font-bold text-gray-900">Laporan Riwayat Transaksi Stok</h2>
                <p class="text-xs text-gray-600">Dicetak pada: {{ now()->translatedFormat('d F Y H:i') }}</p>
                <hr class="my-3 border-gray-300">
            </div>

            <!-- Tabel Data -->
            <div class="relative overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700">
                <table class="w-full text-sm text-left text-gray-800 dark:text-white">
                    <thead class="text-xs uppercase bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-white border-b border-gray-200 dark:border-gray-600 font-bold">
                        <tr>
                            <th scope="col" class="px-6 py-3.5 font-bold">NO.</th>
                            <th scope="col" class="px-6 py-3.5 font-bold">TANGGAL</th>
                            <th scope="col" class="px-6 py-3.5 font-bold">PRODUK</th>
                            <th scope="col" class="px-6 py-3.5 font-bold">SUPPLIER</th>
                            <th scope="col" class="px-6 py-3.5 font-bold">TIPE</th>
                            <th scope="col" class="px-6 py-3.5 font-bold">JUMLAH</th>
                            <th scope="col" class="px-6 py-3.5 font-bold">USER</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($transactions as $index => $tx)
                            <tr class="bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-75">
                                <td class="px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap">
                                    {{ $index + 1 }}
                                </td>
                                <td class="px-6 py-4 text-gray-900 dark:text-white whitespace-nowrap">
                                    {{ \Carbon\Carbon::parse($tx->date)->translatedFormat('d F Y') }}
                                </td>
                                <td class="px-6 py-4 text-gray-900 dark:text-white font-medium whitespace-nowrap">
                                    {{ $tx->product->name ?? '-' }}
                                </td>
                                <td class="px-6 py-4 text-gray-900 dark:text-white whitespace-nowrap font-medium">
                                    {{ $tx->supplier->name ?? $tx->product->supplier->name ?? '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-3 py-1.5 text-xs font-semibold rounded-md border {{ $tx->type == 'Masuk' ? 'bg-green-50 text-green-700 border-green-200 dark:bg-green-950/40 dark:text-green-400 dark:border-green-800' : 'bg-gray-100 text-gray-800 border-gray-300 dark:bg-gray-700 dark:text-white dark:border-gray-600' }}">
                                        {{ $tx->type }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 font-semibold text-gray-900 dark:text-white whitespace-nowrap">
                                    {{ $tx->quantity }}
                                </td>
                                <td class="px-6 py-4 text-gray-900 dark:text-white whitespace-nowrap">
                                    {{ $tx->user->name ?? 'Admin Stockify' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-8 text-center text-gray-500 dark:text-white">
                                    Belum ada data riwayat transaksi stok.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>



<script>
    function toggleSupplierField() {
        const txType = document.getElementById('txTypeSelect').value;
        const supplierSelect = document.getElementById('supplierSelect');

        if (txType === 'Masuk') {
            supplierSelect.required = true;
        } else {
            supplierSelect.required = false;
        }
    }


</script>
@endsection