    @props(['title', 'icon' => null, 'routeName' => null])

    <li>
        <button type="button"
            class="flex items-center w-full p-2 text-base text-gray-900 transition duration-75 rounded-lg group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700"
            aria-controls="{{ $routeName }}" 
            data-collapse-toggle="{{ $routeName }}">
            <x-dynamic-component 
                :component="$icon"
                class="w-6 h-6 text-gray-500 transition duration-75 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white" />
            <span class="flex-1 ml-3 text-left whitespace-nowrap">
                {{ $title }}
            </span>
            <x-heroicon-c-chevron-down 
                class="w-6 h-6 text-gray-500 transition-transform duration-300
                {{ request()->routeIs($routeName) ? 'rotate-180' : '' }}" />
        </button>
        <ul 
            id="{{ $routeName }}" 
            class="py-2 space-y-2 {{ request()->routeIs($routeName) ? 'block' : 'hidden' }}">
            {{ $slot }}
        </ul>
    </li>
