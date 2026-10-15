<div>
    <div class="tools">
        <input class="f" type="search" wire:model.live.debounce.300ms="search" placeholder="Search name, business, phone, email or city..." aria-label="Search customers">
        <select class="f" wire:model.live="typeFilter" aria-label="Filter by type">
            <option value="">All types</option>
            @foreach ($types as $type)
                <option value="{{ $type->value }}">{{ $type->label() }}</option>
            @endforeach
        </select>
        <button class="btn pri" type="button" wire:click="create"><i class="bi bi-plus-lg" aria-hidden="true"></i> Add customer</button>
    </div>

    <div class="card">
        @if ($customers->isEmpty())
            <div class="empty">
                <div class="empty-ic" aria-hidden="true"><i class="bi bi-people"></i></div>
                @if (trim($search) !== '' || $typeFilter !== '')
                    <h3>No customers found</h3>
                    <p>Nothing matches your search or filter. Try a different one.</p>
                @else
                    <h3>No customers yet</h3>
                    <p>Customers are added when they send an enquiry. You can also add one yourself.</p>
                @endif
            </div>
        @else
            <table>
                <tr>
                    <th scope="col">Customer</th>
                    <th scope="col">Type</th>
                    <th scope="col">Contact</th>
                    <th scope="col">Location</th>
                    <th scope="col">Enquiries</th>
                    <th scope="col"><span class="sr-only">Actions</span></th>
                </tr>
                @foreach ($customers as $customer)
                    @php($location = collect([$customer->city, $customer->country])->filter()->implode(', '))
                    <tr wire:key="customer-{{ $customer->id }}">
                        <td data-label="Customer">
                            <strong>{{ $customer->business_name ?: $customer->contact_name }}</strong>
                            @if ($customer->business_name)
                                <div class="muted">{{ $customer->contact_name }}</div>
                            @endif
                        </td>
                        <td data-label="Type"><span class="pill">{{ $customer->type->label() }}</span></td>
                        <td data-label="Contact">
                            @if ($customer->phone)
                                <div><i class="bi bi-telephone" aria-hidden="true"></i> <span class="sr-only">Phone</span> {{ $customer->phone }}</div>
                            @endif
                            @if ($customer->whatsapp)
                                <div><i class="bi bi-whatsapp" aria-hidden="true"></i> <span class="sr-only">WhatsApp</span> {{ $customer->whatsapp }}</div>
                            @endif
                            @if ($customer->email)
                                <div><i class="bi bi-envelope" aria-hidden="true"></i> <span class="sr-only">Email</span> {{ $customer->email }}</div>
                            @endif
                        </td>
                        <td data-label="Location">{{ $location !== '' ? $location : '—' }}</td>
                        <td data-label="Enquiries">{{ $customer->enquiries_count }}</td>
                        <td data-label="Actions" class="acts">
                            <button class="ib" type="button" wire:click="edit({{ $customer->id }})" aria-label="Edit {{ $customer->business_name ?: $customer->contact_name }}"><i class="bi bi-pencil" aria-hidden="true"></i></button>
                            <button class="ib bad" type="button" wire:click="confirmDelete({{ $customer->id }})" aria-label="Delete {{ $customer->business_name ?: $customer->contact_name }}"><i class="bi bi-trash" aria-hidden="true"></i></button>
                        </td>
                    </tr>
                @endforeach
            </table>

            {{ $customers->links('livewire.admin.pagination') }}
        @endif
    </div>

    @if ($showForm)
        <div class="ov" wire:key="customer-form-modal" wire:keydown.escape.window="closeForm">
            <form class="mdl wide" role="dialog" aria-modal="true" aria-labelledby="customer-form-title" wire:submit="save" novalidate>
                <h3 id="customer-form-title">{{ $editingId ? 'Edit customer' : 'Add customer' }}</h3>

                <div class="fgrid">
                    <x-admin.field name="contact_name" id="cu-contact_name" label="Contact name">
                        <input class="f" id="cu-contact_name" type="text" wire:model="contact_name" maxlength="120" autofocus required @error('contact_name') aria-invalid="true" aria-describedby="cu-contact_name-error" @enderror>
                    </x-admin.field>

                    <x-admin.field name="business_name" id="cu-business_name" label="Business name (optional)">
                        <input class="f" id="cu-business_name" type="text" wire:model="business_name" maxlength="160" @error('business_name') aria-invalid="true" aria-describedby="cu-business_name-error" @enderror>
                    </x-admin.field>

                    <x-admin.field name="phone" id="cu-phone" label="Phone">
                        <input class="f" id="cu-phone" type="text" inputmode="tel" wire:model="phone" maxlength="30" @error('phone') aria-invalid="true" aria-describedby="cu-phone-error" @enderror>
                    </x-admin.field>

                    <x-admin.field name="whatsapp" id="cu-whatsapp" label="WhatsApp" hint="Include the country code, for example 8801700000000.">
                        <input class="f" id="cu-whatsapp" type="text" inputmode="tel" wire:model="whatsapp" maxlength="30" @error('whatsapp') aria-invalid="true" aria-describedby="cu-whatsapp-error" @enderror>
                    </x-admin.field>

                    <x-admin.field name="email" id="cu-email" label="Email">
                        <input class="f" id="cu-email" type="email" wire:model="email" maxlength="255" @error('email') aria-invalid="true" aria-describedby="cu-email-error" @enderror>
                    </x-admin.field>

                    <x-admin.field name="customer_type" id="cu-type" label="Customer type">
                        <select class="f" id="cu-type" wire:model="customer_type" @error('customer_type') aria-invalid="true" aria-describedby="cu-type-error" @enderror>
                            @foreach ($types as $type)
                                <option value="{{ $type->value }}">{{ $type->label() }}</option>
                            @endforeach
                        </select>
                    </x-admin.field>

                    <x-admin.field name="city" id="cu-city" label="City (optional)">
                        <input class="f" id="cu-city" type="text" wire:model="city" maxlength="100">
                    </x-admin.field>

                    <x-admin.field name="country" id="cu-country" label="Country (optional)">
                        <input class="f" id="cu-country" type="text" wire:model="country" maxlength="80">
                    </x-admin.field>

                    <x-admin.field name="notes" id="cu-notes" label="Internal notes (optional)" hint="Only staff can see this." :span="true">
                        <textarea class="f" id="cu-notes" rows="3" wire:model="notes" maxlength="5000" @error('notes') aria-invalid="true" aria-describedby="cu-notes-error" @enderror></textarea>
                    </x-admin.field>
                </div>

                <p class="hint">Add at least one of phone, WhatsApp or email.</p>

                <div class="act">
                    <button class="btn" type="button" wire:click="closeForm">Cancel</button>
                    <button class="btn pri" type="submit" wire:loading.attr="disabled" wire:target="save">Save</button>
                </div>
            </form>
        </div>
    @endif

    @if ($deletingCustomer)
        <div class="ov" wire:key="customer-delete-modal" wire:keydown.escape.window="cancelDelete">
            <div class="mdl" role="alertdialog" aria-modal="true" aria-labelledby="customer-delete-title">
                <h3 id="customer-delete-title">Delete customer?</h3>
                <p>"{{ $deletingCustomer->business_name ?: $deletingCustomer->contact_name }}" will be deleted. This cannot be undone. A customer who has enquiries cannot be deleted.</p>
                <div class="act">
                    <button class="btn" type="button" wire:click="cancelDelete">Cancel</button>
                    <button class="btn danger" type="button" wire:click="delete" wire:loading.attr="disabled" wire:target="delete">Delete</button>
                </div>
            </div>
        </div>
    @endif
</div>
