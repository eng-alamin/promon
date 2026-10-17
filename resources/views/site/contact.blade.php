@php
    $phoneBd = '+880 1676 213722';
    $mapQuery = urlencode('House 2/A, Road 13, Sector 4, Uttara, Dhaka 1230, Bangladesh');
@endphp
<x-site.layout title="Contact" description="Contact PROMON LTD in the UK or K Pharma in Uttara, Dhaka. Email, WhatsApp and phone details for UK product enquiries." nav="contact">
  <section class="page-head">
    <div class="wrap">
      <p class="eyebrow">Contact</p>
      <h1>Let's work together.</h1>
      <p class="lead">Message us on WhatsApp or send an email. To enquire about products, choose your categories on the enquiry page.</p>
    </div>
  </section>

  <section class="section">
    <div class="wrap">
      <div class="contact">
        <div>
          <dl>
            <div class="row"><dt>United Kingdom</dt><dd>PROMON LTD<br>Company number 13358479</dd></div>
            <div class="row"><dt>Bangladesh</dt><dd>K Pharma (sister concern, since 2006)<br>House 2/A, Road 13, Sector 4, Uttara, Dhaka 1230<br><a href="tel:+8801676213722">{{ $phoneBd }}</a></dd></div>
            <div class="row"><dt>Direct</dt><dd>
              <a href="mailto:{{ $email }}">{{ $email }}</a>
              @if (strlen($waDigits) >= 8)<br>WhatsApp: <a href="https://wa.me/{{ $waDigits }}" target="_blank" rel="noopener">{{ $whatsapp }}<span class="sr-only"> (opens in a new tab)</span></a>@endif
            </dd></div>
          </dl>
        </div>
        <div class="cta-box">
          <h3>Which UK product do you need?</h3>
          <p>Choose your categories and send the enquiry on WhatsApp. Or message us with a photo and the details to get a price.</p>
          <a class="btn btn--gold" href="{{ route('site.enquiry') }}">Send an enquiry</a>
          @if (strlen($waDigits) >= 8)<a class="btn btn--line" href="https://wa.me/{{ $waDigits }}" target="_blank" rel="noopener">WhatsApp<span class="sr-only"> (opens in a new tab)</span></a>@endif
        </div>
      </div>
    </div>
  </section>

  <section class="section dhaka">
    <div class="wrap">
      <div class="box">
        <div>
          <p class="eyebrow">Visit us in Dhaka</p>
          <h2>K Pharma</h2>
          <p>Our sister concern store in Uttara Model Town, Dhaka, Bangladesh.</p>
          <p class="since">Since 2006</p>
          <address>House 2/A, Road 13, Sector 4, Uttara,<br>Dhaka 1230, Bangladesh<br><a href="tel:+8801676213722">{{ $phoneBd }}</a></address>
          <a class="btn btn--gold btn--sm" target="_blank" rel="noopener" href="https://www.google.com/maps/search/?api=1&amp;query={{ $mapQuery }}">Open in Google Maps<span class="sr-only"> (opens in a new tab)</span></a>
        </div>
        <iframe class="map" title="Map of K Pharma, Uttara, Dhaka" loading="lazy" referrerpolicy="no-referrer-when-downgrade" src="https://www.google.com/maps?q={{ $mapQuery }}&amp;output=embed"></iframe>
      </div>
    </div>
  </section>
</x-site.layout>