@extends('layouts.dashboard')

@section('content')
<div class="p-4 bg-white block border-b border-gray-200 lg:mt-1.5 dark:bg-gray-800 dark:border-gray-700">
    <div class="w-full mb-4">
        <div class="flex items-center justify-between mb-4">
            <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl dark:text-white">Laporan Aktivitas Pengguna</h1>
            <button onclick="window.print()" class="text-white bg-blue-700 hover:bg-blue-800 font-medium rounded-lg text-xs px-4 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700">
                🖨️ Cetak Laporan
            </button>
        </div>

        <!-- Tabel Laporan Aktivitas -->
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-4 py-3 text-center">No</th>
                        <th scope="col" class="px-4 py-3">Pengguna</th>
                        <th scope="col" class="px-4 py-3">Aksi</th>
                        <th scope="col" class="px-4 py-3">Modul</th>
                        <th scope="col" class="px-4 py-3">Deskripsi</th>
                        <th scope="col" class="px-4 py-3">Waktu</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                    @forelse($activities as $index => $act)
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                            <td class="px-4 py-3 text-center font-medium text-gray-900 dark:text-white">{{ $index + 1 }}</td>
                            <td class="px-4 py-3 font-semibold text-gray-900 dark:text-white">{{ $act->user->name ?? 'Sistem' }}</td>
                            <td class="px-4 py-3">
                                <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded dark:bg-blue-900 dark:text-blue-300">
                                    {{ $act->action ?? 'LOG' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-xs">{{ $act->model_type ?? '-' }}</td>
                            <td class="px-4 py-3 text-xs text-gray-700 dark:text-gray-300">{{ $act->description ?? '-' }}</td>
                            <td class="px-4 py-3 text-xs whitespace-nowrap">{{ \Carbon\Carbon::parse($act->created_at)->translatedFormat('d F Y H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">Belum ada riwayat laporan aktivitas pengguna.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
