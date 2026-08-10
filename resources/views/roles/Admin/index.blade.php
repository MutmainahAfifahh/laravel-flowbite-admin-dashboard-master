@extends('layouts.dashboard')

@section('content')
<div class="px-4 pt-6">
    <div class="mb-4">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Dashboard Admin</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400">Ringkasan informasi produk, statistik transaksi, dan aktivitas sistem terbaru.</p>
    </div>

    <!-- Ringkasan Statistik Cards -->
    <div class="grid w-full grid-cols-1 gap-4 mt-4 sm:grid-cols-2 lg:grid-cols-4">
        <!-- 1. Total Produk -->
        <div class="items-center justify-between p-4 bg-white border border-gray-200 rounded-lg shadow-sm sm:flex dark:border-gray-700 dark:bg-gray-800">
            <div>
                <span class="text-xs font-semibold text-gray-500 uppercase dark:text-gray-400">Total Produk</span>
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $totalProducts ?? 0 }}</h3>
                <span class="text-xs text-green-500">Terdaftar di sistem</span>
            </div>
            <div class="w-12 h-12 flex items-center justify-center rounded-full bg-blue-100 text-blue-600 dark:bg-blue-900 dark:text-blue-300 text-xl">
                📦
            </div>
        </div>

        <!-- 2. Transaksi Masuk (30 Hari) -->
        <div class="items-center justify-between p-4 bg-white border border-gray-200 rounded-lg shadow-sm sm:flex dark:border-gray-700 dark:bg-gray-800">
            <div>
                <span class="text-xs font-semibold text-gray-500 uppercase dark:text-gray-400">Barang Masuk (30 Hari)</span>
                <h3 class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $incomingTransaction ?? 0 }}</h3>
                <span class="text-xs text-gray-500">Penerimaan stok</span>
            </div>
            <div class="w-12 h-12 flex items-center justify-center rounded-full bg-green-100 text-green-600 dark:bg-green-900 dark:text-green-300 text-xl">
                📥
            </div>
        </div>

        <!-- 3. Transaksi Keluar (30 Hari) -->
        <div class="items-center justify-between p-4 bg-white border border-gray-200 rounded-lg shadow-sm sm:flex dark:border-gray-700 dark:bg-gray-800">
            <div>
                <span class="text-xs font-semibold text-gray-500 uppercase dark:text-gray-400">Barang Keluar (30 Hari)</span>
                <h3 class="text-2xl font-bold text-red-600 dark:text-red-400">{{ $outgoingTransaction ?? 0 }}</h3>
                <span class="text-xs text-gray-500">Pengeluaran stok</span>
            </div>
            <div class="w-12 h-12 flex items-center justify-center rounded-full bg-red-100 text-red-600 dark:bg-red-900 dark:text-red-300 text-xl">
                📤
            </div>
        </div>

        <!-- 4. Stok Menipis -->
        <div class="items-center justify-between p-4 bg-white border border-gray-200 rounded-lg shadow-sm sm:flex dark:border-gray-700 dark:bg-gray-800">
            <div>
                <span class="text-xs font-semibold text-gray-500 uppercase dark:text-gray-400">Stok Menipis</span>
                <h3 class="text-2xl font-bold text-yellow-600 dark:text-yellow-400">{{ $totalLowStock ?? 0 }}</h3>
                <span class="text-xs text-yellow-500">Perlu re-stock</span>
            </div>
            <div class="w-12 h-12 flex items-center justify-center rounded-full bg-yellow-100 text-yellow-600 dark:bg-yellow-900 dark:text-yellow-300 text-xl">
                ⚠️
            </div>
        </div>
    </div>

    <!-- Section Grafik & Visualisasi -->
    <div class="grid grid-cols-1 gap-4 mt-6 lg:grid-cols-3">
        <!-- Grafik Transaksi (Visual Chart Canvas) -->
        <div class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm lg:col-span-2 dark:border-gray-700 dark:bg-gray-800">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Grafik Transaksi Stok</h3>
                <span class="text-xs text-gray-500 dark:text-gray-400">Perbandingan per bulan</span>
            </div>
            <div class="relative h-64 flex items-end justify-around border-b border-gray-200 dark:border-gray-700 pb-2 pt-6">
                <!-- Bar chart visualizer -->
                @forelse($transactionData ?? [] as $data)
                    <div class="flex flex-col items-center gap-1 group">
                        <div class="text-xs font-semibold text-blue-600 dark:text-blue-400 mb-1 opacity-0 group-hover:opacity-100 transition">{{ $data->total_quantity }}</div>
                        <div class="w-12 bg-blue-500 rounded-t hover:bg-blue-600 transition" style="height: {{ min(200, max(20, $data->total_quantity * 4)) }}px;"></div>
                        <span class="text-xs text-gray-500 dark:text-gray-400">Bln {{ $data->month }}/{{ $data->year }}</span>
                    </div>
                @empty
                    <div class="flex items-center justify-center w-full h-full text-gray-400 text-sm">
                        Belum ada data grafik transaksi.
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Info Cepat & Akses Fitur Admin -->
        <div class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm dark:border-gray-700 dark:bg-gray-800">
            <h3 class="mb-4 text-lg font-bold text-gray-900 dark:text-white">Akses Cepat Admin</h3>
            <div class="space-y-3">
                <a href="{{ route('products.index') }}" class="flex items-center p-3 text-sm font-semibold text-gray-900 rounded-lg bg-gray-50 dark:bg-gray-700 dark:text-white hover:bg-blue-50 dark:hover:bg-gray-600">
                    <span class="mr-3">📦</span> Kelola Data Produk
                </a>
                <a href="{{ route('categories.index') }}" class="flex items-center p-3 text-sm font-semibold text-gray-900 rounded-lg bg-gray-50 dark:bg-gray-700 dark:text-white hover:bg-blue-50 dark:hover:bg-gray-600">
                    <span class="mr-3">🏷️</span> Kelola Kategori Produk
                </a>
                <a href="{{ route('suppliers.index') }}" class="flex items-center p-3 text-sm font-semibold text-gray-900 rounded-lg bg-gray-50 dark:bg-gray-700 dark:text-white hover:bg-blue-50 dark:hover:bg-gray-600">
                    <span class="mr-3">🏢</span> Kelola Supplier
                </a>
                <a href="{{ route('users.index') }}" class="flex items-center p-3 text-sm font-semibold text-gray-900 rounded-lg bg-gray-50 dark:bg-gray-700 dark:text-white hover:bg-blue-50 dark:hover:bg-gray-600">
                    <span class="mr-3">👥</span> Manajemen Hak Akses Pengguna
                </a>
                <a href="{{ route('transactions.history') }}" class="flex items-center p-3 text-sm font-semibold text-gray-900 rounded-lg bg-gray-50 dark:bg-gray-700 dark:text-white hover:bg-blue-50 dark:hover:bg-gray-600">
                    <span class="mr-3">📜</span> Riwayat Transaksi Barang
                </a>
            </div>
        </div>
    </div>

    <!-- Aktivitas Pengguna Terbaru -->
    <div class="mt-6 mb-8 p-4 bg-white border border-gray-200 rounded-lg shadow-sm dark:border-gray-700 dark:bg-gray-800">
        <h3 class="mb-4 text-lg font-bold text-gray-900 dark:text-white">Aktivitas Pengguna Terbaru</h3>
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-4 py-3 text-center">No</th>
                        <th scope="col" class="px-4 py-3">Pengguna</th>
                        <th scope="col" class="px-4 py-3">Aksi</th>
                        <th scope="col" class="px-4 py-3">Modul</th>
                        <th scope="col" class="px-4 py-3">Deskripsi</th>
                        <th scope="col" class="px-4 py-3">Waktu</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                    @forelse($activities ?? [] as $index => $act)
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                            <td class="px-4 py-3 text-center font-medium text-gray-900 dark:text-white">{{ $index + 1 }}</td>
                            <td class="px-4 py-3 font-semibold text-gray-900 dark:text-white">{{ $act->user->name ?? 'Sistem' }}</td>
                            <td class="px-4 py-3">
                                <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2 py-0.5 rounded dark:bg-blue-900 dark:text-blue-300">
                                    {{ $act->action ?? 'LOG' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-xs">{{ $act->model_type ?? '-' }}</td>
                            <td class="px-4 py-3 text-xs text-gray-700 dark:text-gray-300">{{ $act->description ?? '-' }}</td>
                            <td class="px-4 py-3 text-xs whitespace-nowrap">{{ \Carbon\Carbon::parse($act->created_at ?? now())->diffForHumans() }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">Belum ada catatan aktivitas pengguna.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
