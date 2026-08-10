@extends('layouts.dashboard')

@section('content')
<div class="p-4 bg-white block border-b border-gray-200 lg:mt-1.5 dark:bg-gray-800 dark:border-gray-700">
    <div class="w-full mb-1">
        <div class="mb-4">
            <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl dark:text-white">Manajemen Kategori</h1>
        </div>
        
        {{-- Pesan Sukses --}}
        @if(session('success'))
            <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400" role="alert">
                {{ session('success') }}
            </div>
        @endif

        {{-- Pesan Error Validasi --}}
        @if($errors->any())
            <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400" role="alert">
                <ul class="list-disc pl-5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Form Tambah Kategori Baru -->
        <div class="p-4 mb-6 bg-gray-50 rounded-lg dark:bg-gray-700 border border-gray-200 dark:border-gray-600">
            <h2 class="mb-3 text-lg font-bold text-gray-900 dark:text-white">Tambah Kategori Baru</h2>
            <form action="{{ route('categories.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                        Nama Kategori <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" value="{{ old('name') }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-800 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" placeholder="Contoh: Elektronik" required>
                    @error('name')
                        <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Deskripsi</label>
                    <textarea name="description" rows="3" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-800 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" placeholder="Deskripsi singkat kategori...">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
                <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                    Simpan Kategori
                </button>
            </form>
        </div>

        <!-- Tabel Data Kategori -->
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg border border-gray-200 dark:border-gray-700">
            <table class="w-full text-sm text-left text-gray-800 dark:text-white">
                <thead class="text-xs uppercase bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-white font-bold border-b dark:border-gray-600">
                    <tr>
                        <th scope="col" class="px-3 py-3.5 text-center w-12 text-gray-900 dark:text-white">No</th>
                        <th scope="col" class="px-6 py-3.5 text-gray-900 dark:text-white">Nama Kategori</th>
                        <th scope="col" class="px-6 py-3.5 text-gray-900 dark:text-white">Deskripsi</th>
                        <th scope="col" class="px-6 py-3.5 text-center text-gray-900 dark:text-white">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                    @forelse($categories as $index => $category)
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-3 py-4 text-center font-bold text-gray-900 whitespace-nowrap dark:text-white">
                                {{ $index + 1 }}
                            </td>
                            <td class="px-6 py-4 font-bold text-gray-900 whitespace-nowrap dark:text-white">
                                {{ $category->name }}
                            </td>
                            <td class="px-6 py-4 font-medium text-gray-800 dark:text-white">
                                {{ $category->description ?? '-' }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center space-x-3">
                                    <a href="{{ route('categories.edit', $category->id) }}" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">
                                        Edit
                                    </a>
                                    <span class="text-gray-300 dark:text-gray-600">|</span>
                                    <form action="{{ route('categories.destroy', $category->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus kategori ini?')" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="font-medium text-red-600 dark:text-red-500 hover:underline">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center text-gray-500 dark:text-white">
                                Belum ada data kategori. Silakan tambah kategori di atas.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</div>
@endsection