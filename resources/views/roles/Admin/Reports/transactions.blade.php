@extends('layouts.dashboard')

@section('content')
<div class="p-4 bg-white block border-b border-gray-200 lg:mt-1.5 dark:bg-gray-800 dark:border-gray-700">
    <div class="w-full mb-4">
        <div class="flex items-center justify-between mb-4">
            <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl dark:text-white">Laporan Transaksi Barang Masuk dan Keluar</h1>
            <button onclick="window.print()" class="text-white bg-blue-700 hover:bg-blue-800 font-medium rounded-lg text-xs px-4 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700">
                🖨️ Cetak Laporan
            </button>
        </div>

        <!-- Filter -->
        <form action="{{ route('reports.transactions') }}" method="GET" class="p-4 mb-6 bg-gray-50 rounded-lg dark:bg-gray-700 border border-gray-200 dark:border-gray-600 grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
            <div>
                <label class="block mb-2 text-xs font-medium text-gray-900 dark:text-white">Jenis Transaksi</label>
                <select name="type" class="bg-gray-50 border border-gray-300 text-gray-900 text-xs rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-800 dark:border-gray-600 dark:text-white">
                    <option value="">-- Semua Jenis --</option>
                    <option value="Masuk" {{ request('type') == 'Masuk' ? 'selected' : '' }}>Barang Masuk</option>
                    <option value="Keluar" {{ request('type') == 'Keluar' ? 'selected' : '' }}>Barang Keluar</option>
                </select>
            </div>
            <div>
                <label class="block mb-2 text-xs font-medium text-gray-900 dark:text-white">Dari Tanggal</label>
                <input type="date" name="start_date" value="{{ request('start_date') }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-xs rounded-lg focus:ring-blue-500 block w-full p-2.5 dark:bg-gray-800 dark:border-gray-600 dark:text-white">
            </div>
            <div>
                <label class="block mb-2 text-xs font-medium text-gray-900 dark:text-white">Sampai Tanggal</label>
                <input type="date" name="end_date" value="{{ request('end_date') }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-xs rounded-lg focus:ring-blue-500 block w-full p-2.5 dark:bg-gray-800 dark:border-gray-600 dark:text-white">
            </div>
            <div class="flex gap-2">
                <button type="submit" class="w-full text-white bg-blue-700 hover:bg-blue-800 font-medium rounded-lg text-xs px-4 py-2.5 dark:bg-blue-600">Filter</button>
                <a href="{{ route('reports.transactions') }}" class="text-gray-900 bg-white border border-gray-300 font-medium rounded-lg text-xs px-3 py-2.5 dark:bg-gray-800 dark:text-white dark:border-gray-600">Reset</a>
            </div>
        </form>

        <!-- Tabel Laporan Transaksi -->
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-4 py-3 text-center">No</th>
                        <th scope="col" class="px-4 py-3">Tanggal</th>
                        <th scope="col" class="px-4 py-3">Produk</th>
                        <th scope="col" class="px-4 py-3 text-center">Jenis</th>
                        <th scope="col" class="px-4 py-3 text-center">Jumlah Qty</th>
                        <th scope="col" class="px-4 py-3 text-center">Status</th>
                        <th scope="col" class="px-4 py-3">Catatan</th>
                        <th scope="col" class="px-4 py-3">Pencatat</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                    @forelse($transactions as $index => $tx)
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                            <td class="px-4 py-3 text-center">{{ $index + 1 }}</td>
                            <td class="px-4 py-3 whitespace-nowrap">{{ \Carbon\Carbon::parse($tx->date)->translatedFormat('d F Y') }}</td>
                            <td class="px-4 py-3 font-semibold text-gray-900 dark:text-white">{{ $tx->product->name ?? '-' }}</td>
                            <td class="px-4 py-3 text-center">
                                @if($tx->type == 'Masuk')
                                    <span class="bg-green-100 text-green-800 text-xs font-semibold px-2 py-0.5 rounded dark:bg-green-900 dark:text-green-300">Masuk</span>
                                @else
                                    <span class="bg-red-100 text-red-800 text-xs font-semibold px-2 py-0.5 rounded dark:bg-red-900 dark:text-red-300">Keluar</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-center font-bold">{{ $tx->quantity }}</td>
                            <td class="px-4 py-3 text-center">{{ $tx->status }}</td>
                            <td class="px-4 py-3 text-xs">{{ $tx->notes ?? '-' }}</td>
                            <td class="px-4 py-3 text-xs">{{ $tx->user->name ?? 'Admin' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">Belum ada riwayat laporan transaksi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
