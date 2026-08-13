@extends('layouts.dashboard')

@section('content')
<div class="p-4 bg-white block border-b border-gray-200 lg:mt-1.5 dark:bg-gray-800 dark:border-gray-700">
    <div class="w-full mb-4">
        <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl dark:text-white mb-4">Pengaturan Stok Minimum Global</h1>

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

        <!-- Form Pengaturan Stok Minimum -->
        <div class="p-6 bg-gray-50 rounded-lg dark:bg-gray-700 border border-gray-200 dark:border-gray-600 max-w-xl">
            <h2 class="mb-2 text-lg font-bold text-gray-900 dark:text-white">Batas Stok Minimum Global</h2>
            <p class="mb-4 text-xs text-gray-500 dark:text-gray-400">
                Batas stok minimum ini digunakan untuk memicu peringatan (alert) ketika jumlah stok suatu barang di gudang menipis atau berada di bawah ambang batas ini.
            </p>

            <form action="{{ route('stock.update-minimum') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Jumlah Stok Minimum Saat Ini</label>
                    <input type="number" name="minimum_stock" min="0" value="{{ old('minimum_stock', $minimumStock) }}" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-3 dark:bg-gray-800 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" placeholder="Contoh: 5" required>
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700">
                        Simpan Batas Stok Minimum
                    </button>
                </div>
            </form>
        </div>

        <!-- Info Dampak Perubahan ke Staff -->
        <div class="mt-6 p-5 bg-amber-50 rounded-lg dark:bg-amber-950/40 border border-amber-200 dark:border-amber-700 max-w-xl">
            <div class="flex items-start gap-3">
                <svg class="w-6 h-6 text-amber-600 dark:text-amber-400 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495zM10 5a.75.75 0 01.75.75v3.5a.75.75 0 01-1.5 0v-3.5A.75.75 0 0110 5zm0 9a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                </svg>
                <div>
                    <h3 class="text-sm font-bold text-amber-800 dark:text-amber-300 mb-2">⚡ Dampak Perubahan ke Staff</h3>
                    <ul class="text-xs text-amber-700 dark:text-amber-400 space-y-1.5 list-disc list-inside">
                        <li>Perubahan batas minimum ini <strong>langsung berlaku secara real-time</strong> di halaman Staff (otomatis sinkron setiap 30 detik).</li>
                        <li>Staff <strong>tidak akan bisa menyimpan</strong> transaksi (Barang Masuk maupun Keluar) jika qty yang diinput <strong>kurang dari batas minimum</strong> ini.</li>
                        <li>Untuk Barang Keluar, sisa stok setelah dikurangi juga <strong>tidak boleh di bawah batas minimum</strong> ini.</li>
                        <li>Contoh: Jika batas minimum diatur <strong>20</strong>, maka Staff yang input qty <strong>10</strong> akan ditolak otomatis.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
