@extends('layouts.dashboard')

@section('content')
<div class="p-4 sm:p-6 bg-gray-50 dark:bg-gray-800 min-h-screen">

    {{-- Page Header --}}
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Dashboard Admin</h1>

    {{-- 4 Stat Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5 mb-6">

        {{-- Jumlah Produk --}}
        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-5 shadow-sm">
            <div class="flex items-start justify-between mb-4">
                <p class="text-blue-600 dark:text-blue-400 font-bold text-base">Jumlah Produk</p>
                <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
            </div>
            <p class="text-4xl font-extrabold text-gray-900 dark:text-white mb-2">{{ $totalProducts ?? 0 }}</p>
            <p class="text-sm font-medium text-gray-600 dark:text-gray-300">Total produk dalam inventaris</p>
        </div>

        {{-- Total Stok Rendah --}}
        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-5 shadow-sm">
            <div class="flex items-start justify-between mb-4">
                <p class="text-red-600 dark:text-red-400 font-bold text-base">Total Stok Rendah</p>
                <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            <p class="text-4xl font-extrabold text-red-600 dark:text-red-400 mb-2">{{ $totalLowStock ?? 0 }}</p>
            <p class="text-sm font-medium text-gray-600 dark:text-gray-300">Produk perlu diisi ulang</p>
        </div>

        {{-- Transaksi Masuk --}}
        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-5 shadow-sm">
            <div class="flex items-start justify-between mb-4">
                <p class="text-yellow-600 dark:text-yellow-400 font-bold text-base">Transaksi Masuk</p>
                <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 4h13M3 8h9m-9 4h9m5 4v-12m0 0l-4 4m4-4l4 4"/>
                </svg>
            </div>
            <p class="text-4xl font-extrabold text-yellow-600 dark:text-yellow-400 mb-2">{{ $incomingTransaction ?? 0 }}</p>
            <p class="text-sm font-medium text-gray-600 dark:text-gray-300">Dalam 30 hari terakhir</p>
        </div>

        {{-- Transaksi Keluar --}}
        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-5 shadow-sm">
            <div class="flex items-start justify-between mb-4">
                <p class="text-amber-700 dark:text-amber-400 font-bold text-base">Transaksi Keluar</p>
                <svg class="w-6 h-6 text-amber-700 dark:text-amber-400" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 4h13M3 8h9m-9 4h9m5-4v12m0 0l-4-4m4 4l4-4"/>
                </svg>
            </div>
            <p class="text-4xl font-extrabold text-amber-700 dark:text-amber-400 mb-2">{{ $outgoingTransaction ?? 0 }}</p>
            <p class="text-sm font-medium text-gray-600 dark:text-gray-300">Dalam 30 hari terakhir</p>
        </div>

    </div>

    {{-- Chart Section --}}
    <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-6 mb-6 shadow-sm">
        <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-1">Grafik Stok Barang</h2>
        <p class="text-sm font-medium text-gray-600 dark:text-gray-300 mb-5">Jumlah quantity transaksi masuk & keluar per bulan (6 bulan terakhir)</p>
        <div class="relative h-72">
            <canvas id="stockChart"></canvas>
        </div>
    </div>

    <!-- Aktivitas Pengguna Terbaru -->
    <div class="mt-6 mb-8 p-4 bg-white border border-gray-200 rounded-lg shadow-sm dark:border-gray-700 dark:bg-gray-800">
        <h3 class="mb-4 text-lg font-bold text-gray-900 dark:text-white">Aktivitas Pengguna Terbaru</h3>
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
                    @forelse($activities ?? [] as $index => $act)
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
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

{{-- Chart.js CDN --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const canvas = document.getElementById('stockChart');
        if (!canvas) return;

        const ctx = canvas.getContext('2d');
        const labels = @json($chartLabels ?? []);
        const masukData  = @json($chartMasuk ?? []);
        const keluarData = @json($chartKeluar ?? []);

        const isDark = document.documentElement.classList.contains('dark');
        const textColor = isDark ? '#f3f4f6' : '#4b5563';
        const gridColor = isDark ? 'rgba(255, 255, 255, 0.1)' : 'rgba(107, 114, 128, 0.15)';

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Stock Barang Masuk',
                        data: masukData,
                        borderColor: '#38bdf8',
                        backgroundColor: 'rgba(56, 189, 248, 0.15)',
                        borderWidth: 2.5,
                        pointBackgroundColor: '#38bdf8',
                        pointHoverRadius: 6,
                        pointRadius: 4,
                        tension: 0.35,
                        fill: true,
                    },
                    {
                        label: 'Stock Barang Keluar',
                        data: keluarData,
                        borderColor: '#f87171',
                        backgroundColor: 'rgba(248, 113, 113, 0.15)',
                        borderWidth: 2.5,
                        pointBackgroundColor: '#f87171',
                        pointHoverRadius: 6,
                        pointRadius: 4,
                        tension: 0.35,
                        fill: true,
                    },
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: { mode: 'index', intersect: false },
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            color: textColor,
                            usePointStyle: true,
                            pointStyleWidth: 10,
                            font: { size: 12, weight: '600' }
                        }
                    },
                    tooltip: {
                        padding: 10,
                        backgroundColor: isDark ? '#1f2937' : '#ffffff',
                        titleColor: isDark ? '#ffffff' : '#111827',
                        bodyColor: isDark ? '#d1d5db' : '#374151',
                        borderColor: isDark ? '#374151' : '#e5e7eb',
                        borderWidth: 1,
                    }
                },
                scales: {
                    x: {
                        grid: { color: gridColor },
                        ticks: { color: textColor, font: { size: 11, weight: '500' } }
                    },
                    y: {
                        beginAtZero: true,
                        grid: { color: gridColor },
                        ticks: { 
                            color: textColor, 
                            font: { size: 11, weight: '500' },
                            stepSize: 1,
                            precision: 0
                        }
                    }
                }
            }
        });
    });
</script>
@endsection