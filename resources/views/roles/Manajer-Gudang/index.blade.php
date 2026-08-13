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

                <div class="mt-8 p-4 bg-white border border-gray-200 rounded-lg shadow-sm dark:border-gray-700 dark:bg-slate-700">
                    <h3 class="mb-4 text-lg font-bold text-gray-900 dark:text-white">Aktivitas Pengguna Terbaru</h3>
                    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-100 dark:bg-slate-800 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-4 py-3 text-center">No</th>
                                    <th scope="col" class="px-4 py-3">Pengguna</th>
                                    <th scope="col" class="px-4 py-3">Aksi</th>
                                    <th scope="col" class="px-4 py-3">Modul</th>
                                    <th scope="col" class="px-4 py-3">Deskripsi</th>
                                    <th scope="col" class="px-4 py-3">Waktu</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200 dark:bg-slate-700 dark:divide-gray-600">
                                @forelse($activities ?? [] as $index => $act)
                                    <tr class="bg-white border-b dark:bg-slate-700 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-slate-600">
                                        <td class="px-4 py-3 text-center font-medium text-gray-900 dark:text-white">{{ $index + 1 }}</td>
                                        <td class="px-4 py-3 font-semibold text-gray-900 dark:text-white">{{ $act['user_id'] ?? 'Sistem' }}</td>
                                        <td class="px-4 py-3">
                                            <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2 py-0.5 rounded dark:bg-blue-900 dark:text-blue-300">
                                                {{ $act['action'] ?? 'LOG' }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-xs text-gray-900 dark:text-white">{{ $act['entity'] ?? '-' }}</td>
                                        <td class="px-4 py-3 text-xs text-gray-700 dark:text-white">{{ $act['message'] ?? '-' }}</td>
                                        <td class="px-4 py-3 text-xs text-gray-900 dark:text-white whitespace-nowrap">{{ \Carbon\Carbon::parse($act['timestamp'] ?? now())->diffForHumans() }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">Belum ada catatan aktivitas pengguna.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
