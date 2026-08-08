@php
    $url = explode('/', request()->url());
    $page_slug = count($url) > 1 ? $url[count($url) - 2] : '';
    $userRole = auth()->check() ? str_replace(' ', '_', strtolower(trim(auth()->user()->role ?? ''))) : '';
@endphp

<aside id="sidebar" class="fixed top-0 left-0 z-20 flex flex-col flex-shrink-0 hidden w-64 h-full pt-16 font-normal duration-75 lg:flex transition-width" aria-label="Sidebar">
  <div class="relative flex flex-col flex-1 min-h-0 pt-0 bg-white border-r border-gray-200 dark:bg-gray-800 dark:border-gray-700">
    <div class="flex flex-col flex-1 pt-5 pb-4 overflow-y-auto">
      <div class="flex-1 px-3 space-y-1 bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
        <ul class="pb-2 space-y-2">

          @auth
              {{-- 1. MENU UNTUK ADMIN --}}
              @if($userRole === 'admin')
                  <li>
                      <a href="{{ route('categories.index') }}" class="flex items-center p-2 text-base font-normal text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700">
                          <span class="ml-3">Kategori</span>
                      </a>
                  </li>
                  <li>
                      <a href="{{ route('suppliers.index') }}" class="flex items-center p-2 text-base font-normal text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700">
                          <span class="ml-3">Supplier</span>
                      </a>
                  </li>
                  <li>
                      <a href="{{ route('products.index') }}" class="flex items-center p-2 text-base font-normal text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700">
                          <span class="ml-3">Produk</span>
                      </a>
                  </li>
                  <li>
                      <a href="{{ route('stock-transactions.index') }}" class="flex items-center p-2 text-base font-normal text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700">
                          <span class="ml-3">Transaksi Stok</span>
                      </a>
                  </li>

              {{-- 2. MENU UNTUK MANAJER GUDANG --}}
              @elseif($userRole === 'manajer_gudang')
                <li>
                      <a href="{{ route('products.index') }}" class="flex items-center p-2 text-base font-normal text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700">
                          <span class="ml-3">Produk</span>
                      </a>
                  </li>
                  <li>
                      <a href="{{ route('suppliers.index') }}" class="flex items-center p-2 text-base font-normal text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700">
                          <span class="ml-3">Daftar Supplier</span>
                      </a>
                  </li>
                  <li>
                      <a href="{{ route('stock-transactions.index') }}" class="flex items-center p-2 text-base font-normal text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700">
                          <span class="ml-3">Transaksi Stok</span>
                      </a>
                  </li>

              {{-- 3. MENU UNTUK STAFF GUDANG --}}
              @elseif($userRole === 'staff_gudang')
                  <li>
                      <a href="{{ route('stock-transactions.index') }}" class="flex items-center p-2 text-base font-normal text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700">
                          <span class="ml-3">Transaksi Stok</span>
                      </a>
                  </li>
                  <li>
                      <a href="{{ route('products.index') }}" class="flex items-center p-2 text-base font-normal text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700">
                          <span class="ml-3">Daftar Produk</span>
                      </a>
                  </li>
              @endif
          @endauth

        </ul>
      </div>
    </div>
  </div>
</aside>

<div class="fixed inset-0 z-10 hidden bg-gray-900/50" id="sidebarBackdrop"></div>