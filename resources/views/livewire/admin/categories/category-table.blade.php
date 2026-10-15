<div>
    <div class="tools">
        <input class="f" type="search" wire:model.live.debounce.300ms="search" placeholder="Search categories..." aria-label="Search categories">
        <button class="btn pri" type="button" wire:click="create"><i class="bi bi-plus-lg" aria-hidden="true"></i> Add category</button>
    </div>

    <div class="card">
        @if ($categories->isEmpty())
            <div class="empty">
                <div class="empty-ic" aria-hidden="true"><i class="bi bi-tags"></i></div>
                @if (trim($search) !== '')
                    <h3>No categories found</h3>
                    <p>Nothing matches "{{ $search }}". Try a different word.</p>
                @else
                    <h3>No categories yet</h3>
                    <p>Add your first category to start building the catalogue.</p>
                @endif
            </div>
        @else
            <table>
                <tr>
                    <th scope="col">Name</th>
                    <th scope="col">Slug</th>
                    <th scope="col">Products</th>
                    <th scope="col">Order</th>
                    <th scope="col">Status</th>
                    <th scope="col"><span class="sr-only">Actions</span></th>
                </tr>
                @foreach ($categories as $category)
                    <tr wire:key="category-{{ $category->id }}">
                        <td data-label="Name">{{ $category->name }}</td>
                        <td data-label="Slug"><code>{{ $category->slug }}</code></td>
                        <td data-label="Products">{{ $category->products_count }}</td>
                        <td data-label="Order">{{ $category->sort_order }}</td>
                        <td data-label="Status">
                            <button type="button" @class(['pill', 'ok' => $category->is_active]) wire:click="toggleActive({{ $category->id }})" title="Click to change" aria-label="{{ $category->name }} is {{ $category->is_active ? 'active' : 'inactive' }}. Click to change.">
                                {{ $category->is_active ? 'Active' : 'Inactive' }}
                            </button>
                        </td>
                        <td data-label="Actions" class="acts">
                            <button class="ib" type="button" wire:click="edit({{ $category->id }})" aria-label="Edit {{ $category->name }}"><i class="bi bi-pencil" aria-hidden="true"></i></button>
                            <button class="ib bad" type="button" wire:click="confirmDelete({{ $category->id }})" aria-label="Delete {{ $category->name }}"><i class="bi bi-trash" aria-hidden="true"></i></button>
                        </td>
                    </tr>
                @endforeach
            </table>

            {{ $categories->links('livewire.admin.pagination') }}
        @endif
    </div>

    @if ($showForm)
        <div class="ov" wire:key="category-form-modal" wire:keydown.escape.window="closeForm">
            <form class="mdl" role="dialog" aria-modal="true" aria-labelledby="category-form-title" wire:submit="save" novalidate>
                <h3 id="category-form-title">{{ $editingId ? 'Edit category' : 'Add category' }}</h3>

                <label for="category-name">Name</label>
                <input class="f" id="category-name" type="text" wire:model="name" maxlength="100" autofocus required @error('name') aria-invalid="true" aria-describedby="category-name-error" @enderror>
                @error('name')
                    <p class="err-msg" id="category-name-error"><i class="bi bi-exclamation-circle" aria-hidden="true"></i>{{ $message }}</p>
                @enderror

                <label for="category-slug">Slug</label>
                <input class="f" id="category-slug" type="text" wire:model="slug" maxlength="120" @error('slug') aria-invalid="true" aria-describedby="category-slug-error" @enderror>
                <p class="hint">Used in the web address. Leave empty to create it from the name.</p>
                @error('slug')
                    <p class="err-msg" id="category-slug-error"><i class="bi bi-exclamation-circle" aria-hidden="true"></i>{{ $message }}</p>
                @enderror

                <label for="category-order">Sort order</label>
                <input class="f" id="category-order" type="number" min="0" max="65535" inputmode="numeric" wire:model="sort_order" @error('sort_order') aria-invalid="true" aria-describedby="category-order-error" @enderror>
                <p class="hint">Smaller numbers appear first.</p>
                @error('sort_order')
                    <p class="err-msg" id="category-order-error"><i class="bi bi-exclamation-circle" aria-hidden="true"></i>{{ $message }}</p>
                @enderror

                <label class="chk" for="category-active">
                    <input id="category-active" type="checkbox" wire:model="is_active">
                    Active (visible on the website)
                </label>

                <div class="act">
                    <button class="btn" type="button" wire:click="closeForm">Cancel</button>
                    <button class="btn pri" type="submit" wire:loading.attr="disabled" wire:target="save">Save</button>
                </div>
            </form>
        </div>
    @endif

    @if ($deletingCategory)
        <div class="ov" wire:key="category-delete-modal" wire:keydown.escape.window="cancelDelete">
            <div class="mdl" role="alertdialog" aria-modal="true" aria-labelledby="category-delete-title">
                <h3 id="category-delete-title">Delete category?</h3>
                <p>"{{ $deletingCategory->name }}" will be deleted. This cannot be undone. A category that has products cannot be deleted.</p>
                <div class="act">
                    <button class="btn" type="button" wire:click="cancelDelete">Cancel</button>
                    <button class="btn danger" type="button" wire:click="delete" wire:loading.attr="disabled" wire:target="delete">Delete</button>
                </div>
            </div>
        </div>
    @endif
</div>
