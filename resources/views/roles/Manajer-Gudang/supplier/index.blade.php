@extends('layouts.dashboard')

@section('content')
<div class="px-4 pt-6">
    <div class="p-4 bg-white block sm:flex items-center justify-between border-b border-gray-200 lg:mt-1.5 dark:bg-gray-800 dark:border-gray-700 rounded-lg shadow-sm">
        <div class="w-full mb-1">
            <div class="mb-4">
                <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl dark:text-white">Daftar Supplier (Manajer)</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400">Informasi data supplier dan pamasok barang gudang.</p>
            </div>
            
            <div class="relative overflow-x-auto shadow-sm sm:rounded-lg border border-gray-200 dark:border-gray-700">
                <table class="w-full text-xs text-left text-gray-800 dark:text-gray-200">
                    <thead class="text-[11px] text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-300 font-bold border-b dark:border-gray-600">
                        <tr>
                            <th class="px-4 py-3 text-center w-12">#</th>
                            <th class="px-4 py-3">Nama Supplier</th>
                            <th class="px-4 py-3">No. Telepon / Kontak</th>
                            <th class="px-4 py-3">Alamat</th>
                            <th class="px-4 py-3 text-center">Jumlah Produk</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($suppliers as $index => $supplier)
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="px-4 py-3 text-center font-semibold">{{ $index + 1 }}</td>
                                <td class="px-4 py-3 font-bold text-gray-900 dark:text-white">{{ $supplier->name }}</td>
                                <td class="px-4 py-3 text-gray-600 dark:text-gray-300">{{ $supplier->phone ?? '-' }}</td>
                                <td class="px-4 py-3 text-gray-600 dark:text-gray-300">{{ $supplier->address ?? '-' }}</td>
                                <td class="px-4 py-3 text-center">
                                    <span class="bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 text-[11px] font-bold px-2.5 py-0.5 rounded">
                                        {{ $supplier->products_count ?? $supplier->products->count() ?? 0 }} Produk
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-6 text-center text-gray-500 dark:text-gray-400">Belum ada data supplier.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
