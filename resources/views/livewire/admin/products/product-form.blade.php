<form class="card" wire:submit="save" novalidate>
    <h2 class="sec">{{ $productId ? 'Edit product' : 'Add product' }}</h2>

    <div class="fgrid">
        <x-admin.field name="name" label="Name" span>
            <input class="f" id="f-name" type="text" wire:model="name" maxlength="200" required @error('name') aria-invalid="true" aria-describedby="f-name-error" @enderror>
        </x-admin.field>

        <x-admin.field name="category_id" label="Category">
            <select class="f" id="f-category_id" wire:model="category_id" required @error('category_id') aria-invalid="true" aria-describedby="f-category_id-error" @enderror>
                <option value="">Choose a category</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}{{ $category->is_active ? '' : ' (inactive)' }}</option>
                @endforeach
            </select>
        </x-admin.field>

        <x-admin.field name="brand_id" label="Brand (optional)">
            <select class="f" id="f-brand_id" wire:model="brand_id" @error('brand_id') aria-invalid="true" aria-describedby="f-brand_id-error" @enderror>
                <option value="">No brand</option>
                @foreach ($brands as $brand)
                    <option value="{{ $brand->id }}">{{ $brand->name }}{{ $brand->is_active ? '' : ' (inactive)' }}</option>
                @endforeach
            </select>
        </x-admin.field>

        <x-admin.field name="slug" label="Slug" hint="Used in the web address. Leave empty to create it from the name.">
            <input class="f" id="f-slug" type="text" wire:model="slug" maxlength="220" @error('slug') aria-invalid="true" aria-describedby="f-slug-error" @enderror>
        </x-admin.field>

        <x-admin.field name="sku" label="SKU (optional)">
            <input class="f" id="f-sku" type="text" wire:model="sku" maxlength="60" @error('sku') aria-invalid="true" aria-describedby="f-sku-error" @enderror>
        </x-admin.field>

        <x-admin.field name="price" label="Price (£ GBP)" hint="Base price per unit. Quantity based prices come in the next step.">
            <input class="f" id="f-price" type="text" inputmode="decimal" wire:model="price" placeholder="0.00" required @error('price') aria-invalid="true" aria-describedby="f-price-error" @enderror>
        </x-admin.field>

        <x-admin.field name="moq" label="MOQ (optional)" hint="Minimum order quantity.">
            <input class="f" id="f-moq" type="number" min="1" inputmode="numeric" wire:model="moq" @error('moq') aria-invalid="true" aria-describedby="f-moq-error" @enderror>
        </x-admin.field>

        <x-admin.field name="pack_size" label="Pack size (optional)">
            <input class="f" id="f-pack_size" type="text" wire:model="pack_size" maxlength="80" placeholder="e.g. 12 x 500g" @error('pack_size') aria-invalid="true" aria-describedby="f-pack_size-error" @enderror>
        </x-admin.field>

        <x-admin.field name="status" label="Stock status">
            <select class="f" id="f-status" wire:model="status" required @error('status') aria-invalid="true" aria-describedby="f-status-error" @enderror>
                @foreach ($statuses as $status)
                    <option value="{{ $status->value }}">{{ $status->label() }}</option>
                @endforeach
            </select>
        </x-admin.field>

        <x-admin.field name="origin_country" label="Country of origin (optional)">
            <input class="f" id="f-origin_country" type="text" wire:model="origin_country" maxlength="80" @error('origin_country') aria-invalid="true" aria-describedby="f-origin_country-error" @enderror>
        </x-admin.field>

        <x-admin.field name="shelf_life" label="Shelf life (optional)">
            <input class="f" id="f-shelf_life" type="text" wire:model="shelf_life" maxlength="80" placeholder="e.g. 18 months" @error('shelf_life') aria-invalid="true" aria-describedby="f-shelf_life-error" @enderror>
        </x-admin.field>

        <x-admin.field name="bd_approval" label="Bangladesh approval (optional)">
            <input class="f" id="f-bd_approval" type="text" wire:model="bd_approval" maxlength="160" @error('bd_approval') aria-invalid="true" aria-describedby="f-bd_approval-error" @enderror>
        </x-admin.field>

        <x-admin.field name="sort_order" label="Sort order" hint="Smaller numbers appear first.">
            <input class="f" id="f-sort_order" type="number" min="0" max="65535" inputmode="numeric" wire:model="sort_order" required @error('sort_order') aria-invalid="true" aria-describedby="f-sort_order-error" @enderror>
        </x-admin.field>

        <x-admin.field name="description" label="Description (optional)" span>
            <textarea class="f" id="f-description" rows="5" wire:model="description" maxlength="5000" @error('description') aria-invalid="true" aria-describedby="f-description-error" @enderror></textarea>
        </x-admin.field>
    </div>

    <label class="chk" for="f-is_active"><input id="f-is_active" type="checkbox" wire:model="is_active"> Visible on the website</label>
    <label class="chk" for="f-is_popular"><input id="f-is_popular" type="checkbox" wire:model="is_popular"> Show as popular</label>
    <label class="chk" for="f-is_new"><input id="f-is_new" type="checkbox" wire:model="is_new"> Show as new arrival</label>

    <div class="formbar">
        <a class="btn" href="{{ route('admin.products.index') }}">Cancel</a>
        <button class="btn pri" type="submit" wire:loading.attr="disabled" wire:target="save">Save product</button>
    </div>
</form>
