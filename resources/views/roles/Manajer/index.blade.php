@extends('layouts.dashboard')
@section('content')
    <div class="px-4 pt-6">
        <x-notify::notify />
        <h1 class="text-2xl font-medium dark:text-white text-slate-700">{{ $title }}</h1>

        <section>
            <div class="container mx-auto px-4 py-8">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-8">

                    {{-- Stock Menipis --}}
                    <div class="bg-white rounded-lg shadow-md p-6 dark:bg-slate-700">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-xl font-semibold text-violet-700 dark:text-violet-400">
                                Stock Menipis
                            </h2>
                            <x-heroicon-m-arrow-down-on-square-stack class="h-8 w-8 text-violet-600 dark:text-violet-400" />
                        </div>
                        <p class="text-3xl font-bold text-violet-700 dark:text-violet-400">
                            {{ $lowStock }}
                        </p>
                        <p class="text-xs font-medium text-violet-700 mt-2 dark:text-violet-400">
                            Total Jumlah Stock yang Menipis
                        </p>
                    </div>

                    {{-- Barang Masuk Hari ini --}}
                    <div class="bg-white rounded-lg shadow-md p-6 dark:bg-slate-700">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-xl font-semibold text-green-700 dark:text-green-400">
                                Total Barang Masuk
                            </h2>
                            <x-heroicon-c-bars-arrow-up class="h-8 w-8 text-green-600 dark:text-green-400" />
                        </div>
                        <p class="text-3xl font-bold text-green-700 dark:text-green-400">
                            {{ $incomingTransaction }}
                        </p>
                        <p class="text-xs font-medium text-green-700 mt-2 dark:text-green-400">
                            Rekapitulasi barang masuk dalam 1 hari terakhir.
                        </p>
                    </div>

                    {{-- Barang Keluar Hari ini --}}
                    <div class="bg-white rounded-lg shadow-md p-6 dark:bg-slate-700">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-xl font-semibold text-red-700 dark:text-red-500">
                                Total Barang Keluar
                            </h2>
                            <x-heroicon-c-bars-arrow-down class="h-8 w-8 text-red-600 dark:text-red-500" />
                        </div>
                        <p class="text-3xl font-bold text-red-700 dark:text-red-500">
                            {{ $outgoingTransaction }}
                        </p>
                        <p class="text-xs font-medium text-red-700 mt-2 dark:text-red-500">
                            Rekapitulasi barang keluar dalam 1 hari terakhir.
                        </p>
                    </div>
                </div>
                
            </div>
        </section>
    </div>
@endsection
