<x-sidebar-dashboard>
    <x-sidebar-menu-dashboard routeName="categories.index" title="Kategori"/>
    <x-sidebar-menu-dashboard routeName="suppliers.index" title="Supplier"/>
    <x-sidebar-menu-dashboard routeName="products.index" title="Produk"/>
    <x-sidebar-menu-dashboard routeName="stock-transactions.index" title="Transaksi Stok"/>
    <x-sidebar-menu-dashboard routeName="index-practice" title="Practice Index"/>
    <x-sidebar-menu-dropdown-dashboard routeName="practice.*" title="Practice Menu">
        <x-sidebar-menu-dropdown-item-dashboard routeName="practice.first" title="Judul Item1"/>
        <x-sidebar-menu-dropdown-item-dashboard routeName="practice.second" title="Judul Item2"/>
    </x-sidebar-menu-dropdown-dashboard>
</x-sidebar-dashboard>

