@extends('layouts.dashboard')

@section('content')
<div class="p-4 bg-white block border-b border-gray-200 lg:mt-1.5 dark:bg-gray-800 dark:border-gray-700">
    <div class="w-full mb-4">
        <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl dark:text-white mb-4">Detail Supplier: {{ $supplier->name ?? '-' }}</h1>

        <div class="p-6 bg-gray-50 rounded-lg dark:bg-gray-700 max-w-2xl space-y-4">
            <div>
                <label class="block mb-1 text-sm font-medium text-gray-500 dark:text-gray-400">Nama Supplier</label>
                <p class="text-base font-semibold text-gray-900 dark:text-white">{{ $supplier->name ?? '-' }}</p>
            </div>

            <div>
                <label class="block mb-1 text-sm font-medium text-gray-500 dark:text-gray-400">Email</label>
                <p class="text-base font-semibold text-gray-900 dark:text-white">{{ $supplier->email ?? '-' }}</p>
            </div>

            <div>
                <label class="block mb-1 text-sm font-medium text-gray-500 dark:text-gray-400">Telepon</label>
                <p class="text-base font-semibold text-gray-900 dark:text-white">{{ $supplier->phone ?? '-' }}</p>
            </div>

            <div>
                <label class="block mb-1 text-sm font-medium text-gray-500 dark:text-gray-400">Alamat</label>
                <p class="text-base font-semibold text-gray-900 dark:text-white">{{ $supplier->address ?? '-' }}</p>
            </div>

            <div class="flex items-center space-x-3 pt-4 border-t border-gray-200 dark:border-gray-600">
                <a href="{{ route('suppliers.edit', $supplier->id) }}" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Edit Supplier</a>
                <a href="{{ route('suppliers.index') }}" class="text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700">Kembali</a>
            </div>
        </div>
    </div>
</div>
@endsection
