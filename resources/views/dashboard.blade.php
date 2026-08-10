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
            <p class="text-4xl font-extrabold text-gray-900 dark:text-white mb-2">{{ $totalProducts }}</p>
            <p class="text-sm font-medium text-gray-600 dark:text-gray-300">Total produk dalam inventaris</p>
        </div>

        {{-- Total Stok Rendah --}}
        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-5 shadow-sm">
            <div class="flex items-start justify-between mb-4">
                <p class="text-amber-600 dark:text-amber-400 font-bold text-base">Total Stok Rendah</p>
                <svg class="w-6 h-6 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            <p class="text-4xl font-extrabold text-amber-600 dark:text-amber-400 mb-2">{{ $stokRendah }}</p>
            <p class="text-sm font-medium text-gray-600 dark:text-gray-300">Produk perlu diisi ulang</p>
        </div>

        {{-- Transaksi Masuk --}}
        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-5 shadow-sm">
            <div class="flex items-start justify-between mb-4">
                <p class="text-emerald-600 dark:text-emerald-400 font-bold text-base">Transaksi Masuk</p>
                <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12"/>
                </svg>
            </div>
            <p class="text-4xl font-extrabold text-emerald-600 dark:text-emerald-400 mb-2">{{ $masuk30 }}</p>
            <p class="text-sm font-medium text-gray-600 dark:text-gray-300">Dalam 30 hari terakhir</p>
        </div>

        {{-- Transaksi Keluar --}}
        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-5 shadow-sm">
            <div class="flex items-start justify-between mb-4">
                <p class="text-rose-600 dark:text-rose-400 font-bold text-base">Transaksi Keluar</p>
                <svg class="w-6 h-6 text-rose-600 dark:text-rose-400" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 4h13M3 8h9m-9 4h9m5-4v12m0 0l-4-4m4 4l4-4"/>
                </svg>
            </div>
            <p class="text-4xl font-extrabold text-rose-600 dark:text-rose-400 mb-2">{{ $keluar30 }}</p>
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

    {{-- Tabel Transaksi Terbaru --}}
    <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl overflow-hidden shadow-sm">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
            <div>
                <h2 class="text-base font-bold text-gray-900 dark:text-white">Transaksi Stok Terbaru</h2>
                <p class="text-xs font-medium text-gray-600 dark:text-gray-300 mt-0.5">5 transaksi terakhir yang dicatat</p>
            </div>
            <a href="{{ route('transactions.history') }}" class="text-sm font-bold text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 hover:underline">
                Lihat semua &rarr;
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-800 dark:text-white">
                <thead class="text-xs font-bold uppercase text-gray-900 dark:text-white bg-gray-100 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
                    <tr>
                        <th class="px-6 py-4">Tanggal</th>
                        <th class="px-6 py-4">Produk</th>
                        <th class="px-6 py-4">Tipe</th>
                        <th class="px-6 py-4">Qty</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Pencatat</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($transaksiTerbaru as $tx)
                    <tr class="bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-75">
                        <td class="px-6 py-5 font-medium text-gray-900 dark:text-white whitespace-nowrap">
                            {{ \Carbon\Carbon::parse($tx->date)->format('d M Y') }}
                        </td>
                        <td class="px-6 py-5 font-bold text-gray-900 dark:text-white whitespace-nowrap">
                            {{ $tx->product->name ?? '-' }}
                        </td>
                        <td class="px-6 py-5 whitespace-nowrap">
                            @if($tx->type == 'Masuk')
                                <span class="px-2.5 py-1 text-xs font-semibold rounded-md border bg-green-50 text-green-700 border-green-200 dark:bg-green-950/40 dark:text-green-400 dark:border-green-800">Masuk</span>
                            @else
                                <span class="px-2.5 py-1 text-xs font-semibold rounded-md border bg-gray-100 text-gray-800 border-gray-300 dark:bg-gray-700 dark:text-white dark:border-gray-600">Keluar</span>
                            @endif
                        </td>
                        <td class="px-6 py-5 font-bold text-gray-900 dark:text-white whitespace-nowrap">
                            {{ $tx->quantity }}
                        </td>
                        <td class="px-6 py-5 whitespace-nowrap">
                            @if($tx->status == 'Pending')
                                <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-amber-100 text-amber-800 dark:bg-amber-900/50 dark:text-amber-400">Pending</span>
                            @elseif(in_array($tx->status, ['Completed', 'Diterima', 'Dikeluarkan']))
                                <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-emerald-100 text-emerald-800 dark:bg-emerald-900/50 dark:text-emerald-400">{{ $tx->status }}</span>
                            @else
                                <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-gray-200 text-gray-800 dark:bg-gray-700 dark:text-white">{{ $tx->status }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-5 font-medium text-gray-800 dark:text-white whitespace-nowrap">
                            {{ $tx->user->name ?? 'Admin Stockify' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center font-medium text-gray-500 dark:text-gray-400">
                            Belum ada data transaksi stok.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

{{-- Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    const ctx = document.getElementById('stockChart').getContext('2d');
    const labels = @json($chartLabels);
    const masukData  = @json($chartMasuk);
    const keluarData = @json($chartKeluar);

    const isDark = document.documentElement.classList.contains('dark');
    const textColor = isDark ? '#ffffff' : '#64748b';
    const gridColor = isDark ? 'rgba(255, 255, 255, 0.1)' : 'rgba(148, 163, 184, 0.15)';

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
                    pointRadius: 4,
                    tension: 0.4,
                    fill: true,
                },
                {
                    label: 'Stock Barang Keluar',
                    data: keluarData,
                    borderColor: '#f87171',
                    backgroundColor: 'rgba(248, 113, 113, 0.15)',
                    borderWidth: 2.5,
                    pointBackgroundColor: '#f87171',
                    pointRadius: 4,
                    tension: 0.4,
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
                        font: { size: 12, weight: 'bold' }
                    }
                },
                title: {
                    display: true,
                    text: 'Product Stock Transaction (Last 6 Months)',
                    color: textColor,
                    font: { size: 13, weight: 'bold' },
                    padding: { bottom: 16 }
                }
            },
            scales: {
                x: {
                    grid: { color: gridColor },
                    ticks: { color: textColor, font: { size: 11, weight: '600' } }
                },
                y: {
                    beginAtZero: true,
                    grid: { color: gridColor },
                    ticks: { color: textColor, font: { size: 11, weight: '600' } }
                }
            }
        }
    });
</script>
@endsection