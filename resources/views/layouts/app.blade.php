<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gray-50 dark:bg-gray-900">
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
            
            {{-- Navigation / Topbar --}}
            @include('layouts.navigation')

            {{-- Sidebar --}}
            <x-sidebar.sidebar />

            {{-- KONTEN UTAMA: Ditambahkan margin kiri (sm:ml-64) dan padding atas (pt-16) --}}
            <div class="p-4 sm:ml-64 pt-20">
                
                {{-- Page Heading (jika ada) --}}
                @if (isset($header))
                    <header class="bg-white dark:bg-gray-800 shadow rounded-lg mb-4">
                        <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endif

                {{-- Page Content --}}
                <main>
                    {{ $slot }}
                </main>

            </div>

        </div>
    </body>
</html>