@props(['icon' => null, 'routeName' => null, 'title' => null])
<li>
    <a href="{{ route($routeName) }}"
        class="text-base text-gray-900 dark:text-white rounded-lg flex items-center p-2 group hover:bg-gray-100 dark:hover:bg-gray-700 transition duration-75 pl-11
    {{ request()->routeIs($routeName) ? 'bg-gray-200 dark:bg-gray-700' : '' }}">
        {{ $title }}
    </a>
</li>
