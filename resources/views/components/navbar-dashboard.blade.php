@php
    // 1. Ambil data setting dari SettingService bawaan project
    $settingService = app(\App\Services\Setting\SettingService::class);
    $settings = $settingService->getSetting();

    $appLogo = $settings['app_logo'] ?? null;
    $appTitle = $settings['app_title'] ?? 'Stockify';

    // 2. Normalisasi URL Gambar Logo agar tidak 404 / Pecah
    $finalLogo = asset('images/Logo S.png'); // Default Fallback Logo

    if (!empty($appLogo)) {
        if (str_starts_with($appLogo, 'http://') || str_starts_with($appLogo, 'https://')) {
            $finalLogo = $appLogo;
        } else {
            // Bersihkan slash ganda di awal jika ada
            $cleanPath = ltrim($appLogo, '/');
            $finalLogo = asset($cleanPath);
        }
    }
@endphp

<nav class="fixed z-50 w-full bg-white border-b border-gray-200 dark:bg-gray-800 dark:border-gray-700">
    <div class="px-3 py-3 lg:px-5 lg:pl-3">
        <div class="flex items-center justify-between">
            <div class="flex items-center justify-start">
                <!-- Toggle Sidebar Mobile -->
                <button id="toggleSidebarMobile" aria-expanded="true" aria-controls="sidebar" class="p-2 text-gray-600 rounded cursor-pointer lg:hidden hover:text-gray-900 hover:bg-gray-100 focus:bg-gray-100 dark:focus:bg-gray-700 focus:ring-2 focus:ring-gray-100 dark:focus:ring-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
                    <svg id="toggleSidebarMobileHamburger" class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h6a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                    </svg>
                    <svg id="toggleSidebarMobileClose" class="hidden w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </button>
          
                <!-- Brand / Logo App Dinamis -->
                <a href="{{ url('/') }}" class="flex ml-2 md:mr-24 items-center">
                    <!-- Logo Aplikasi Dinamis -->
                    <img src="{{ $finalLogo }}" class="h-10 mr-3 object-contain" alt="Logo Aplikasi" />
            
                    <!-- Nama Aplikasi Dinamis -->
                    <span class="self-center text-xl font-bold sm:text-2xl whitespace-nowrap text-gray-900 dark:text-white">
                        {{ $appTitle }}
                    </span>
                </a>
            </div>

            <!-- Bagian Kanan Navbar (Theme Toggle & User Profile) -->
            <div class="flex items-center">
                <!-- Theme Toggle -->
                <button id="theme-toggle" data-tooltip-target="tooltip-toggle" type="button" class="text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 rounded-lg text-sm p-2.5">
                    <svg id="theme-toggle-dark-icon" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path></svg>
                    <svg id="theme-toggle-light-icon" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" fill-rule="evenodd" clip-rule="evenodd"></path></svg>
                </button>
                <div id="tooltip-toggle" role="tooltip" class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip">
                    Toggle dark mode
                    <div class="tooltip-arrow" data-popper-arrow></div>
                </div>

                <!-- Profile Dropdown -->
                <div class="flex items-center ml-3">
                    <div>
                        <button type="button" class="flex text-sm bg-gray-800 rounded-full focus:ring-4 focus:ring-gray-300 dark:focus:ring-gray-600" id="user-menu-button-2" aria-expanded="false" data-dropdown-toggle="dropdown-2">
                            <span class="sr-only">Open user menu</span>
                            <div class="w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-bold text-sm shadow">
                                {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                            </div>
                        </button>
                    </div>

                    <!-- Dropdown Menu -->
                    <div class="z-50 hidden my-4 text-base list-none bg-white divide-y divide-gray-100 rounded shadow-lg dark:bg-gray-700 dark:divide-gray-600 border border-gray-100 dark:border-gray-600" id="dropdown-2">
                        <div class="px-4 py-3" role="none">
                            <p class="text-sm font-bold text-gray-900 dark:text-white" role="none">
                                {{ auth()->user()->name ?? 'Pengguna' }}
                            </p>
                            <p class="text-sm font-medium text-gray-600 truncate dark:text-gray-300" role="none">
                                {{ auth()->user()->email ?? '' }}
                            </p>
                            <span class="inline-block px-2.5 py-0.5 mt-1.5 text-[11px] font-semibold rounded bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                {{ auth()->user()->role ?? 'User' }}
                            </span>
                        </div>
                        <ul class="py-1" role="none">
                            <li>
                                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm font-medium text-gray-800 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-600 dark:hover:text-white" role="menuitem">Profil Saya</a>
                            </li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left block px-4 py-2 text-sm text-red-600 hover:bg-gray-100 dark:text-red-400 dark:hover:bg-gray-600 dark:hover:text-white" role="menuitem">
                                        Keluar (Logout)
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>