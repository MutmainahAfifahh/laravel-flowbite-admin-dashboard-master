@extends('layouts.dashboard')

@section('content')
<div class="p-4 bg-white block sm:flex items-center justify-between border-b border-gray-200 lg:mt-1.5 dark:bg-gray-800 dark:border-gray-700">
    <div class="w-full mb-1">
        <div class="mb-4">
            <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl dark:text-white">Manajemen Supplier</h1>
        </div>
        
        @if(session('success'))
            <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400" role="alert">
                {{ session('success') }}
            </div>
        @endif

        <!-- Form Tambah Supplier -->
        <div class="p-4 mb-6 bg-gray-50 rounded-lg dark:bg-gray-700 border border-gray-200 dark:border-gray-600">
            <h2 class="mb-3 text-lg font-bold text-gray-900 dark:text-white">Tambah Supplier Baru</h2>
            <form action="{{ route('suppliers.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @csrf
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama Supplier</label>
                    <input type="text" name="name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-800 dark:border-gray-600 dark:text-white dark:placeholder-gray-400" required>
                </div>
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Email</label>
                    <input type="email" name="email" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-800 dark:border-gray-600 dark:text-white dark:placeholder-gray-400">
                </div>
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Telepon</label>
                    <input type="text" name="phone" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-800 dark:border-gray-600 dark:text-white dark:placeholder-gray-400">
                </div>
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Alamat</label>
                    <input type="text" name="address" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-800 dark:border-gray-600 dark:text-white dark:placeholder-gray-400">
                </div>
                <div class="md:col-span-2">
                    <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700">Simpan Supplier</button>
                </div>
            </form>
        </div>

        <!-- Tabel Supplier -->
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg border border-gray-200 dark:border-gray-700">
            <table class="w-full text-sm text-left text-gray-800 dark:text-white">
                <thead class="text-xs uppercase bg-gray-100 dark:bg-gray-700 dark:text-white font-bold border-b dark:border-gray-600">
                    <tr>
                        <th class="px-6 py-3.5">#</th>
                        <th class="px-6 py-3.5">Nama Supplier</th>
                        <th class="px-6 py-3.5">Telepon</th>
                        <th class="px-6 py-3.5">Email</th>
                        <th class="px-6 py-3.5">Alamat</th>
                        <th class="px-6 py-3.5">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($suppliers as $index => $supplier)
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-6 py-4 font-bold text-gray-900 dark:text-white">{{ $index + 1 }}</td>
                            <td class="px-6 py-4 font-bold text-gray-900 dark:text-white">{{ $supplier->name }}</td>
                            <td class="px-6 py-4 font-medium text-gray-800 dark:text-white">{{ $supplier->phone ?? '-' }}</td>
                            <td class="px-6 py-4 font-medium text-gray-800 dark:text-white">{{ $supplier->email ?? '-' }}</td>
                            <td class="px-6 py-4 font-medium text-gray-800 dark:text-white">{{ $supplier->address ?? '-' }}</td>
                            <td class="px-6 py-4 flex items-center space-x-3">
                                <!-- Tombol Aksi Tetap Mempertahankan Warna Aslinya -->
                                <a href="{{ route('suppliers.show', $supplier->id) }}" class="font-medium text-green-600 dark:text-green-500 hover:underline">Detail</a>
                                <a href="{{ route('suppliers.edit', $supplier->id) }}" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Edit</a>
                                <form action="{{ route('suppliers.destroy', $supplier->id) }}" method="POST" onsubmit="return confirm('Hapus supplier ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="font-medium text-red-600 dark:text-red-500 hover:underline">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500 dark:text-white">Belum ada data supplier.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection