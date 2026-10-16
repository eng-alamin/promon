@php
    $selected = collect(old('categories', $preselected))->all();
    $chosenType = old('type', $defaultType);
@endphp
<x-site.layout title="Enquiry" description="Choose the UK product categories you need and send an enquiry to PROMON LTD. We reply with products and a wholesale quote." nav="enquiry">
  <section class="page-head">
    <div class="wrap">
      <p class="eyebrow">Enquiry</p>
      <h1>Tell us what you need.</h1>
      <p class="lead">Choose one or more categories, then tell us the products and quantities. There is no online checkout. We reply with available products and a quote.</p>
    </div>
  </section>

  <section class="page-body">
    <div class="wrap" style="max-width: 900px">
      @if ($errors->any())
        <div class="alert" role="alert">
          <strong>Please check the form:</strong>
          <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
      @endif

      <form method="POST" action="{{ route('site.enquiry.submit') }}" novalidate data-enquiry-form>
        @csrf
        <div class="hp" aria-hidden="true"><label>Website<input type="text" name="website" tabindex="-1" autocomplete="off" value=""></label></div>

        <fieldset style="border:0;padding:0;margin:0 0 40px">
          <legend class="legend">Categories <span class="req">*</span></legend>
          <div class="check-grid">
            @foreach ($categories as $category)
              <label class="check">
                <input type="checkbox" name="categories[]" value="{{ $category->slug }}" @checked(in_array($category->slug, $selected, true))>
                <span>{{ $category->name }}</span>
              </label>
            @endforeach
          </div>
          @error('categories')<p class="err">{{ $message }}</p>@enderror
        </fieldset>

        <div class="form-grid">
          <div class="field">
            <label for="business_name">Business name <span class="req">*</span></label>
            <input id="business_name" name="business_name" type="text" value="{{ old('business_name') }}" maxlength="150" required autocomplete="organization">
            @error('business_name')<p class="err">{{ $message }}</p>@enderror
          </div>
          <div class="field">
            <label for="contact_name">Your name <span class="req">*</span></label>
            <input id="contact_name" name="contact_name" type="text" value="{{ old('contact_name') }}" maxlength="100" required autocomplete="name">
            @error('contact_name')<p class="err">{{ $message }}</p>@enderror
          </div>
          <div class="field">
            <label for="email">Email</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" maxlength="150" autocomplete="email">
            @error('email')<p class="err">{{ $message }}</p>@enderror
          </div>
          <div class="field">
            <label for="whatsapp">WhatsApp</label>
            <input id="whatsapp" name="whatsapp" type="tel" value="{{ old('whatsapp') }}" maxlength="30" autocomplete="tel">
            @error('whatsapp')<p class="err">{{ $message }}</p>@enderror
          </div>
          <div class="field">
            <label for="phone">Phone</label>
            <input id="phone" name="phone" type="tel" value="{{ old('phone') }}" maxlength="30">
            @error('phone')<p class="err">{{ $message }}</p>@enderror
          </div>
          <div class="field">
            <label for="type">I am a <span class="req">*</span></label>
            <select id="type" name="type" required>
              @foreach ($types as $type)
                <option value="{{ $type->value }}" @selected($chosenType === $type->value)>{{ $type->label() }}</option>
              @endforeach
            </select>
            @error('type')<p class="err">{{ $message }}</p>@enderror
          </div>
          <div class="field">
            <label for="city">City</label>
            <input id="city" name="city" type="text" value="{{ old('city') }}" maxlength="100" autocomplete="address-level2">
            @error('city')<p class="err">{{ $message }}</p>@enderror
          </div>
          <div class="field">
            <label for="country">Country</label>
            <input id="country" name="country" type="text" value="{{ old('country') }}" maxlength="100" autocomplete="country-name">
            @error('country')<p class="err">{{ $message }}</p>@enderror
          </div>
          <div class="field field--full">
            <label for="message">Your enquiry</label>
            <textarea id="message" name="message" maxlength="2000" placeholder="Product names, brands, quantities, or anything else we should know.">{{ old('message') }}</textarea>
            @error('message')<p class="err">{{ $message }}</p>@enderror
          </div>
        </div>

        <p class="lead" style="font-size:.85rem;margin-top:24px">Give at least one of email, WhatsApp or phone so we can reply.</p>
        <div style="display:flex;flex-wrap:wrap;gap:12px;margin-top:26px">
          @if (strlen($waDigits) >= 8)
            <button type="button" class="btn btn--gold" data-wa-send data-wa-number="{{ $waDigits }}">Send on WhatsApp</button>
          @endif
          <button type="submit" class="btn {{ strlen($waDigits) >= 8 ? 'btn--line' : 'btn--gold' }}">Send by form</button>
        </div>
        <p class="err" data-wa-error role="alert" hidden>Please choose at least one category first.</p>
      </form>
    </div>
  </section>
</x-site.layout>
