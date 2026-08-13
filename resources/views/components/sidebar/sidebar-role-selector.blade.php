@php $userRole = Auth::user()->role @endphp

<x-sidebar.sidebar-list href="dashboard" label="Dashboard" icon="heroicon-o-squares-2x2" />

@if ($userRole == 'Admin')
    <x-dropdown-menu title="Products" icon="heroicon-o-document-duplicate" routeName="products.*">
        <x-sidebar.sidebar-menu-dropdown-item-dashboard routeName="products.index" title="Product Management" />
        <x-sidebar.sidebar-menu-dropdown-item-dashboard routeName="categories.index" title="Product Category" />
        <x-sidebar.sidebar-menu-dropdown-item-dashboard routeName="attributes.index" title="Product Attributes" />
    </x-dropdown-menu>
    <x-dropdown-menu title="Stock" icon="heroicon-m-square-3-stack-3d" routeName="transactions.*">
        <x-sidebar.sidebar-menu-dropdown-item-dashboard routeName="transactions.history" title="History Transactions" />
        <x-sidebar.sidebar-menu-dropdown-item-dashboard routeName="stock.opname" title="Stock Opname" />
    </x-dropdown-menu>
    <x-sidebar.sidebar-list href="suppliers.index" label="Supplier" icon="heroicon-m-user-group" />
    <x-sidebar.sidebar-list href="users.index" label="User" icon="heroicon-s-user" />
    <x-sidebar.sidebar-list href="setting.index" label="Setting" icon="heroicon-o-cog-6-tooth" />
@endif

@if ($userRole == 'Manajer Gudang')
    <x-sidebar.sidebar-list href="products.index" label="Products" icon="heroicon-o-document-duplicate" />
    <x-sidebar.sidebar-list href="suppliers.index" label="Supplier" icon="heroicon-m-user-group" />
    <x-dropdown-menu title="Stock" icon="heroicon-m-square-3-stack-3d" routeName="transactions.*">
        <x-sidebar.sidebar-menu-dropdown-item-dashboard routeName="transactions.history" title="History Transactions" />
        <x-sidebar.sidebar-menu-dropdown-item-dashboard routeName="opname" title="Stock Opname" />
    </x-dropdown-menu>
@endif

@if ($userRole == 'Staff Gudang')
    <x-dropdown-menu title="Stock" icon="heroicon-m-square-3-stack-3d" routeName="transactions.*">
        <x-sidebar.sidebar-menu-dropdown-item-dashboard routeName="transactions.index" title="Confirmation Stock" />
    </x-dropdown-menu>
@endif
