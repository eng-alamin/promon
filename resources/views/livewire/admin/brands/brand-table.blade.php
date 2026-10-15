<div>
    <div class="tools">
        <input class="f" type="search" wire:model.live.debounce.300ms="search" placeholder="Search brands..." aria-label="Search brands">
        <button class="btn pri" type="button" wire:click="create"><i class="bi bi-plus-lg" aria-hidden="true"></i> Add brand</button>
    </div>

    <div class="card">
        @if ($brands->isEmpty())
            <div class="empty">
                <div class="empty-ic" aria-hidden="true"><i class="bi bi-award"></i></div>
                @if (trim($search) !== '')
                    <h3>No brands found</h3>
                    <p>Nothing matches "{{ $search }}". Try a different word.</p>
                @else
                    <h3>No brands yet</h3>
                    <p>Add the brands of the products you import.</p>
                @endif
            </div>
        @else
            <table>
                <tr>
                    <th scope="col">Name</th>
                    <th scope="col">Slug</th>
                    <th scope="col">Products</th>
                    <th scope="col">Status</th>
                    <th scope="col"><span class="sr-only">Actions</span></th>
                </tr>
                @foreach ($brands as $brand)
                    <tr wire:key="brand-{{ $brand->id }}">
                        <td data-label="Name">{{ $brand->name }}</td>
                        <td data-label="Slug"><code>{{ $brand->slug }}</code></td>
                        <td data-label="Products">{{ $brand->products_count }}</td>
                        <td data-label="Status">
                            <button type="button" @class(['pill', 'ok' => $brand->is_active]) wire:click="toggleActive({{ $brand->id }})" title="Click to change" aria-label="{{ $brand->name }} is {{ $brand->is_active ? 'active' : 'inactive' }}. Click to change.">
                                {{ $brand->is_active ? 'Active' : 'Inactive' }}
                            </button>
                        </td>
                        <td data-label="Actions" class="acts">
                            <button class="ib" type="button" wire:click="edit({{ $brand->id }})" aria-label="Edit {{ $brand->name }}"><i class="bi bi-pencil" aria-hidden="true"></i></button>
                            <button class="ib bad" type="button" wire:click="confirmDelete({{ $brand->id }})" aria-label="Delete {{ $brand->name }}"><i class="bi bi-trash" aria-hidden="true"></i></button>
                        </td>
                    </tr>
                @endforeach
            </table>

            {{ $brands->links('livewire.admin.pagination') }}
        @endif
    </div>

    @if ($showForm)
        <div class="ov" wire:key="brand-form-modal" wire:keydown.escape.window="closeForm">
            <form class="mdl" role="dialog" aria-modal="true" aria-labelledby="brand-form-title" wire:submit="save" novalidate>
                <h3 id="brand-form-title">{{ $editingId ? 'Edit brand' : 'Add brand' }}</h3>

                <label for="brand-name">Name</label>
                <input class="f" id="brand-name" type="text" wire:model="name" maxlength="120" autofocus required @error('name') aria-invalid="true" aria-describedby="brand-name-error" @enderror>
                @error('name')
                    <p class="err-msg" id="brand-name-error"><i class="bi bi-exclamation-circle" aria-hidden="true"></i>{{ $message }}</p>
                @enderror

                <label for="brand-slug">Slug</label>
                <input class="f" id="brand-slug" type="text" wire:model="slug" maxlength="140" @error('slug') aria-invalid="true" aria-describedby="brand-slug-error" @enderror>
                <p class="hint">Used in the web address. Leave empty to create it from the name.</p>
                @error('slug')
                    <p class="err-msg" id="brand-slug-error"><i class="bi bi-exclamation-circle" aria-hidden="true"></i>{{ $message }}</p>
                @enderror

                <label class="chk" for="brand-active">
                    <input id="brand-active" type="checkbox" wire:model="is_active">
                    Active (visible on the website)
                </label>

                <div class="act">
                    <button class="btn" type="button" wire:click="closeForm">Cancel</button>
                    <button class="btn pri" type="submit" wire:loading.attr="disabled" wire:target="save">Save</button>
                </div>
            </form>
        </div>
    @endif

    @if ($deletingBrand)
        <div class="ov" wire:key="brand-delete-modal" wire:keydown.escape.window="cancelDelete">
            <div class="mdl" role="alertdialog" aria-modal="true" aria-labelledby="brand-delete-title">
                <h3 id="brand-delete-title">Delete brand?</h3>
                <p>"{{ $deletingBrand->name }}" will be deleted. This cannot be undone. A brand that has products cannot be deleted.</p>
                <div class="act">
                    <button class="btn" type="button" wire:click="cancelDelete">Cancel</button>
                    <button class="btn danger" type="button" wire:click="delete" wire:loading.attr="disabled" wire:target="delete">Delete</button>
                </div>
            </div>
        </div>
    @endif
</div>
