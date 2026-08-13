@props(['title' => '', 'description' => '', 'header' => '', 'pagination' => '', 'additional' => ''])

<div class="p-4 my-5 bg-white border border-gray-200 rounded-lg shadow-sm dark:border-gray-700 sm:p-6 dark:bg-gray-800">
    <div class="items-center justify-between lg:flex">
        <div class="mb-4 lg:mb-0">
            <h3 class="mb-2 text-xl font-bold text-gray-900 dark:text-white">
                {{ $title }}
            </h3>
            <span class="text-base font-normal text-gray-500 dark:text-gray-400">
                {{ $description }}
            </span>
        </div>
        <div>
            {{ $additional }}
        </div>
    </div>
    <div class="flex flex-col mt-6">
        <div class="overflow-x-auto rounded-lg">
            <div class="inline-block min-w-full align-middle">
                <div class="overflow-hidden shadow sm:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-600">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                {{ $header }}
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800">
                            {{ $slot }}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="pt-3 sm:pt-6">
        {{ $pagination }}
    </div>
</div>