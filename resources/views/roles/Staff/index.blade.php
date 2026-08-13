@extends('layouts.dashboard')
@section('content')
    <div class="px-4 pt-6">
        <x-notify::notify />
        <h1 class="text-2xl font-medium dark:text-white text-slate-700">{{ $title }}</h1>

        <section>
            <div class="container mx-auto px-4 py-8">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-4 mb-8">

                    {{-- Barang Masuk --}}
                    <div class="bg-white rounded-lg shadow-md p-6 dark:bg-slate-700">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-xl font-semibold text-yellow-700 dark:text-yellow-400">
                                Barang Masuk
                            </h2>
                            <x-heroicon-m-arrow-down-on-square-stack class="h-8 w-8 text-yellow-600 dark:text-yellow-400" />
                        </div>
                        <p class="text-3xl font-bold text-yellow-700 dark:text-yellow-400">
                            {{ $incomingItem }}
                        </p>
                        <p class="text-xs font-medium text-yellow-700 mt-2 dark:text-yellow-400">
                            Total Barang Masuk yang perlu diperiksa
                        </p>
                    </div>

                    {{-- Barang Keluar --}}
                    <div class="bg-white rounded-lg shadow-md p-6 dark:bg-slate-700">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-xl font-semibold text-red-700 dark:text-red-400">
                                Barang Keluar
                            </h2>
                            <x-heroicon-c-bars-arrow-up class="h-8 w-8 text-red-600 dark:text-red-400" />
                        </div>
                        <p class="text-3xl font-bold text-red-700 dark:text-red-400">
                            {{ $outgoingItem }}
                        </p>
                        <p class="text-xs font-medium text-red-700 mt-2 dark:text-red-400">
                            Total Barang keluar yang perlu disiapkan
                        </p>
                    </div>
                </div>
                
            </div>
        </section>
    </div>
@endsection
