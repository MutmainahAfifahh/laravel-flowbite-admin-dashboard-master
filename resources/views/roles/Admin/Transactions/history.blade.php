@extends('layouts.dashboard')

@section('content')
<!-- Style khusus untuk format Cetak / PDF -->
<style>
    @media print {
        /* 1. Sembunyikan semua elemen bawaan layout */
        body * {
            visibility: hidden;
            background: #ffffff !important;
        }

        /* 2. Tampilkan HANYA area tabel cetak */
        #printableTableArea, #printableTableArea * {
            visibility: visible;
        }
        #printableTableArea {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
        }

        /* 3. Paksa SEMUA TEKS berwarna HITAM PEKAT */
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

        /* Badge Masuk/Keluar — border hitam saat print */
        #printableTableArea span {
            border: 1.5px solid #000000 !important;
            background-color: transparent !important;
        }

        /* 4. Buat tabel memiliki border hitam yang terlihat jelas */
        #printableTableArea table {
            border: 1.5px solid #000000 !important;
            border-collapse: collapse !important;
            width: 100% !important;
        }
        #printableTableArea th,
        #printableTableArea td {
            border: 1px solid #333333 !important;
            padding: 8px 12px !important;
            background-color: transparent !important;
        }
        #printableTableArea thead th {
            background-color: #e5e7eb !important;
            font-weight: bold !important;
            border-bottom: 2px solid #000000 !important;
        }
        #printableTableArea tbody tr:nth-child(even) td {
            background-color: #f9fafb !important;
        }

        /* 5. Sembunyikan elemen non-cetak */
        .no-print {
            display: none !important;
        }
    }
</style>

<div class="p-4 sm:p-6 bg-gray-50 dark:bg-gray-900 min-h-screen">
    <!-- Judul Halaman -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Riwayat Transaksi Stok</h1>
    </div>

    @if(session('success'))
        <div class="p-4 mb-6 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400 border border-green-200 dark:border-green-800" role="alert">
            {{ session('success') }}
        </div>
    @endif

    <!-- Container Utama Card -->
    <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm overflow-hidden p-6">
        
        <!-- Header Container & Filter Bar (Disembunyikan saat print) -->
        <div class="mb-6 no-print flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">

            <!-- Sub-Judul Kiri -->
            <div>
                <h2 class="text-lg font-bold text-gray-900 dark:text-white">Daftar Transaksi Stok</h2>
                <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 mt-0.5">Riwayat Transaksi Masuk/Keluar Barang Produk</p>
            </div>

            <!-- Kontrol Filter & Tombol Kanan -->
            <div class="flex flex-wrap items-center gap-3">
                <span class="text-xs font-semibold text-gray-700 dark:text-white">Filter berdasarkan:</span>

                <!-- Form Filter (standalone, tidak berisi tombol cetak) -->
                <form action="{{ route('transactions.history') }}" method="GET" id="filterForm">
                    <div class="relative min-w-[180px]">
                        <select name="type" onchange="document.getElementById('filterForm').submit()" class="bg-gray-50 border border-gray-300 text-gray-900 text-xs sm:text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            <option value="">Semua Tipe Transaksi</option>
                            <option value="Masuk" {{ request('type') == 'Masuk' ? 'selected' : '' }}>Masuk</option>
                            <option value="Keluar" {{ request('type') == 'Keluar' ? 'selected' : '' }}>Keluar</option>
                        </select>
                    </div>
                </form>

                <!-- Tombol Cetak TERPISAH dari form agar tidak memicu submit -->
                <button type="button" id="btnCetak" class="inline-flex items-center gap-2 px-4 py-2.5 text-xs sm:text-sm font-medium text-white bg-blue-700 hover:bg-blue-800 rounded-lg focus:ring-4 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 transition duration-75">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                    </svg>
                    Cetak PDF
                </button>
            </div>
        </div>

        <script>
            document.getElementById('btnCetak').addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();

                var content = document.getElementById('printableTableArea').innerHTML;
                var printWin = window.open('', '_blank', 'width=950,height=700,scrollbars=yes');

                printWin.document.write(`<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Riwayat Transaksi Stok</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 13px; color: #000; background: #fff; margin: 24px; }
        h2 { text-align: center; font-size: 16px; font-weight: bold; margin-bottom: 4px; }
        p  { text-align: center; font-size: 11px; color: #444; margin-bottom: 10px; }
        hr { border: 1px solid #ccc; margin-bottom: 16px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #333; padding: 8px 12px; text-align: left; color: #000; }
        thead th { background-color: #e5e7eb; font-weight: bold; border-bottom: 2px solid #000; }
        tbody tr:nth-child(even) td { background-color: #f9fafb; }
        span { border: 1.5px solid #000 !important; padding: 2px 8px; border-radius: 4px; font-size: 11px; }
        /* Tampilkan div judul yang tersembunyi di halaman asli */
        .hidden { display: block !important; }
        /* Sembunyikan border dari div container tabel */
        .relative { border: none !important; }
    </style>
</head>
<body>
    ${content}
</body>
</html>`);

                printWin.document.close();
                printWin.focus();
                setTimeout(function() {
                    printWin.print();
                    printWin.close();
                }, 400);
            });
        </script>

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
                            <th scope="col" class="px-6 py-3.5 font-bold">TIPE</th>
                            <th scope="col" class="px-6 py-3.5 font-bold">JUMLAH</th>
                            <th scope="col" class="px-6 py-3.5 font-bold">USER</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($transactions as $index => $tx)
                            <tr class="bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-75">
                                <td class="px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap">
                                    {{ ($transactions->currentPage() - 1) * $transactions->perPage() + $index + 1 }}
                                </td>
                                <td class="px-6 py-4 text-gray-900 dark:text-white whitespace-nowrap">
                                    {{ \Carbon\Carbon::parse($tx->date)->translatedFormat('d F Y') }}
                                </td>
                                <td class="px-6 py-4 text-gray-900 dark:text-white font-medium whitespace-nowrap">
                                    {{ $tx->product->name ?? '-' }}
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
                                <td colspan="6" class="px-6 py-8 text-center text-gray-500 dark:text-white">
                                    Belum ada data riwayat transaksi stok.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Tombol Navigasi Pagination (Disembunyikan saat print) -->
        <div class="flex items-center justify-between mt-6 no-print">
            @if ($transactions->onFirstPage())
                <span class="inline-flex items-center px-4 py-2 text-xs sm:text-sm font-medium text-gray-400 bg-gray-100 dark:bg-gray-800 dark:text-gray-500 border border-gray-300 dark:border-gray-700 rounded-lg cursor-not-allowed">
                    &laquo; Sebelumnya
                </span>
            @else
                <a href="{{ $transactions->previousPageUrl() }}" class="inline-flex items-center px-4 py-2 text-xs sm:text-sm font-medium text-gray-900 dark:text-white bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 transition">
                    &laquo; Sebelumnya
                </a>
            @endif

            @if ($transactions->hasMorePages())
                <a href="{{ $transactions->nextPageUrl() }}" class="inline-flex items-center px-4 py-2 text-xs sm:text-sm font-medium text-gray-900 dark:text-white bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 transition">
                    Selanjutnya &raquo;
                </a>
            @else
                <span class="inline-flex items-center px-4 py-2 text-xs sm:text-sm font-medium text-gray-400 bg-gray-100 dark:bg-gray-800 dark:text-gray-500 border border-gray-300 dark:border-gray-700 rounded-lg cursor-not-allowed">
                    Selanjutnya &raquo;
                </span>
            @endif
        </div>

    </div>
</div>
@endsection