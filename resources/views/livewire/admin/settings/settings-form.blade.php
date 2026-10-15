<form class="stack" wire:submit="save" novalidate>
    <div class="card">
        <h2 class="sec">Company</h2>
        <p class="hint">These details are used on the website. Prices are always shown in £ (GBP).</p>

        <div class="fgrid">
            <x-admin.field name="company_name" id="st-company_name" label="Company name">
                <input class="f" id="st-company_name" type="text" wire:model="company_name" maxlength="120" required @error('company_name') aria-invalid="true" aria-describedby="st-company_name-error" @enderror>
            </x-admin.field>

            <x-admin.field name="tagline" id="st-tagline" label="Tagline">
                <input class="f" id="st-tagline" type="text" wire:model="tagline" maxlength="120" @error('tagline') aria-invalid="true" aria-describedby="st-tagline-error" @enderror>
            </x-admin.field>

            <x-admin.field name="delivery_time" id="st-delivery_time" label="Delivery time" hint="For example: 10 working days.">
                <input class="f" id="st-delivery_time" type="text" wire:model="delivery_time" maxlength="60" @error('delivery_time') aria-invalid="true" aria-describedby="st-delivery_time-error" @enderror>
            </x-admin.field>

            <x-admin.field name="working_hours" id="st-working_hours" label="Working hours">
                <input class="f" id="st-working_hours" type="text" wire:model="working_hours" maxlength="160" @error('working_hours') aria-invalid="true" aria-describedby="st-working_hours-error" @enderror>
            </x-admin.field>
        </div>
    </div>

    <div class="card">
        <h2 class="sec">Contact</h2>

        <div class="fgrid">
            <x-admin.field name="whatsapp" id="st-whatsapp" label="WhatsApp number" hint="With the country code, for example +447405124730. Customers open this number from the website.">
                <input class="f" id="st-whatsapp" type="text" inputmode="tel" wire:model="whatsapp" maxlength="20" @error('whatsapp') aria-invalid="true" aria-describedby="st-whatsapp-error" @enderror>
            </x-admin.field>

            <x-admin.field name="phone" id="st-phone" label="Phone">
                <input class="f" id="st-phone" type="text" inputmode="tel" wire:model="phone" maxlength="30" @error('phone') aria-invalid="true" aria-describedby="st-phone-error" @enderror>
            </x-admin.field>

            <x-admin.field name="email" id="st-email" label="Email" :span="true">
                <input class="f" id="st-email" type="email" wire:model="email" maxlength="255" @error('email') aria-invalid="true" aria-describedby="st-email-error" @enderror>
            </x-admin.field>

            <x-admin.field name="address" id="st-address" label="Address" :span="true">
                <textarea class="f" id="st-address" rows="3" wire:model="address" maxlength="500" @error('address') aria-invalid="true" aria-describedby="st-address-error" @enderror></textarea>
            </x-admin.field>
        </div>
    </div>

    <div class="formbar">
        <button class="btn pri" type="submit" wire:loading.attr="disabled" wire:target="save">Save settings</button>
    </div>
</form>
