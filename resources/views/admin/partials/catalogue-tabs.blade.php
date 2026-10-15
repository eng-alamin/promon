<nav class="subtabs" aria-label="Catalogue">
    <a href="{{ route('admin.products.index') }}" @class(['on' => request()->routeIs('admin.products.*')]) @if (request()->routeIs('admin.products.*')) aria-current="page" @endif>Products</a>
    <a href="{{ route('admin.categories.index') }}" @class(['on' => request()->routeIs('admin.categories.*')]) @if (request()->routeIs('admin.categories.*')) aria-current="page" @endif>Categories</a>
    <a href="{{ route('admin.brands.index') }}" @class(['on' => request()->routeIs('admin.brands.*')]) @if (request()->routeIs('admin.brands.*')) aria-current="page" @endif>Brands</a>
</nav>
