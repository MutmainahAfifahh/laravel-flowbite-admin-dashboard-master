@extends('layouts.default.dashboard')
@section('content')
    <div class="px-4 pt-6">
        <x-notify::notify />
        <h1 class="text-2xl font-medium text-center md:text-start dark:text-white text-slate-700">
            {{ $title }}
        </h1>

        <section>
            <div class="bg-white dark:bg-slate-700 rounded-md shadow-md p-6 my-5">
                <h3 class="text-xl font-semibold text-gray-600 dark:text-white mb-4">Transaksi Barang</h3>
                <form action="{{ route('stock.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <x-alert-error-form message="Please review the errors below:" />
                    <div class="grid gap-4 mb-4 grid-cols-2">
                        <x-form.select-option name="product_id" label="Product" placeholder="Select Product" required>
                            @foreach($product as $products)
                                <option value="{{ $products->id }}" >{{ $products->name }}</option>
                            @endforeach
                        </x-form.select-option>

                        <x-form.select-option name="type" label="Type" placeholder="Select Item Type" required>
                            <option value="Masuk">Masuk</option>
                            <option value="Keluar">Keluar</option>
                        </x-form.select-option>

                        <x-form.input name="date" type="date" label="Tanggal (Date)" colSpan="col-span-2 sm:col-span-1 md:col-span-1" required />
                        <input type="text" name="status" value="Pending" hidden>

                        <x-form.select-option name="supplier_id" colSpan="" label="Suppliers" placeholder="Select Suppliers" required>
                            @foreach($supplier as $suppliers)
                                <option value="{{ $suppliers->name }}" >{{ $suppliers->name }}</option>
                            @endforeach
                        </x-form.select-option>

                        <x-form.input name="quantity" type="number" colSpan="col-span-2" label="Quantity" placeholder="Item Quantity (Number)" required />
                        <x-form.textarea name="notes" label="Notes" placeholder="Tell them with a note ..." />

                        <div class="col-span-2 flex justify-end">
                            <button type="submit" class="text-white inline-flex bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mt-5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                <x-heroicon-o-shopping-cart class="w-6 h-6 text-white mr-2" />
                                Add Transaction
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <x-table.table-layout :title="'List Action Stock Transaction'" :description="'History Transaction In/Out Item Products'">
                @slot('additional')
                    <form action="{{ route('stock.transaction') }}" method="GET">
                        <div class="flex justify-between items-center sm:justify-start gap-3">
                            <x-form.select-option name="type" label="Filter by:" placeholder="All Transaction Type">
                                <option value="Masuk" {{ request('type') == 'Masuk' ? 'selected' : '' }}>Masuk</option>
                                <option value="Keluar" {{ request('type') == 'Keluar' ? 'selected' : '' }}>Keluar</option>
                            </x-form.select-option>
                            <button type="submit" name="action" value="view" class="text-gray-900 mt-7 bg-white hover:bg-gray-100 border border-gray-300 focus:ring-4 focus:outline-none focus:ring-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center dark:focus:ring-gray-600 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:hover:bg-gray-700">
                                <x-tabler-filter class="w-5 h-5 mr-1" />
                                Filter
                            </button>
                            <button type="submit" name="action" value="print-transaction" class="text-gray-900 mt-7 bg-white hover:bg-gray-100 border border-gray-300 focus:ring-4 focus:outline-none focus:ring-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center dark:focus:ring-gray-600 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:hover:bg-gray-700">
                                <x-tabler-table-export class="w-5 h-5 me-2 -ms-1" />
                                Print
                            </button>
                        </div>
                    </form>
                @endslot
                
                @slot('header')
                    <th class="p-4 text-xs font-medium tracking-wider text-center text-gray-500 uppercase dark:text-white">No.</th>
                    <th class="p-4 text-xs font-medium tracking-wider text-center text-gray-500 uppercase dark:text-white">Product</th>
                    <th class="p-4 text-xs font-medium tracking-wider text-center text-gray-500 uppercase dark:text-white">Type</th>
                    <th class="p-4 text-xs font-medium tracking-wider text-center text-gray-500 uppercase dark:text-white">Quantity</th>
                    <th class="p-4 text-xs font-medium tracking-wider text-center text-gray-500 uppercase dark:text-white">User</th>
                    <th class="p-4 text-xs font-medium tracking-wider text-center text-gray-500 uppercase dark:text-white">Status</th>
                @endslot

                @forelse ($stockByType as $transaction)
                    @php
                        $statusClasses = [
                            'Pending' => 'bg-yellow-200 dark:bg-yellow-700 text-yellow-700 dark:text-yellow-200',
                            'Diterima' => 'bg-green-200 dark:bg-green-700 text-green-700 dark:text-green-200',
                            'Ditolak' => 'bg-red-200 dark:bg-red-700 text-red-700 dark:text-red-200',
                            'Dikeluarkan' => 'bg-blue-200 dark:bg-blue-700 text-blue-700 dark:text-blue-200',
                        ];

                        $statusClass = $statusClasses[$transaction->status] ?? 'bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200';
                    @endphp
                    <tr>
                        <td class="p-4 text-sm font-normal text-center text-gray-900 border-b whitespace-nowrap dark:border-gray-500 dark:text-white">
                            {{ $loop->index + 1 }}
                        </td>
                        <td class="p-4 text-sm font-normal text-gray-900 text-center whitespace-nowrap border-b dark:border-gray-500 dark:text-white">
                            {{ $transaction->products->name }}
                        </td>
                        <td class="p-4 text-sm font-normal text-gray-900 text-center whitespace-nowrap border-b dark:border-gray-500 dark:text-white">
                            <span class="bg-lime-400 text-lime-800 dark:text-white dark:bg-lime-700 py-1 px-3 rounded-full text-xs font-medium">
                                {{ $transaction->type }}
                            </span>
                        </td>
                        <td class="p-4 text-sm font-normal text-gray-900 text-center whitespace-nowrap border-b dark:border-gray-500 dark:text-white">
                            <span class="bg-blue-400 text-blue-800 dark:text-white dark:bg-blue-700 py-1 px-3 rounded-full text-sm font-medium">
                                {{ $transaction->quantity }}
                            </span>
                        </td>
                        <td class="p-4 text-sm font-normal text-gray-900 text-center whitespace-nowrap border-b dark:border-gray-500 dark:text-white">
                            {{ $transaction->users->name }}</td>
                        <td class="p-4 text-xs font-medium text-gray-900 text-center whitespace-nowrap border-b dark:border-gray-500">
                            <span class="py-1 px-2 rounded-full {{ $statusClass }}">
                                {{ $transaction->status }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="p-4 text-sm text-center font-semibold italic text-gray-900 border-b whitespace-nowrap dark:border-gray-500 dark:text-white">
                            Data Not Found
                        </td>
                    </tr>
                @endforelse

                @slot('pagination')
                    {{ $stockByType->links() }}
                @endslot
            </x-table.table-layout>

            <x-table.table-layout :title="'Stock Transaction by Filter'" :description="'Filter Stock Transaction Item Products'">
                @slot('additional')
                    <form action="{{ route('stock.transaction') }}" method="GET">
                        <div class="flex justify-between items-center gap-3">
                            <x-form.select-option name="periods" label="Period:" placeholder="All Periods">
                                <option value="7 Days" {{ request('periods') == '7 Days' ? 'selected' : '' }}>Last 7 Days</option>
                                <option value="30 Days" {{ request('periods') == '30 Days' ? 'selected' : '' }}>Last 30 Days</option>
                                <option value="3 Months" {{ request('periods') == '3 Months' ? 'selected' : '' }}>Last 3 Months</option>
                                <option value="custom" {{ request('periods') == 'custom' ? 'selected' : '' }}>Custom</option>
                            </x-form.select-option>
                            <x-form.select-option name="categories" label="Category:" placeholder="All Categories">
                                @foreach($category as $categories)
                                    <option value="{{ $categories->name }}" {{ request('categories') == $categories->name ? 'selected' : '' }}>{{ $categories->name }}</option>
                                @endforeach
                            </x-form.select-option>
                            <button type="submit" name="action" value="view" class="text-gray-900 mt-7 bg-white hover:bg-gray-100 border border-gray-300 focus:ring-4 focus:outline-none focus:ring-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center dark:focus:ring-gray-600 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:hover:bg-gray-700">
                                <x-tabler-filter class="w-5 h-5 mr-0 md:mr-1" />
                                <span class="hidden md:block">Filter</span>
                            </button>
                            <button type="submit" name="action" value="print-stock" class="text-gray-900 mt-7 bg-white hover:bg-gray-100 border border-gray-300 focus:ring-4 focus:outline-none focus:ring-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center dark:focus:ring-gray-600 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:hover:bg-gray-700">
                                <x-tabler-table-export class="w-5 h-5 me-0 -ms-0 md:me-2 md:-ms-1" />
                                <span class="hidden md:block">Print</span>
                            </button>
                        </div>
                        <div class="justify-center flex-col items-center gap-3 mt-3">
                            <div id="custom-date-range" class="{{ request('periods') == 'custom' ? '' : 'hidden' }} space-y-2">
                                <x-form.input name="start_date" type="date" label="Start Date:" value="{{ request('start_date') }}" />
                                <x-form.input name="end_date" type="date" label="End Date:" value="{{ request('end_date') }}" />
                            </div>
                        </div>
                    </form>
                @endslot
                
                @slot('header')
                    <th class="p-4 text-xs font-medium tracking-wider text-center text-gray-500 uppercase dark:text-white">No.</th>
                    <th class="p-4 text-xs font-medium tracking-wider text-center text-gray-500 uppercase dark:text-white">Product</th>
                    <th class="p-4 text-xs font-medium tracking-wider text-center text-gray-500 uppercase dark:text-white">Category</th>
                    <th class="p-4 text-xs font-medium tracking-wider text-center text-gray-500 uppercase dark:text-white">User</th>
                    <th class="p-4 text-xs font-medium tracking-wider text-center text-gray-500 uppercase dark:text-white">Quantity</th>
                    <th class="p-4 text-xs font-medium tracking-wider text-center text-gray-500 uppercase dark:text-white">Date</th>
                    <th class="p-4 text-xs font-medium tracking-wider text-center text-gray-500 uppercase dark:text-white">Status</th>
                @endslot

                @forelse ($stockByCriteria as $transaction)
                    @php
                        $statusClasses = [
                            'Pending' => 'bg-yellow-200 dark:bg-yellow-700 text-yellow-700 dark:text-yellow-200',
                            'Diterima' => 'bg-green-200 dark:bg-green-700 text-green-700 dark:text-green-200',
                            'Ditolak' => 'bg-red-200 dark:bg-red-700 text-red-700 dark:text-red-200',
                            'Dikeluarkan' => 'bg-blue-200 dark:bg-blue-700 text-blue-700 dark:text-blue-200',
                        ];

                        $statusClass = $statusClasses[$transaction->status] ?? 'bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200';
                    @endphp
                    <tr>
                        <td class="p-4 text-sm font-normal text-gray-900 text-center border-b whitespace-nowrap dark:border-gray-500 dark:text-white">{{ $loop->index + 1 }}</td>
                        <td class="p-4 text-sm font-normal text-gray-900 text-center whitespace-nowrap border-b dark:border-gray-500 dark:text-white">{{ $transaction->products->name }}</td>
                        <td class="p-4 text-sm font-normal text-gray-900 text-center whitespace-nowrap border-b dark:border-gray-500 dark:text-white">
                            <span class="bg-cyan-300 text-cyan-800 dark:text-white dark:bg-cyan-700 py-1 px-3 rounded-full text-xs font-medium">
                                {{ $transaction->products->categories->name }}
                            </span>
                        </td>
                        <td class="p-4 text-sm font-normal text-gray-900 text-center whitespace-nowrap border-b dark:border-gray-500 dark:text-white">{{ $transaction->users->name }}</td>
                        <td class="p-4 text-sm font-normal text-gray-900 text-center whitespace-nowrap border-b dark:border-gray-500 dark:text-white">
                            <span class="bg-blue-400 text-blue-800 dark:text-white dark:bg-blue-700 py-1 px-3 rounded-full text-sm font-medium">
                                {{ $transaction->quantity }}
                            </span>
                        </td>
                        <td class="p-4 text-sm font-normal text-gray-900 text-center whitespace-nowrap border-b dark:border-gray-500 dark:text-white">
                            {{ \Carbon\Carbon::parse($transaction->date)->translatedFormat('d F Y') }}
                        </td>
                        <td class="p-4 text-xs font-medium text-gray-900 text-center whitespace-nowrap border-b dark:border-gray-500">
                            <span class="py-1 px-2 rounded-full {{ $statusClass }}">
                                {{ $transaction->status }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="p-4 text-sm text-center font-semibold italic text-gray-900 border-b whitespace-nowrap dark:border-gray-500 dark:text-white">
                            Data Not Found
                        </td>
                    </tr>
                @endforelse

                @slot('pagination')
                    {{ $stockByCriteria->links() }}
                @endslot
            </x-table.table-layout>
        </section>
    </div> 
@endsection
