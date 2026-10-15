<div class="card">
    <h2 class="sec">Quantity prices</h2>
    <p class="hint">Base price: £{{ number_format((float) $basePrice, 2) }} per unit. Add lower prices for bigger orders, for example 12+ units.</p>

    @if ($tiers->isEmpty())
        <div class="empty">
            <div class="empty-ic" aria-hidden="true"><i class="bi bi-layers"></i></div>
            <h3>No quantity prices</h3>
            <p>Customers will see the base price only.</p>
        </div>
    @else
        <table>
            <tr>
                <th scope="col">Quantity</th>
                <th scope="col">Price per unit</th>
                <th scope="col"><span class="sr-only">Actions</span></th>
            </tr>
            @foreach ($tiers as $tier)
                <tr wire:key="tier-{{ $tier->id }}">
                    <td data-label="Quantity">{{ number_format($tier->min_qty) }}+</td>
                    <td data-label="Price per unit">£{{ number_format((float) $tier->price, 2) }}</td>
                    <td data-label="Actions" class="acts">
                        <button class="ib" type="button" wire:click="edit({{ $tier->id }})" aria-label="Edit price for {{ $tier->min_qty }}+ units"><i class="bi bi-pencil" aria-hidden="true"></i></button>
                        <button class="ib bad" type="button" wire:click="delete({{ $tier->id }})" wire:confirm="Delete this price tier?" aria-label="Delete price for {{ $tier->min_qty }}+ units"><i class="bi bi-trash" aria-hidden="true"></i></button>
                    </td>
                </tr>
            @endforeach
        </table>
    @endif

    <form wire:submit="save" novalidate>
        <h3 class="sub-h">{{ $editingId ? 'Edit quantity price' : 'Add quantity price' }}</h3>

        <div class="fgrid">
            <x-admin.field name="min_qty" id="tier-min_qty" label="From quantity">
                <input class="f" id="tier-min_qty" type="number" min="2" inputmode="numeric" wire:model="min_qty" required @error('min_qty') aria-invalid="true" aria-describedby="tier-min_qty-error" @enderror>
            </x-admin.field>

            <x-admin.field name="price" id="tier-price" label="Price per unit (£ GBP)">
                <input class="f" id="tier-price" type="text" inputmode="decimal" wire:model="price" placeholder="0.00" required @error('price') aria-invalid="true" aria-describedby="tier-price-error" @enderror>
            </x-admin.field>
        </div>

        <div class="formbar">
            @if ($editingId)
                <button class="btn" type="button" wire:click="cancelEdit">Cancel</button>
            @endif
            <button class="btn pri" type="submit" wire:loading.attr="disabled" wire:target="save">{{ $editingId ? 'Save price' : 'Add price' }}</button>
        </div>
    </form>
</div>
