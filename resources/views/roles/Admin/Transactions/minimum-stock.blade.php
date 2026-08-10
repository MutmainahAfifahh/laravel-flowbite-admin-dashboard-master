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
    </div>
</div>
@endsection
