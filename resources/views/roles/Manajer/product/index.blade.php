@extends('layouts.dashboard')
@section('content')
    <div class="px-4 pt-6">
        <x-notify::notify />
        <h1 class="text-2xl font-medium dark:text-white text-slate-700">
            {{ $title }}
        </h1>

        <section>
            <x-table.table-layout :title="'List Products Data'" :description="'List Item of Products Information'">
                @slot('additional')
                    {{-- Undefined --}}
                @endslot

                @slot('header')
                    <th class="p-4 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-white">No.</th>
                    <th class="p-4 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-white">Name</th>
                    <th class="p-4 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-white">Category</th>
                    <th class="p-4 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-white">SKU</th>
                    <th class="p-4 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-white">Purchase Price</th>
                    <th class="p-4 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-white">Action</th>
                @endslot

                @foreach ($products as $product)
                    <tr>
                        <td
                            class="p-4 text-sm font-normal text-gray-900 border-b whitespace-nowrap dark:border-gray-500 dark:text-white">
                            {{ $loop->index + 1 }}</td>
                        <td
                            class="p-4 text-sm font-normal text-gray-900 whitespace-nowrap border-b dark:border-gray-500 dark:text-white">
                            {{ $product->name }}</td>
                        <td
                            class="p-4 text-sm font-normal text-gray-900 whitespace-nowrap border-b dark:border-gray-500 dark:text-white">
                            {{ $product->categories->name }}</td>
                        <td
                            class="p-4 text-sm font-normal text-gray-900 whitespace-nowrap border-b dark:border-gray-500 dark:text-white">
                            {{ $product->sku }}</td>
                        <td
                            class="p-4 text-sm font-normal text-gray-900 whitespace-nowrap border-b dark:border-gray-500 dark:text-white">
                            {{ Number::currency($product->purchase_price, 'Rp.') }}</td>
                        <td class="p-4 text-sm font-normal text-gray-900 whitespace-nowrap border-b dark:border-gray-500 dark:text-white">
                            <a href="{{ route('products.show', $product->id) }}" class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm p-3 text-center inline-flex items-center me-2 dark:bg-blue-600 dark:hover:bg-blue-800 dark:focus:ring-blue-800 hover:cursor-pointer">
                                <x-heroicon-s-eye class="w-5 h-5 text-white" />
                                <span class="ml-2">Detail</span>
                            </a>
                        </td>
                    </tr>
                @endforeach

                @slot('pagination')
                    {{ $products->links() }}
                @endslot
            </x-table.table-layout>
        </section>
    </div>
@endsection
