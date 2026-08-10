@extends('layouts.dashboard')

@section('content')
<div class="p-4 bg-white block border-b border-gray-200 lg:mt-1.5 dark:bg-gray-800 dark:border-gray-700">
    <div class="w-full mb-4">
        <div class="flex items-center justify-between mb-4">
            <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl dark:text-white">Laporan Stok Barang</h1>
            <button onclick="window.print()" class="text-white bg-blue-700 hover:bg-blue-800 font-medium rounded-lg text-xs px-4 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700">
                🖨️ Cetak Laporan
            </button>
        </div>

        <!-- Filter -->
        <form action="{{ route('reports.stock') }}" method="GET" class="p-4 mb-6 bg-gray-50 rounded-lg dark:bg-gray-700 border border-gray-200 dark:border-gray-600 grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
            <div>
                <label class="block mb-2 text-xs font-medium text-gray-900 dark:text-white">Filter Kategori</label>
                <select name="category_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-xs rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-800 dark:border-gray-600 dark:text-white">
                    <option value="">-- Semua Kategori --</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="w-full text-white bg-blue-700 hover:bg-blue-800 font-medium rounded-lg text-xs px-4 py-2.5 dark:bg-blue-600">Filter</button>
                <a href="{{ route('reports.stock') }}" class="text-gray-900 bg-white border border-gray-300 font-medium rounded-lg text-xs px-3 py-2.5 dark:bg-gray-800 dark:text-white dark:border-gray-600">Reset</a>
            </div>
        </form>

        <!-- Tabel Laporan Stok -->
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-4 py-3 text-center">No</th>
                        <th scope="col" class="px-4 py-3">SKU</th>
                        <th scope="col" class="px-4 py-3">Nama Produk</th>
                        <th scope="col" class="px-4 py-3">Kategori</th>
                        <th scope="col" class="px-4 py-3">Supplier</th>
                        <th scope="col" class="px-4 py-3 text-center">Stok</th>
                        <th scope="col" class="px-4 py-3 text-right">Harga (Rp)</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                    @forelse($products as $index => $p)
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                            <td class="px-4 py-3 text-center">{{ $index + 1 }}</td>
                            <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">{{ $p->sku ?? '-' }}</td>
                            <td class="px-4 py-3 font-semibold text-gray-900 dark:text-white">{{ $p->name }}</td>
                            <td class="px-4 py-3">{{ $p->category->name ?? '-' }}</td>
                            <td class="px-4 py-3">{{ $p->supplier->name ?? '-' }}</td>
                            <td class="px-4 py-3 text-center font-bold">{{ $p->quantity ?? 0 }}</td>
                            <td class="px-4 py-3 text-right">Rp {{ number_format($p->price ?? 0, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">Tidak ada data stok produk.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
