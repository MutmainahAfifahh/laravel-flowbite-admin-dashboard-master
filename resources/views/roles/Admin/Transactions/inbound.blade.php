@extends('layouts.dashboard')

@section('content')
<div class="p-4 bg-white block border-b border-gray-200 lg:mt-1.5 dark:bg-gray-800 dark:border-gray-700">
    <div class="w-full mb-4">
        <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl dark:text-white mb-4">Transaksi Barang Masuk (Penerimaan Barang)</h1>

        @if(session('success'))
            <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400" role="alert">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400" role="alert">
                <ul class="list-disc pl-5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Form Catat Barang Masuk -->
        <div class="p-4 mb-6 bg-gray-50 rounded-lg dark:bg-gray-700 border border-gray-200 dark:border-gray-600">
            <h2 class="mb-4 text-lg font-bold text-gray-900 dark:text-white">Catat Penerimaan Barang Masuk</h2>
            
            <form action="{{ route('stock-transactions.store') }}" method="POST">
                @csrf
                <input type="hidden" name="type" value="Masuk">

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                    <!-- 1. Pilih Produk -->
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Pilih Produk <span class="text-red-500">*</span></label>
                        <select name="product_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-800 dark:border-gray-600 dark:text-white" required>
                            <option value="">-- Pilih Produk --</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}" {{ old('product_id') == $product->id ? 'selected' : '' }}>
                                    {{ $product->name }} (SKU: {{ $product->sku }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- 2. Jumlah Qty -->
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Jumlah (Qty Masuk) <span class="text-red-500">*</span></label>
                        <input type="number" name="quantity" min="1" value="{{ old('quantity', 1) }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-800 dark:border-gray-600 dark:text-white" placeholder="Jumlah barang" required>
                    </div>

                    <!-- 3. Tanggal Masuk -->
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tanggal Penerimaan <span class="text-red-500">*</span></label>
                        <input type="date" name="date" value="{{ old('date', date('Y-m-d')) }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-800 dark:border-gray-600 dark:text-white" required>
                    </div>

                    <!-- 4. Status -->
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Status Transaksi <span class="text-red-500">*</span></label>
                        <select name="status" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-800 dark:border-gray-600 dark:text-white" required>
                            <option value="Diterima" {{ old('status') == 'Diterima' ? 'selected' : '' }}>Diterima (Completed)</option>
                            <option value="Pending" {{ old('status') == 'Pending' ? 'selected' : '' }}>Pending (Menunggu)</option>
                        </select>
                    </div>

                    <!-- 5. Catatan / Keterangan -->
                    <div class="md:col-span-2">
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Catatan Penerimaan</label>
                        <input type="text" name="notes" value="{{ old('notes') }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-800 dark:border-gray-600 dark:text-white" placeholder="Contoh: Penerimaan barang dari supplier PT ABC">
                    </div>
                </div>

                <div class="text-right">
                    <button type="submit" class="text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-green-600 dark:hover:bg-green-700">
                        + Catat Barang Masuk
                    </button>
                </div>
            </form>
        </div>

        <!-- Tabel Daftar Barang Masuk -->
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-4 py-3 text-center">No</th>
                        <th scope="col" class="px-4 py-3">Tanggal</th>
                        <th scope="col" class="px-4 py-3">Produk</th>
                        <th scope="col" class="px-4 py-3 text-center">Jumlah (Qty)</th>
                        <th scope="col" class="px-4 py-3 text-center">Status</th>
                        <th scope="col" class="px-4 py-3">Catatan</th>
                        <th scope="col" class="px-4 py-3">Pencatat</th>
                        <th scope="col" class="px-4 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                    @forelse($transactions as $index => $tx)
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                            <td class="px-4 py-4 text-center font-medium text-gray-900 dark:text-white">{{ $index + 1 }}</td>
                            <td class="px-4 py-4 whitespace-nowrap text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($tx->date)->translatedFormat('d F Y') }}</td>
                            <td class="px-4 py-4 font-semibold text-gray-900 dark:text-white">{{ $tx->product->name ?? '-' }}</td>
                            <td class="px-4 py-4 text-center font-bold text-green-600 dark:text-green-400">+{{ $tx->quantity }}</td>
                            <td class="px-4 py-4 text-center">
                                <span class="bg-green-100 text-green-800 text-xs font-semibold px-2.5 py-0.5 rounded dark:bg-green-900 dark:text-green-300">
                                    {{ $tx->status }}
                                </span>
                            </td>
                            <td class="px-4 py-4 text-xs">{{ $tx->notes ?? '-' }}</td>
                            <td class="px-4 py-4 text-xs">{{ $tx->user->name ?? 'Admin' }}</td>
                            <td class="px-4 py-4 text-center">
                                <form action="{{ route('stock-transactions.destroy', $tx->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus catatan barang masuk ini?')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-3 py-1 bg-red-600 text-white text-xs font-medium rounded hover:bg-red-700 transition">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">Belum ada riwayat transaksi barang masuk.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(method_exists($transactions, 'hasPages') && $transactions->hasPages())
            <div class="mt-4">
                {{ $transactions->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
