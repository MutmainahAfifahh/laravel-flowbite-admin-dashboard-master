@extends('example.layouts.default.baseof')

@section('main')
<div class="flex flex-col items-center justify-center min-h-screen px-6 py-8 mx-auto bg-gray-50 dark:bg-gray-900">
    <a href="{{ url('/') }}" class="flex items-center justify-center mb-8 text-2xl font-bold lg:mb-10 text-gray-900 dark:text-white">
        <img src="{{ asset('images/Logo S.png') }}" class="mr-3 h-11" alt="Stockify Logo">
        <span>Stockify</span>
    </a>
    
    <!-- Card Login -->
    <div class="w-full max-w-xl p-6 space-y-8 sm:p-8 bg-white rounded-lg shadow-md dark:bg-gray-800 dark:border dark:border-gray-700">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
            Masuk ke Akun Anda
        </h2>

        <!-- Alert Error -->
        @if ($errors->any())
            <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400" role="alert">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form class="mt-8 space-y-6" action="{{ route('login') }}" method="POST">
            @csrf
            <div>
                <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Alamat Email</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" placeholder="admin@stockify.com" required>
            </div>
            
            <div>
                <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Password</label>
                <input type="password" name="password" id="password" placeholder="••••••••" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" required>
            </div>

            <div class="flex items-start">
                <div class="flex items-center h-5">
                    <input id="remember" name="remember" type="checkbox" class="w-4 h-4 border-gray-300 rounded bg-gray-50 focus:ring-3 focus:ring-blue-300 dark:bg-gray-700 dark:border-gray-600">
                </div>
                <div class="ml-3 text-sm">
                    <label for="remember" class="font-medium text-gray-900 dark:text-white">Ingat Saya</label>
                </div>
            </div>

            <!-- Tombol Masuk -->
            <button type="submit" class="w-full px-5 py-3 text-base font-semibold text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none transition-colors cursor-pointer">
                Masuk
            </button>
        </form>
    </div>
</div>
@endsection
