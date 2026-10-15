<form class="stack" wire:submit="save" novalidate>
    <div class="card">
        <h2 class="sec">Customer</h2>

        <div class="seg" role="group" aria-label="Customer type">
            <label class="chk" for="mode-existing"><input id="mode-existing" type="radio" wire:model.live="customerMode" value="existing"> Existing customer</label>
            <label class="chk" for="mode-new"><input id="mode-new" type="radio" wire:model.live="customerMode" value="new"> New customer</label>
        </div>

        @if ($customerMode === 'existing')
            @if ($selectedCustomer)
                <p><strong>{{ $selectedCustomer->business_name ?: $selectedCustomer->contact_name }}</strong>
                    <span class="muted">{{ $selectedCustomer->contact_name }}@if ($selectedCustomer->phone) · {{ $selectedCustomer->phone }}@endif</span>
                    <button class="btn" type="button" wire:click="clearCustomer">Change</button></p>
            @else
                <label for="customer-search">Find a customer</label>
                <input class="f" id="customer-search" type="search" wire:model.live.debounce.300ms="customerSearch" placeholder="Name, business, phone or email..." autocomplete="off">

                @if (trim($customerSearch) !== '')
                    @if ($results->isEmpty())
                        <p class="hint">No customer found. Try another word, or choose "New customer".</p>
                    @else
                        <ul class="picks">
                            @foreach ($results as $found)
                                <li wire:key="customer-{{ $found->id }}">
                                    <button class="btn" type="button" wire:click="selectCustomer({{ $found->id }})">
                                        {{ $found->business_name ?: $found->contact_name }}
                                        <span class="muted">{{ $found->contact_name }}@if ($found->phone) · {{ $found->phone }}@endif</span>
                                    </button>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                @endif
            @endif
            @error('customerId')
                <p class="err-msg"><i class="bi bi-exclamation-circle" aria-hidden="true"></i>{{ $message }}</p>
            @enderror
        @else
            <div class="fgrid">
                <x-admin.field name="contact_name" id="nc-contact_name" label="Contact name">
                    <input class="f" id="nc-contact_name" type="text" wire:model="contact_name" maxlength="120" required @error('contact_name') aria-invalid="true" aria-describedby="nc-contact_name-error" @enderror>
                </x-admin.field>

                <x-admin.field name="business_name" id="nc-business_name" label="Business name (optional)">
                    <input class="f" id="nc-business_name" type="text" wire:model="business_name" maxlength="160">
                </x-admin.field>

                <x-admin.field name="phone" id="nc-phone" label="Phone">
                    <input class="f" id="nc-phone" type="text" inputmode="tel" wire:model="phone" maxlength="30" @error('phone') aria-invalid="true" aria-describedby="nc-phone-error" @enderror>
                </x-admin.field>

                <x-admin.field name="whatsapp" id="nc-whatsapp" label="WhatsApp" hint="Include the country code, for example 8801700000000.">
                    <input class="f" id="nc-whatsapp" type="text" inputmode="tel" wire:model="whatsapp" maxlength="30" @error('whatsapp') aria-invalid="true" aria-describedby="nc-whatsapp-error" @enderror>
                </x-admin.field>

                <x-admin.field name="email" id="nc-email" label="Email">
                    <input class="f" id="nc-email" type="email" wire:model="email" maxlength="255" @error('email') aria-invalid="true" aria-describedby="nc-email-error" @enderror>
                </x-admin.field>

                <x-admin.field name="customer_type" id="nc-type" label="Customer type">
                    <select class="f" id="nc-type" wire:model="customer_type">
                        @foreach ($types as $type)
                            <option value="{{ $type->value }}">{{ $type->label() }}</option>
                        @endforeach
                    </select>
                </x-admin.field>

                <x-admin.field name="city" id="nc-city" label="City (optional)">
                    <input class="f" id="nc-city" type="text" wire:model="city" maxlength="100">
                </x-admin.field>

                <x-admin.field name="country" id="nc-country" label="Country (optional)">
                    <input class="f" id="nc-country" type="text" wire:model="country" maxlength="80">
                </x-admin.field>
            </div>
        @endif
    </div>

    <div class="card">
        <h2 class="sec">Categories</h2>
        <p class="hint">Choose one or more categories the customer is asking about.</p>

        @error('categoryIds')
            <p class="err-msg"><i class="bi bi-exclamation-circle" aria-hidden="true"></i>{{ $message }}</p>
        @enderror
        @error('categoryIds.*')
            <p class="err-msg"><i class="bi bi-exclamation-circle" aria-hidden="true"></i>{{ $message }}</p>
        @enderror

        @if ($categories->isEmpty())
            <p class="hint">There are no active categories. Add one in Catalogue first.</p>
        @else
            <div style="display:flex;flex-wrap:wrap;gap:0 28px">
                @foreach ($categories as $category)
                    <label class="chk" for="cat-{{ $category->id }}" wire:key="cat-{{ $category->id }}">
                        <input id="cat-{{ $category->id }}" type="checkbox" wire:model="categoryIds" value="{{ $category->id }}"> {{ $category->name }}
                    </label>
                @endforeach
            </div>
        @endif
    </div>

    <div class="card">
        <h2 class="sec">Message (optional)</h2>
        <label for="enquiry-message" class="sr-only">Message</label>
        <textarea class="f" id="enquiry-message" rows="4" wire:model="message" maxlength="5000" placeholder="What the customer asked for, or anything to remember"></textarea>
        @error('message')
            <p class="err-msg"><i class="bi bi-exclamation-circle" aria-hidden="true"></i>{{ $message }}</p>
        @enderror

        <div class="formbar">
            <a class="btn" href="{{ route('admin.enquiries.index') }}">Cancel</a>
            <button class="btn pri" type="submit" wire:loading.attr="disabled" wire:target="save">Create enquiry</button>
        </div>
    </div>
</form>
