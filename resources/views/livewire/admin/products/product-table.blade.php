<div>
    <div class="tools">
        <input class="f" type="search" wire:model.live.debounce.300ms="search" placeholder="Search name, SKU or slug..." aria-label="Search products">
        <select class="f" wire:model.live="categoryFilter" aria-label="Filter by category">
            <option value="">All categories</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}">{{ $category->name }}</option>
            @endforeach
        </select>
        <select class="f" wire:model.live="statusFilter" aria-label="Filter by stock status">
            <option value="">All stock statuses</option>
            @foreach ($statuses as $status)
                <option value="{{ $status->value }}">{{ $status->label() }}</option>
            @endforeach
        </select>
        <a class="btn pri" href="{{ route('admin.products.create') }}"><i class="bi bi-plus-lg" aria-hidden="true"></i> Add product</a>
    </div>

    <div class="card">
        @if ($products->isEmpty())
            <div class="empty">
                <div class="empty-ic" aria-hidden="true"><i class="bi bi-box-seam"></i></div>
                @if (trim($search) !== '' || $categoryFilter !== '' || $statusFilter !== '')
                    <h3>No products found</h3>
                    <p>Nothing matches your search or filters. Try a different one.</p>
                @else
                    <h3>No products yet</h3>
                    <p>Add your first product to start building the catalogue.</p>
                @endif
            </div>
        @else
            <table>
                <tr>
                    <th scope="col">Product</th>
                    <th scope="col">SKU</th>
                    <th scope="col">Price</th>
                    <th scope="col">MOQ</th>
                    <th scope="col">Stock</th>
                    <th scope="col">Visible</th>
                    <th scope="col"><span class="sr-only">Actions</span></th>
                </tr>
                @foreach ($products as $product)
                    <tr wire:key="product-{{ $product->id }}">
                        <td data-label="Product">
                            {{ $product->name }}
                            @if ($product->is_popular)<span class="pill info">Popular</span>@endif
                            @if ($product->is_new)<span class="pill info">New</span>@endif
                            <small class="muted" style="display:block">{{ $product->category->name }}@if ($product->brand) · {{ $product->brand->name }}@endif</small>
                        </td>
                        <td data-label="SKU">{{ $product->sku ?? '-' }}</td>
                        <td data-label="Price">£{{ number_format((float) $product->price, 2) }}</td>
                        <td data-label="MOQ">{{ $product->moq ?? '-' }}</td>
                        <td data-label="Stock"><x-admin.product-status :status="$product->status" /></td>
                        <td data-label="Visible">
                            <button type="button" @class(['pill', 'ok' => $product->is_active]) wire:click="toggleActive({{ $product->id }})" title="Click to change" aria-label="{{ $product->name }} is {{ $product->is_active ? 'visible' : 'hidden' }}. Click to change.">
                                {{ $product->is_active ? 'Visible' : 'Hidden' }}
                            </button>
                        </td>
                        <td data-label="Actions" class="acts">
                            <a class="ib" href="{{ route('admin.products.edit', $product) }}" aria-label="Edit {{ $product->name }}"><i class="bi bi-pencil" aria-hidden="true"></i></a>
                            <button class="ib bad" type="button" wire:click="confirmDelete({{ $product->id }})" aria-label="Delete {{ $product->name }}"><i class="bi bi-trash" aria-hidden="true"></i></button>
                        </td>
                    </tr>
                @endforeach
            </table>

            {{ $products->links('livewire.admin.pagination') }}
        @endif
    </div>

    @if ($deletingProduct)
        <div class="ov" wire:key="product-delete-modal" wire:keydown.escape.window="cancelDelete">
            <div class="mdl" role="alertdialog" aria-modal="true" aria-labelledby="product-delete-title">
                <h3 id="product-delete-title">Delete product?</h3>
                <p>"{{ $deletingProduct->name }}" will be removed from the catalogue. Past enquiries keep their record of it.</p>
                <div class="act">
                    <button class="btn" type="button" wire:click="cancelDelete">Cancel</button>
                    <button class="btn danger" type="button" wire:click="delete" wire:loading.attr="disabled" wire:target="delete">Delete</button>
                </div>
            </div>
        </div>
    @endif
</div>
