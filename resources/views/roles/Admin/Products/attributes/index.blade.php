@extends('layouts.dashboard')

@section('content')
<div class="p-4 bg-white block border-b border-gray-200 lg:mt-1.5 dark:bg-gray-800 dark:border-gray-700">
    <div class="w-full mb-4">
        <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl dark:text-white mb-4">Manajemen Atribut Produk</h1>

        {{-- Notifikasi Sukses --}}
        @if(session('success'))
            <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400" role="alert">
                {{ session('success') }}
            </div>
        @endif

        {{-- Notifikasi Error Global --}}
        @if($errors->any())
            <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400" role="alert">
                <ul class="list-disc pl-5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Form Tambah Atribut Baru -->
        <div class="p-4 mb-6 bg-gray-50 rounded-lg dark:bg-gray-700 border border-gray-200 dark:border-gray-600">
            <h2 class="mb-4 text-lg font-bold text-gray-900 dark:text-white">Tambah Atribut Produk</h2>
            
            <form action="{{ route('attributes.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                @csrf
                
                <!-- Pilih Produk -->
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Pilih Produk <span class="text-red-500">*</span></label>
                    <select name="product_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-800 dark:border-gray-600 dark:text-white" required>
                        <option value="">-- Pilih Produk --</option>
                        @foreach($products as $prod)
                            <option value="{{ $prod->id }}" {{ old('product_id') == $prod->id ? 'selected' : '' }}>
                                {{ $prod->name }} (SKU: {{ $prod->sku }})
                            </option>
                        @endforeach
                    </select>
                    @error('product_id')
                        <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Nama Atribut -->
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama Atribut <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" placeholder="Contoh: Warna, Ukuran, Garansi" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-800 dark:border-gray-600 dark:text-white" required>
                    @error('name')
                        <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Nilai Atribut -->
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nilai Atribut <span class="text-red-500">*</span></label>
                    <div class="flex gap-2">
                        <input type="text" name="value" value="{{ old('value') }}" placeholder="Contoh: Hitam, XL, 1 Tahun" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-800 dark:border-gray-600 dark:text-white" required>
                        <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800 whitespace-nowrap">
                            + Tambah
                        </button>
                    </div>
                    @error('value')
                        <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </form>
        </div>

        <!-- Tabel Daftar Atribut -->
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-4 text-center w-16">No</th>
                        <th scope="col" class="px-6 py-4">Produk</th>
                        <th scope="col" class="px-6 py-4">Nama Atribut</th>
                        <th scope="col" class="px-6 py-4">Nilai Atribut</th>
                        <th scope="col" class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                    @forelse($attributes as $index => $attr)
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                            <td class="px-4 py-4 text-center font-medium text-gray-900 dark:text-white">
                                {{ $index + 1 }}
                            </td>
                            <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ $attr->product->name ?? '-' }}
                            </td>
                            <td class="px-6 py-4 font-semibold text-gray-900 dark:text-white">
                                {{ $attr->name }}
                            </td>
                            <td class="px-6 py-4 text-gray-600 dark:text-gray-300">
                                {{ $attr->value }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center space-x-2">
                                    <a href="{{ route('attributes.edit', $attr->id) }}" class="px-3 py-1 bg-yellow-500 text-white text-xs font-medium rounded hover:bg-yellow-600 transition inline-block">
                                        Edit
                                    </a>
                                    <span class="text-gray-300 dark:text-gray-600">|</span>
                                    <form action="{{ route('attributes.destroy', $attr->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus atribut ini?')" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-3 py-1 bg-red-600 text-white text-xs font-medium rounded hover:bg-red-700 transition">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">Belum ada atribut produk. Silakan tambah atribut baru di atas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</div>
@endsection