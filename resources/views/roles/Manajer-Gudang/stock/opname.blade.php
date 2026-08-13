@extends('layouts.dashboard')

@section('content')
<div class="p-4 bg-white block border-b border-gray-200 lg:mt-1.5 dark:bg-gray-800 dark:border-gray-700">
    <div class="w-full mb-4">
        <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl dark:text-white mb-4">Stock Inventory Audit (Stock Opname)</h1>

        {{-- Alert Sukses --}}
        @if(session('success'))
            <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400" role="alert">
                {{ session('success') }}
            </div>
        @endif

        {{-- Alert Error --}}
        @if($errors->any())
            <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400" role="alert">
                <ul class="list-disc pl-5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Tabel Bulk Audit Transaksi Stok -->
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <form action="{{ route('stock.update') }}" method="POST">
                @csrf
                <div class="p-4 flex justify-end bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600 rounded-t-lg">
                    <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700">
                        Simpan Perubahan Opname
                    </button>
                </div>
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-4 py-3">No</th>
                            <th scope="col" class="px-4 py-3">Tanggal</th>
                            <th scope="col" class="px-4 py-3">Produk</th>
                            <th scope="col" class="px-4 py-3">Jenis Transaksi</th>
                            <th scope="col" class="px-4 py-3">Jumlah (Qty)</th>
                            <th scope="col" class="px-4 py-3">Status</th>
                            <th scope="col" class="px-4 py-3">Catatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($transaction as $index => $item)
                            @php
                                $statusOptions = ['Pending', 'Diterima', 'Ditolak', 'Dikeluarkan'];
                            @endphp
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">
                                    <input type="hidden" name="stock_id[{{ $item->id }}]" value="{{ $item->id }}">
                                    {{ $index + 1 }}
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-gray-900 dark:text-white">
                                    {{ \Carbon\Carbon::parse($item->date)->translatedFormat('d F Y') }}
                                </td>
                                <td class="px-4 py-3 font-semibold text-gray-900 dark:text-white">
                                    {{ $item->product->name ?? '-' }}
                                </td>
                                <td class="px-4 py-3">
                                    <select name="type[{{ $item->id }}]" class="bg-gray-50 border border-gray-300 text-gray-900 text-xs rounded-lg p-2 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                        <option value="Masuk" {{ $item->type == 'Masuk' ? 'selected' : '' }}>Masuk</option>
                                        <option value="Keluar" {{ $item->type == 'Keluar' ? 'selected' : '' }}>Keluar</option>
                                    </select>
                                </td>
                                <td class="px-4 py-3">
                                    <input type="number" name="quantity[{{ $item->id }}]" value="{{ $item->quantity }}" min="0" class="w-24 bg-gray-50 border border-gray-300 text-gray-900 text-xs rounded-lg p-2 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                </td>
                                <td class="px-4 py-3">
                                    <select name="status[{{ $item->id }}]" class="bg-gray-50 border border-gray-300 text-gray-900 text-xs rounded-lg p-2 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                        @foreach ($statusOptions as $opt)
                                            <option value="{{ $opt }}" {{ $item->status == $opt ? 'selected' : '' }}>{{ $opt }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td class="px-4 py-3">
                                    <input type="text" name="notes[{{ $item->id }}]" value="{{ $item->notes }}" placeholder="Catatan opsional..." class="bg-gray-50 border border-gray-300 text-gray-900 text-xs rounded-lg p-2 w-full dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">Belum ada data transaksi untuk diaudit.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

            </form>
        </div>

        @if(method_exists($transaction, 'hasPages') && $transaction->hasPages())
            <div class="mt-4">
                {{ $transaction->links() }}
            </div>
        @endif
    </div>
</div>
@endsection