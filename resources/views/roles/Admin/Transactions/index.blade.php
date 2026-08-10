@extends('layouts.dashboard')

@section('content')
<div class="p-4 bg-white block border-b border-gray-200 lg:mt-1.5 dark:bg-gray-800 dark:border-gray-700">
    <div class="w-full mb-1">
        <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl dark:text-white">Kelola Transaksi Stok Barang</h1>
    </div>
</div>

<div class="p-4 space-y-6">
    @if(session('success'))
        <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400" role="alert">
            <span class="font-medium">Berhasil!</span> {{ session('success') }}
        </div>
    @endif

    <!-- Form Tambah Transaksi Stok -->
    <div class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm dark:border-gray-700 dark:bg-gray-800">
        <h3 class="mb-4 text-lg font-bold text-gray-900 dark:text-white">Catat Transaksi Stok Baru</h3>
        <form action="{{ route('stock-transactions.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                <!-- Pilihan Produk -->
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Pilih Produk <span class="text-red-500">*</span></label>
                    <select name="product_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-800 dark:border-gray-600 dark:text-white" required>
                        <option value="">-- Pilih Produk --</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}">{{ $product->name }} (SKU: {{ $product->sku }})</option>
                        @endforeach
                    </select>
                </div>

                <!-- Jenis Transaksi -->
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Jenis Transaksi <span class="text-red-500">*</span></label>
                    <select name="type" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-800 dark:border-gray-600 dark:text-white" required>
                        <option value="Masuk">Barang Masuk (+)</option>
                        <option value="Keluar">Barang Keluar (-)</option>
                    </select>
                </div>

                <!-- Jumlah (Quantity) -->
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Jumlah (Qty) <span class="text-red-500">*</span></label>
                    <input type="number" name="quantity" min="1" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-800 dark:border-gray-600 dark:text-white" placeholder="1" required>
                </div>

                <!-- Tanggal Transaksi -->
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tanggal <span class="text-red-500">*</span></label>
                    <input type="date" name="date" value="{{ date('Y-m-d') }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-800 dark:border-gray-600 dark:text-white" required>
                </div>

                <!-- Status -->
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Status <span class="text-red-500">*</span></label>
                    <select name="status" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-800 dark:border-gray-600 dark:text-white" required>
                        <option value="Completed">Completed (Selesai)</option>
                        <option value="Pending">Pending (Menunggu)</option>
                        <option value="Cancelled">Cancelled (Dibatalkan)</option>
                    </select>
                </div>

                <!-- Catatan / Keterangan -->
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Catatan</label>
                    <input type="text" name="notes" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-800 dark:border-gray-600 dark:text-white" placeholder="Keterangan transaksi...">
                </div>
            </div>

            <div class="text-right">
                <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700">
                    Simpan Transaksi
                </button>
            </div>
        </form>
    </div>

    <!-- Tabel Daftar Transaksi Stok -->
    <div class="bg-white border border-gray-200 rounded-lg shadow-sm dark:border-gray-700 dark:bg-gray-800 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th class="px-4 py-3">No</th>
                        <th class="px-4 py-3">Tanggal</th>
                        <th class="px-4 py-3">Produk</th>
                        <th class="px-4 py-3 text-center">Jenis</th>
                        <th class="px-4 py-3 text-center">Jumlah</th>
                        <th class="px-4 py-3 text-center">Status</th>
                        <th class="px-4 py-3">Catatan</th>
                        <th class="px-4 py-3">Pencatat</th>
                        <th class="px-4 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $index => $tx)
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                            <td class="px-4 py-3">{{ $index + 1 }}</td>
                            <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($tx->date)->format('d M Y') }}</td>
                            <td class="px-4 py-3 font-semibold text-gray-900 dark:text-white">{{ $tx->product->name ?? '-' }}</td>
                            <td class="px-4 py-3 text-center">
                                @if($tx->type == 'Masuk')
                                    <span class="bg-green-100 text-green-800 text-xs font-semibold px-2.5 py-0.5 rounded dark:bg-green-900 dark:text-green-300">+ Barang Masuk</span>
                                @else
                                    <span class="bg-red-100 text-red-800 text-xs font-semibold px-2.5 py-0.5 rounded dark:bg-red-900 dark:text-red-300">- Barang Keluar</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-center font-bold">{{ $tx->quantity }}</td>
                            <td class="px-4 py-3 text-center">
                                @if($tx->status == 'Completed')
                                    <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2 py-0.5 rounded dark:bg-blue-900 dark:text-blue-300">Completed</span>
                                @elseif($tx->status == 'Pending')
                                    <span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2 py-0.5 rounded dark:bg-yellow-900 dark:text-yellow-300">Pending</span>
                                @else
                                    <span class="bg-gray-100 text-gray-800 text-xs font-medium px-2 py-0.5 rounded dark:bg-gray-700 dark:text-gray-300">Cancelled</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-xs">{{ $tx->notes ?? '-' }}</td>
                            <td class="px-4 py-3 text-xs">{{ $tx->user->name ?? 'Admin' }}</td>
                            <td class="px-4 py-3 text-center">
                                <form action="{{ route('stock-transactions.destroy', $tx->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus transaksi stok ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="font-medium text-red-600 dark:text-red-500 hover:underline text-xs">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-6 py-4 text-center text-gray-400">Belum ada riwayat transaksi stok.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if(method_exists($transactions, 'hasPages') && $transactions->hasPages())
            <div class="p-4 border-t border-gray-200 dark:border-gray-700">
                {{ $transactions->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
