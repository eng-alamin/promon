@php
    $customer = $enquiry->customer;
    $reachNumber = preg_replace('/\D+/', '', (string) ($customer->whatsapp ?: $customer->phone));
@endphp

<div class="stack">
    <div class="card">
        <h2 class="sec">{{ $enquiry->reference }} <x-admin.status-pill :status="$enquiry->status" /></h2>

        <dl class="kv">
            <dt>Received</dt>
            <dd>{{ $enquiry->created_at->format('d M Y, H:i') }} ({{ $enquiry->created_at->diffForHumans() }})</dd>
            <dt>Channel</dt>
            <dd>{{ $enquiry->channel->label() }}</dd>
            <dt>Handled by</dt>
            <dd>
                @if ($enquiry->handler)
                    {{ $enquiry->handler->name }}
                @else
                    Not assigned
                @endif
                @if ($enquiry->handled_by !== auth()->id())
                    <button class="btn" type="button" wire:click="assignToMe" wire:loading.attr="disabled" wire:target="assignToMe">Assign to me</button>
                @endif
            </dd>
        </dl>

        <label for="enquiry-status">Status</label>
        <select class="f" id="enquiry-status" wire:change="setStatus($event.target.value)" @error('status') aria-invalid="true" @enderror>
            @foreach ($statuses as $status)
                <option value="{{ $status->value }}" @selected($enquiry->status === $status)>{{ $status->label() }}</option>
            @endforeach
        </select>
        @error('status')
            <p class="err-msg"><i class="bi bi-exclamation-circle" aria-hidden="true"></i>{{ $message }}</p>
        @enderror
    </div>

    <div class="card">
        <h2 class="sec">Customer</h2>

        <dl class="kv">
            <dt>Name</dt>
            <dd>{{ $customer->contact_name }}</dd>
            @if ($customer->business_name)
                <dt>Business</dt>
                <dd>{{ $customer->business_name }}</dd>
            @endif
            <dt>Type</dt>
            <dd>{{ $customer->type->label() }}</dd>
            @if ($customer->phone)
                <dt>Phone</dt>
                <dd>{{ $customer->phone }}</dd>
            @endif
            @if ($customer->whatsapp)
                <dt>WhatsApp</dt>
                <dd>{{ $customer->whatsapp }}</dd>
            @endif
            @if ($customer->email)
                <dt>Email</dt>
                <dd><a href="mailto:{{ $customer->email }}">{{ $customer->email }}</a></dd>
            @endif
            @if ($customer->city || $customer->country)
                <dt>Location</dt>
                <dd>{{ collect([$customer->city, $customer->country])->filter()->implode(', ') }}</dd>
            @endif
        </dl>

        @if (strlen($reachNumber) >= 8)
            <p><a class="btn" href="https://wa.me/{{ $reachNumber }}" target="_blank" rel="noopener noreferrer"><i class="bi bi-whatsapp" aria-hidden="true"></i> Open in WhatsApp</a></p>
        @endif
    </div>

    @if ($enquiry->items->isNotEmpty())
    <div class="card">
        <h2 class="sec">Products</h2>
        <p class="hint">Prices are the ones shown to the customer when the enquiry was made. They do not change if the catalogue changes later.</p>

        <table>
            <tr>
                <th scope="col">Product</th>
                <th scope="col">Quantity</th>
                <th scope="col">Unit price</th>
                <th scope="col">Line total</th>
            </tr>
            @foreach ($enquiry->items as $item)
                <tr wire:key="item-{{ $item->id }}">
                    <td data-label="Product">
                        @if ($item->product->trashed())
                            {{ $item->product->name }} <span class="pill">Deleted</span>
                        @else
                            <a href="{{ route('admin.products.edit', $item->product) }}">{{ $item->product->name }}</a>
                        @endif
                    </td>
                    <td data-label="Quantity">{{ number_format($item->quantity) }}</td>
                    <td data-label="Unit price">£{{ number_format((float) $item->unit_price, 2) }}</td>
                    <td data-label="Line total">£{{ number_format($item->quantity * (float) $item->unit_price, 2) }}</td>
                </tr>
            @endforeach
            <tr class="total">
                <td data-label="Product"><strong>Total</strong></td>
                <td data-label="Quantity">{{ number_format($enquiry->items->sum('quantity')) }}</td>
                <td data-label="Unit price"></td>
                <td data-label="Line total"><strong>£{{ number_format($total, 2) }}</strong></td>
            </tr>
        </table>
    </div>
    @endif

    @if ($enquiry->message)
        <div class="card">
            <h2 class="sec">Customer message</h2>
            <p class="pre">{{ $enquiry->message }}</p>
        </div>
    @endif

    <form class="card" wire:submit="saveNotes" novalidate>
        <h2 class="sec">Internal notes</h2>
        <p class="hint">Only staff can see these notes.</p>

        <label for="admin-notes" class="sr-only">Internal notes</label>
        <textarea class="f" id="admin-notes" rows="5" wire:model="admin_notes" maxlength="5000" @error('admin_notes') aria-invalid="true" aria-describedby="admin-notes-error" @enderror></textarea>
        @error('admin_notes')
            <p class="err-msg" id="admin-notes-error"><i class="bi bi-exclamation-circle" aria-hidden="true"></i>{{ $message }}</p>
        @enderror

        <div class="formbar">
            <button class="btn pri" type="submit" wire:loading.attr="disabled" wire:target="saveNotes">Save notes</button>
        </div>
    </form>
    @if (auth()->user()->isSuperAdmin())
        <div class="card">
            <h2 class="sec">Delete enquiry</h2>
            <p class="hint">This removes the enquiry and its product list for good. To keep the record, set the status to Closed instead.</p>
            <button class="btn danger" type="button" wire:click="confirmDelete">Delete enquiry</button>
        </div>
    @endif

    @if ($confirmingDelete)
        <div class="ov" wire:key="enquiry-delete-modal" wire:keydown.escape.window="cancelDelete">
            <div class="mdl" role="alertdialog" aria-modal="true" aria-labelledby="enquiry-delete-title">
                <h3 id="enquiry-delete-title">Delete enquiry?</h3>
                <p>{{ $enquiry->reference }} and its products will be deleted. This cannot be undone.</p>
                <div class="act">
                    <button class="btn" type="button" wire:click="cancelDelete">Cancel</button>
                    <button class="btn danger" type="button" wire:click="delete" wire:loading.attr="disabled" wire:target="delete">Delete</button>
                </div>
            </div>
        </div>
    @endif
</div>
