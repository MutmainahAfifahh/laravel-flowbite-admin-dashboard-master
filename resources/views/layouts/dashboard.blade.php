<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="#">
    <meta name="author" content="#">
    <meta name="generator" content="Laravel">

    <title>Dashboard</title>
    @vite(['resources/css/app.css','resources/js/app.js'])
    <link rel="canonical" href="{{ request()->fullUrl() }}">

    @if(isset($page->params['robots']))
        <meta name="robots" content="{{ $page->params['robots'] }}">
    @endif

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="icon" type="image/png" href="/favicon.ico">
    <link rel="manifest" href="/site.webmanifest">
    <link rel="mask-icon" href="/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="theme-color" content="#ffffff">

    <script>
        // On page load or when changing themes, best to add inline in `head` to avoid FOUC
        if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark')
        }
    </script>
</head>

<body class="bg-gray-50 dark:bg-gray-900">
    
    {{-- Topbar / Navbar --}}
    @include('example.layouts.partials.navbar-dashboard')

    <div class="flex pt-16 overflow-hidden bg-gray-50 dark:bg-gray-900">
        
        {{-- Tempat memanggil Sidebar --}}
        <x-sidebar.sidebar />

        <div id="sidebarBackdrop" class="fixed inset-0 z-10 hidden bg-gray-900/50 dark:bg-gray-900/90"></div>

        {{-- Container Utama Konten --}}
        <div id="main-content" class="relative w-full h-full overflow-y-auto bg-gray-50 lg:ml-64 dark:bg-gray-900 min-h-screen">
            <main class="p-4 pt-6">
                {{-- Jika pakai @extends / @section('content') --}}
                @yield('content')

                {{-- Jika pakai Blade Component $slot --}}
                {{ $slot ?? '' }}
            </main>
        </div>

    </div>

    <script async defer src="https://buttons.github.io/buttons.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.6.2/datepicker.min.js"></script>

    {{-- Global Script: Minimum Stock Validation --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // 🟢 1. Cek Role Pengguna & Halaman Saat Ini
            const userRole = "{{ strtolower(auth()->user()->role ?? '') }}";
            const isAdminOrManager = userRole.includes('admin') || userRole.includes('manajer') || userRole.includes('manager');
            const isOpnamePage = window.location.pathname.includes('opname') || window.location.pathname.includes('stock-opname');

            // 🟢 2. Jika Halaman Stock Opname DIBUKA oleh Admin/Manajer -> Nonaktifkan Validasi
            // (Staff Gudang TETAP mendapatkan pop-up validasi batas minimum)
            if (isOpnamePage && isAdminOrManager) {
                return;
            }

            let currentMin = 0; // Set default awal 0 agar tidak mengunci form sebelum fetch API selesai

            function getQtyInputs() {
                return document.querySelectorAll('input[type="number"][name^="quantity"], form[action*="products"] input[type="number"][name="minimum_stock"]');
            }

            function applyMinToInputs(inputs) {
                inputs.forEach(input => {
                    input.min = currentMin;
                    validateInput(input);
                });
            }

            function validateInput(input) {
                if (!input.value || currentMin === 0) return;
                const val = parseInt(input.value);
                const form = input.closest('form');
                const submitBtn = form ? form.querySelector('button[type="submit"]') : null;
                
                // Pengecualian untuk form Transaksi Keluar (Outbound)
                const isOutbound = form && form.querySelector('input[name="type"][value="Keluar"]');
                if (isOutbound) {
                    // Untuk form keluar, biarkan backend yang memvalidasi sisa stok.
                    // Qty keluar boleh berapapun (misal 1 pcs).
                    input.min = 1;
                    if (submitBtn) submitBtn.disabled = false;
                    input.setCustomValidity("");
                    return;
                }
                
                if (val < currentMin) {
                    if (submitBtn) submitBtn.disabled = true;
                    input.setCustomValidity("Jumlah tidak boleh kurang dari batas minimum (" + currentMin + ")");
                    input.reportValidity();
                } else {
                    if (submitBtn) submitBtn.disabled = false;
                    input.setCustomValidity("");
                }
            }

            // Fetch Real-time Stok Minimum dari API
            function checkMinStock() {
                const qtyInputs = getQtyInputs();
                if (qtyInputs.length === 0) return;

                fetch('/api/stock/minimum')
                    .then(res => res.json())
                    .then(data => {
                        if (data.minimum_stock && data.minimum_stock !== currentMin) {
                            currentMin = data.minimum_stock;
                            applyMinToInputs(qtyInputs);
                        }
                    })
                    .catch(err => console.error("Error fetching min stock:", err));
            }

            // Validasi Realtime saat Mengetik
            document.body.addEventListener('input', function(e) {
                if (e.target.tagName === 'INPUT' && e.target.type === 'number') {
                    if (e.target.name.startsWith('quantity') || (e.target.name === 'minimum_stock' && e.target.closest('form[action*="products"]'))) {
                        validateInput(e.target);
                    }
                }
            });

            // Jalankan Pengecekan Awal
            const initialInputs = getQtyInputs();
            if (initialInputs.length > 0) {
                checkMinStock(); // Ambil nilai stok minimum asli terlebih dahulu
                setInterval(checkMinStock, 30000); // Polling tiap 30 detik
            }
        });
    </script>
</body>
</html>