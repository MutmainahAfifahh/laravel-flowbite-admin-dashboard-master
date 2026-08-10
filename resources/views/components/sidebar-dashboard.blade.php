@php
    // 1. Ambil data setting dari SettingService
    $settingService = app(\App\Services\Setting\SettingService::class);
    $settings = $settingService->getSetting();

    $appLogo = $settings['app_logo'] ?? null;
    $appTitle = $settings['app_title'] ?? 'Stockify';

    // 2. Tentukan URL Logo
    $finalLogo = asset('images/Logo S.png'); // Default Fallback Logo

    if (!empty($appLogo)) {
        if (str_starts_with($appLogo, 'http')) {
            $finalLogo = $appLogo;
        } else {
            // Hilangkan kata 'storage/' atau '/' di awal jika ada, lalu bungkus dengan asset()
            $cleanPath = preg_replace('/^\/?(storage\/)?/', '', $appLogo);
            $finalLogo = asset('storage/' . $cleanPath);
        }
    }

    // 3. Deteksi Route Aktif Secara Otomatis
    $isDashboardActive = $isDashboardActive ?? request()->routeIs('dashboard*');
    $isProductsActive  = $isProductsActive ?? request()->routeIs('products.*', 'categories.*', 'attributes.*');
    $isStockActive     = $isStockActive ?? request()->routeIs('transactions.*', 'stock.*');
    $isSupplierActive  = $isSupplierActive ?? request()->routeIs('suppliers.*');
    $isUserActive      = $isUserActive ?? request()->routeIs('users.*');
    $isSettingsActive  = $isSettingsActive ?? request()->routeIs('setting.*');
@endphp

