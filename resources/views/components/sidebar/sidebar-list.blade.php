@props(['href', 'label', 'icon'])

<li>
    <a href="{{ $href ? route($href) : '#' }}"
        class="flex items-center p-2 text-base text-gray-900 rounded-lg hover:bg-gray-100 group dark:text-white dark:hover:bg-gray-700
          {{ $href && request()->routeIs($href) ? 'bg-gray-200 dark:bg-gray-700' : '' }}">
        <x-dynamic-component :component="$icon"
            class="w-6 h-6 text-gray-500 transition duration-75 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white" />
        <span class="ml-3">{{ $label }}</span>
    </a>
</li>
