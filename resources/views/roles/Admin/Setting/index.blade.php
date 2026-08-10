@extends('layouts.dashboard')

@section('content')
<div class="p-4 bg-white block border-b border-gray-200 lg:mt-1.5 dark:bg-gray-800 dark:border-gray-700 min-h-screen">
    <div class="w-full mb-4">
        <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl dark:text-white mb-4">{{ $title ?? 'Pengaturan Aplikasi' }}</h1>

        {{-- Alert Sukses --}}
        @if(session('success'))
            <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400 border border-green-200 dark:border-green-800" role="alert">
                {{ session('success') }}
            </div>
        @endif

        {{-- Alert Error Validation --}}
        @if($errors->any())
            <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400 border border-red-200 dark:border-red-800" role="alert">
                <ul class="list-disc pl-5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('setting.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2 max-w-4xl">
                
                <!-- Logo Aplikasi -->
                <div class="p-5 bg-gray-50 rounded-lg dark:bg-gray-700 border border-gray-200 dark:border-gray-600">
                    <h3 class="mb-3 text-lg font-bold text-gray-900 dark:text-white">Logo Aplikasi</h3>
                    
                    @if (!empty($setting['app_logo']))
                        <div class="mb-4">
                            <p class="text-xs text-gray-500 dark:text-gray-300 mb-2">Logo Saat Ini:</p>
                            {{-- Menggunakan asset() dengan membersihkan slash awal agar path tidak bentrok --}}
                            <img src="{{ asset(ltrim($setting['app_logo'], '/')) }}" alt="Logo Aplikasi" class="p-2 border border-gray-300 rounded-lg w-28 h-28 object-contain bg-white shadow-sm">
                        </div>
                    @endif

                    <div class="mb-3 text-xs text-gray-500 dark:text-gray-300">
                        Format gambar: JPG, PNG, atau SVG (Maks. 2MB)
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Pilih File Logo Baru</label>
                        <input type="file" name="app_logo" accept="image/*" class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-white focus:outline-none dark:bg-gray-800 dark:border-gray-600">
                    </div>
                </div>

                <!-- Nama Aplikasi -->
                <div class="p-5 bg-gray-50 rounded-lg dark:bg-gray-700 border border-gray-200 dark:border-gray-600 flex flex-col justify-between">
                    <div>
                        <h3 class="mb-3 text-lg font-bold text-gray-900 dark:text-white">Nama Aplikasi</h3>
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Judul / Nama Sistem</label>
                            <input type="text" name="app_title" value="{{ old('app_title', $setting['app_title'] ?? '') }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-800 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" placeholder="Contoh: Stockify - Management System" required>
                        </div>
                    </div>
                    <div class="pt-4 mt-6 border-t border-gray-200 dark:border-gray-600">
                        <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800 transition">
                            Simpan Pengaturan
                        </button>
                    </div>
                </div>

            </div>
        </form>
    </div>
</div>
@endsection