@extends('layouts.dashboard')

@section('content')
<div class="p-4 bg-white block border-b border-gray-200 lg:mt-1.5 dark:bg-gray-800 dark:border-gray-700">
    <div class="w-full mb-4">
        <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl dark:text-white mb-4">Edit Atribut Produk</h1>

        @if($errors->any())
            <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400" role="alert">
                <ul class="list-disc pl-5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="p-4 bg-gray-50 rounded-lg dark:bg-gray-700 max-w-xl">
            <form action="{{ route('attributes.update', $attribute->id) }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <!-- Nama Produk (Read Only) -->
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Produk</label>
                    <input type="text" value="{{ $attribute->product->name ?? '-' }}" class="bg-gray-200 border border-gray-300 text-gray-700 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-600 dark:text-gray-300 cursor-not-allowed" readonly>
                </div>

                <!-- Nama Atribut -->
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama Atribut <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $attribute->name) }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-800 dark:border-gray-600 dark:text-white" required>
                </div>

                <!-- Nilai Atribut -->
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nilai Atribut <span class="text-red-500">*</span></label>
                    <input type="text" name="value" value="{{ old('value', $attribute->value) }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-800 dark:border-gray-600 dark:text-white" required>
                </div>

                <!-- Tombol Aksi -->
                <div class="flex items-center space-x-3 pt-2">
                    <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none">
                        Update Atribut
                    </button>
                    <a href="{{ route('attributes.index') }}" class="text-gray-900 bg-white border border-gray-300 hover:bg-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
