@extends('layouts.dashboard')
@section('content')
    <div class="px-4 pt-6">
        <h1 class="text-2xl font-medium dark:text-white text-slate-700">{{ $title }}</h1>

        <section>
            <div class="p-4 my-5 bg-white border border-gray-200 rounded-lg shadow-sm dark:border-gray-700 sm:p-6 dark:bg-gray-800">
                <form action="{{ route('categories.update', $category->id) }}" method="POST">
                    @csrf @method('PUT')
                    <div class="mb-5">
                        <label for="name" class="after:content-['*'] after:text-red-600 after:ml-1 block mb-2 text-sm font-medium text-gray-900 dark:text-white">Name Category</label>
                        <input type="text" id="name" name="name" value="{{ $category->name }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 placeholder:italic" required />
                    </div>
                    <div class="mb-5">
                        <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Description</label>
                        <textarea name="description" id="description" rows="5" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 placeholder:italic">{{ trim($category->description) }}</textarea>
                    </div>
                    <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 mt-5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Update Category</button>
                </form>
            </div>
        </section>
    </div>
@endsection