<aside id="sidebar" class="fixed top-0 left-0 z-20 flex flex-col flex-shrink-0 hidden w-64 h-full pt-16 font-normal duration-75 lg:flex transition-width" aria-label="Sidebar">
  <div class="relative flex flex-col flex-1 min-h-0 pt-0 bg-white border-r border-gray-200 dark:bg-gray-800 dark:border-gray-700">
    <div class="flex flex-col flex-1 pt-5 pb-4 overflow-y-auto">
      <div class="flex-1 px-3 space-y-1 bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
        <ul class="pb-2 space-y-1">

          @auth
            {{-- 1. DASHBOARD --}}
            <li>
              <a href="{{ route('dashboard') }}" class="flex items-center p-2.5 text-base font-semibold rounded-lg transition duration-75 group dark:text-white {{ $isDashboardActive ? 'bg-gray-200 text-gray-900 dark:bg-gray-700' : 'text-gray-900 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                <svg class="w-6 h-6 transition duration-75 dark:text-white {{ $isDashboardActive ? 'text-gray-900' : 'text-gray-600 group-hover:text-gray-900' }}" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                  <path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 02-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                </svg>
                <span class="ml-3 dark:text-white" sidebar-toggle-item>Dashboard</span>
              </a>
            </li>

            {{-- 2. PRODUK (DROPDOWN) --}}
            <li>
              <button type="button" class="flex items-center w-full p-2.5 text-base font-semibold rounded-lg transition duration-75 group dark:text-white {{ $isProductsActive ? 'bg-gray-200 text-gray-900 dark:bg-gray-700' : 'text-gray-900 hover:bg-gray-100 dark:hover:bg-gray-700' }}" aria-controls="dropdown-products" data-collapse-toggle="dropdown-products">
                <svg class="w-6 h-6 transition duration-75 dark:text-white {{ $isProductsActive ? 'text-gray-900' : 'text-gray-600 group-hover:text-gray-900' }}" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                </svg>
                <span class="flex-1 ml-3 text-left whitespace-nowrap dark:text-white" sidebar-toggle-item>Produk</span>
                <svg sidebar-toggle-item class="w-6 h-6 dark:text-white" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                  <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                </svg>
              </button>
              <ul id="dropdown-products" class="py-1 space-y-1 {{ $isProductsActive ? 'block' : 'hidden' }}">
                <li>
                  <a href="{{ route('products.index') }}" class="flex items-center p-2 text-sm text-gray-900 font-medium rounded-lg pl-11 group transition duration-75 dark:text-white dark:hover:bg-gray-700 hover:bg-gray-100 {{ request()->routeIs('products.*') ? 'bg-gray-200 font-bold dark:bg-gray-700' : '' }}">
                    Daftar Produk
                  </a>
                </li>
                <li>
                  <a href="{{ route('categories.index') }}" class="flex items-center p-2 text-sm text-gray-900 font-medium rounded-lg pl-11 group transition duration-75 dark:text-white dark:hover:bg-gray-700 hover:bg-gray-100 {{ request()->routeIs('categories.*') ? 'bg-gray-200 font-bold dark:bg-gray-700' : '' }}">
                    Kategori
                  </a>
                </li>
                <li>
                  <a href="{{ route('attributes.index') }}" class="flex items-center p-2 text-sm text-gray-900 font-medium rounded-lg pl-11 group transition duration-75 dark:text-white dark:hover:bg-gray-700 hover:bg-gray-100 {{ request()->routeIs('attributes.*') ? 'bg-gray-200 font-bold dark:bg-gray-700' : '' }}">
                    Atribut Produk
                  </a>
                </li>
              </ul>
            </li>

            {{-- 3. STOK (DROPDOWN) --}}
            <li>
              <button type="button" class="flex items-center w-full p-2.5 text-base font-semibold rounded-lg transition duration-75 group dark:text-white {{ $isStockActive ? 'bg-gray-200 text-gray-900 dark:bg-gray-700' : 'text-gray-900 hover:bg-gray-100 dark:hover:bg-gray-700' }}" aria-controls="dropdown-stock" data-collapse-toggle="dropdown-stock">
                <svg class="w-6 h-6 transition duration-75 dark:text-white {{ $isStockActive ? 'text-gray-900' : 'text-gray-600 group-hover:text-gray-900' }}" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"></path>
                </svg>
                <span class="flex-1 ml-3 text-left whitespace-nowrap dark:text-white" sidebar-toggle-item>Stok</span>
                <svg sidebar-toggle-item class="w-6 h-6 dark:text-white" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                  <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                </svg>
              </button>
              <ul id="dropdown-stock" class="py-1 space-y-1 {{ $isStockActive ? 'block' : 'hidden' }}">
                <li>
                  <a href="{{ route('transactions.history') }}" class="flex items-center p-2 text-sm text-gray-900 font-medium rounded-lg pl-11 group transition duration-75 dark:text-white dark:hover:bg-gray-700 hover:bg-gray-100 {{ request()->routeIs('transactions.history') ? 'bg-gray-200 font-bold dark:bg-gray-700' : '' }}">
                    Riwayat Transaksi
                  </a>
                </li>
                <li>
                  <a href="{{ route('stock.opname') }}" class="flex items-center p-2 text-sm text-gray-900 font-medium rounded-lg pl-11 group transition duration-75 dark:text-white dark:hover:bg-gray-700 hover:bg-gray-100 {{ request()->routeIs('stock.opname') ? 'bg-gray-200 font-bold dark:bg-gray-700' : '' }}">
                    Stock Opname
                  </a>
                </li>
              </ul>
            </li>

            {{-- 4. SUPPLIER --}}
            <li>
              <a href="{{ route('suppliers.index') }}" class="flex items-center p-2.5 text-base font-semibold rounded-lg transition duration-75 group dark:text-white {{ $isSupplierActive ? 'bg-gray-200 text-gray-900 dark:bg-gray-700' : 'text-gray-900 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                <svg class="w-6 h-6 transition duration-75 dark:text-white {{ $isSupplierActive ? 'text-gray-900' : 'text-gray-600 group-hover:text-gray-900' }}" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                  <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"></path>
                </svg>
                <span class="ml-3 dark:text-white" sidebar-toggle-item>Supplier</span>
              </a>
            </li>

            {{-- 5. USER --}}
            <li>
              <a href="{{ route('users.index') }}" class="flex items-center p-2.5 text-base font-semibold rounded-lg transition duration-75 group dark:text-white {{ $isUserActive ? 'bg-gray-200 text-gray-900 dark:bg-gray-700' : 'text-gray-900 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                <svg class="w-6 h-6 transition duration-75 dark:text-white {{ $isUserActive ? 'text-gray-900' : 'text-gray-600 group-hover:text-gray-900' }}" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                  <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                </svg>
                <span class="ml-3 dark:text-white" sidebar-toggle-item>User</span>
              </a>
            </li>

            {{-- 6. SETTINGS --}}
            <li>
              <a href="{{ route('setting.index') }}" class="flex items-center p-2.5 text-base font-semibold rounded-lg transition duration-75 group dark:text-white {{ $isSettingsActive ? 'bg-gray-200 text-gray-900 dark:bg-gray-700' : 'text-gray-900 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                <svg class="w-6 h-6 transition duration-75 dark:text-white {{ $isSettingsActive ? 'text-gray-900' : 'text-gray-600 group-hover:text-gray-900' }}" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                  <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.532 1.532 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.532 1.532 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"></path>
                </svg>
                <span class="ml-3 dark:text-white" sidebar-toggle-item>Settings</span>
              </a>
            </li>

          @endauth

        </ul>
      </div>
    </div>
  </div>
</aside>

<div class="fixed inset-0 z-10 hidden bg-gray-900/50" id="sidebarBackdrop"></div>