@props(['icon' => null, 'routeName' => null, 'title' => null])
<li>
    <a href="{{ route($routeName) }}"
        class="text-base text-gray-900 rounded-lg flex items-center p-2 group hover:bg-gray-100 transition duration-75 pl-11
    {{ request()->routeIs($routeName) ? 'bg-gray-200' : '' }}">
        {{ $title }}
    </a>
</li>
