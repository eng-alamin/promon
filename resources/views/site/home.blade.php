@php
    // Small line icons for the category tiles. A category not listed here gets the default tag icon.
    $icons = [
        'supplements' => '<path d="M10.5 20.5 3.5 13.5a4.95 4.95 0 0 1 7-7l7 7a4.95 4.95 0 0 1-7 7Z"/><path d="m8.5 8.5 7 7"/>',
        'medicine' => '<path d="M10.5 20.5 3.5 13.5a4.95 4.95 0 0 1 7-7l7 7a4.95 4.95 0 0 1-7 7Z"/><path d="m8.5 8.5 7 7"/>',
        'baby-food-milk' => '<path d="M9 3h6v3H9z"/><path d="M8 6h8v3a3 3 0 0 1-1 2.2V20a1 1 0 0 1-1 1h-4a1 1 0 0 1-1-1v-8.8A3 3 0 0 1 8 9z"/>',
        'grocery' => '<circle cx="9" cy="20" r="1.4"/><circle cx="18" cy="20" r="1.4"/><path d="M2 3h3l2.7 12.4a1 1 0 0 0 1 .8h8.6a1 1 0 0 0 1-.8L20 8H6"/>',
        'nappies' => '<path d="M3 6h18v4c0 5-3 8-6 8h-1.5L12 15.5 10.5 18H9c-3 0-6-3-6-8z"/>',
        'chocolate' => '<rect x="4" y="3" width="16" height="18" rx="1"/><path d="M4 9h16M4 15h16M10 3v18M15 3v18"/>',
        'gifts' => '<rect x="3" y="8" width="18" height="4"/><path d="M12 8v13M5 12v9h14v-9M8 8a2.5 2.5 0 1 1 4-2 2.5 2.5 0 1 1 4 2"/>',
        'health-products' => '<path d="M12 21s-8-5-8-11a4.5 4.5 0 0 1 8-2.8A4.5 4.5 0 0 1 20 10c0 6-8 11-8 11Z"/><path d="M7 12h3l1.5-3 2 6 1.5-3h2"/>',
        'beauty' => '<rect x="4" y="9" width="6" height="12" rx="1"/><path d="M5.5 9V6h3v3M14 21V11h6v10zM15.5 11V8h3v3"/>',
        'shoes-bags' => '<path d="M6 8h12l1 13H5z"/><path d="M9 8V6a3 3 0 0 1 6 0v2"/>',
    ];
    $blurbs = [
        'supplements' => 'Vitamins and health supplements',
        'medicine' => 'OTC medicine, subject to import rules',
        'baby-food-milk' => 'Formula milk and baby food',
        'grocery' => 'British food and pantry items',
        'nappies' => 'Pampers and baby care in every size',
        'chocolate' => 'Classic British chocolates',
        'gifts' => 'Gifts and accessories for every occasion',
        'health-products' => 'Personal care and wellbeing',
        'beauty' => 'Skincare and cosmetics',
        'shoes-bags' => 'Fashion and lifestyle',
    ];
    $defaultIcon = '<path d="M20.6 13.4 13.4 20.6a2 2 0 0 1-2.8 0L3 13V3h10l7.6 7.6a2 2 0 0 1 0 2.8Z"/><circle cx="7.5" cy="7.5" r="1.2"/>';
    $phoneBd = '+880 1676 213722';
    $waShown = $whatsapp ?? '';
@endphp
<x-site.layout title="" description="PROMON LTD sources genuine UK products for wholesalers and retailers in Bangladesh. Supplements, baby food, groceries, beauty and more. Direct UK to Bangladesh air cargo." nav="home">

  <section class="hero" id="top">
    <div class="wrap">
      <p class="eyebrow">UK Sourcing &nbsp;|&nbsp; Bangladesh Wholesale</p>
      <h1>Your UK Products. Your Business. Delivered in 10 Working Days.</h1>
      <p class="sub">Grow your business with genuine UK-sourced products. PROMON LTD makes wholesale sourcing simple for retailers across Bangladesh &mdash; from everyday essentials to in-demand international brands.</p>
      <div class="actions">
        <a href="#products" class="btn btn--gold">Explore UK Products</a>
        <a href="{{ route('site.enquiry') }}" class="btn btn--line">Get a Wholesale Quote</a>
      </div>
      <p class="strip">UK-Sourced Products <i>&bull;</i> Wholesale Orders <i>&bull;</i> Direct UK to Bangladesh Delivery</p>
    </div>
  </section>

  <section class="facts" aria-label="Key facts">
    <div class="wrap">
      <ul>
        <li><b>Direct UK to Bangladesh</b><span>Air cargo service</span></li>
        <li><b>Wholesale &amp; Retail</b><span>Orders available</span></li>
        <li><b>Up to 10 working days</b><span>Depends on product and customs</span></li>
        <li><b>Serving Bangladesh</b><span>Since 2021</span></li>
      </ul>
    </div>
  </section>

  <section class="section" id="about">
    <div class="wrap">
      <div class="about">
        <div class="logo-ring"><img src="{{ asset('assets/site/img/logo.jpeg') }}" width="320" height="320" alt="PROMON LTD - UK Products | Bangladesh - Bringing You the Best" loading="lazy"></div>
        <div class="copy">
          <p class="eyebrow">01 &mdash; About</p>
          <h2>Your Trusted UK Shopping &amp; Global Delivery Partner.</h2>
          <p class="about-tag">Bringing Original UK Products to Bangladesh &amp; Beyond <i>&bull;</i> Since 2021</p>
          <p style="margin-top:20px">Looking for genuine UK products at competitive prices? Whether you are shopping for yourself, sending gifts to loved ones, running a retail business, or sourcing products in bulk, PROMON LTD is here to make UK shopping easier, safer, and more convenient.</p>
          <p>Established in 2021, PROMON LTD specialises in sourcing, supplying, and delivering original UK products to individual customers, retailers, wholesalers, and businesses.</p>
          <p class="about-more"><a class="link-gold" href="{{ route('site.about') }}">More &hellip;<span class="sr-only"> about PROMON LTD</span></a></p>
        </div>
      </div>
    </div>
  </section>

  <section class="section section--alt" id="products">
    <div class="wrap">
      <p class="eyebrow">02 &mdash; Products</p>
      <h2>What we sell.</h2>
      <p class="lead">Choose a category and send us an enquiry. We reply with the available products, quantities and a wholesale quote.</p>

      @php
        $first = $categories->take(12);
        $rest = $categories->slice(12);
      @endphp
      @if ($categories->isEmpty())
        <ul class="cats">
          <li class="cat" style="grid-column:1/-1"><span><h3>Categories coming soon</h3><p>Please contact us with the product you need.</p></span></li>
        </ul>
      @else
        <ul class="cats">
          @foreach ($first as $category)
            <x-site.cat-tile :category="$category" :icons="$icons" :blurbs="$blurbs" :default-icon="$defaultIcon" />
          @endforeach
        </ul>
        @if ($rest->isNotEmpty())
          <details class="cats-more">
            <summary><span class="when-closed">Show all {{ $categories->count() }} categories</span><span class="when-open">Show fewer</span></summary>
            <ul class="cats cats--more">
              @foreach ($rest as $category)
                <x-site.cat-tile :category="$category" :icons="$icons" :blurbs="$blurbs" :default-icon="$defaultIcon" />
              @endforeach
            </ul>
          </details>
        @endif
      @endif

      <p class="note">Your product is not in the list? Tell us what you need. We can pre-order it from the UK. Medicines are supplied subject to the import rules that apply.</p>
    </div>
  </section>

  <section class="section" id="services">
    <div class="wrap">
      <p class="eyebrow">03 &mdash; Services</p>
      <h2>Wholesale in the UK. Export to Bangladesh.</h2>
      <div class="services">
        <div>
          <p class="eyebrow">United Kingdom</p>
          <h3>Wholesale supply</h3>
          <p class="t">Bulk supply for shops, pharmacies and retailers at wholesale prices.</p>
          <a class="link-gold" href="{{ route('site.enquiry', ['type' => 'wholesaler']) }}">Get a wholesale quote &rarr;</a>
        </div>
        <div>
          <p class="eyebrow">Bangladesh</p>
          <h3>Export on request</h3>
          <p class="t">Genuine UK products sourced, packed and shipped by air cargo.</p>
          <a class="link-gold" href="{{ route('site.enquiry') }}">Ask about export &rarr;</a>
        </div>
      </div>
    </div>
  </section>

  <section class="section section--alt" id="how">
    <div class="wrap">
      <p class="eyebrow">04 &mdash; How it works</p>
      <h2>Four simple steps.</h2>
      <ol class="steps">
        <li><span class="n">1</span><h3>Browse</h3><p>Look through our product categories and pick what your business needs.</p></li>
        <li><span class="n">2</span><h3>Enquiry</h3><p>Send an enquiry with the categories and quantities. We reply with products and a quote.</p></li>
        <li><span class="n">3</span><h3>Payment</h3><p>Pay by bank transfer, TT or LC from Bangladesh. Details are below.</p></li>
        <li><span class="n">4</span><h3>Delivery</h3><p>We send your order from the UK by air cargo, usually within 10 working days.</p></li>
      </ol>
    </div>
  </section>

  <section class="section" id="payment">
    <div class="wrap">
      <p class="eyebrow">05 &mdash; Payment</p>
      <h2>Pay from Bangladesh or the UK.</h2>
      <p class="lead">Customers in Bangladesh can pay to our UK bank account in any of these ways.</p>
      <div class="pay">
        <ul class="methods">
          <li><b>TT</b><div><h3>Telegraphic Transfer (TT)</h3><p>Send the payment by TT from your bank in Bangladesh.</p></div></li>
          <li><b>BT</b><div><h3>Bank transfer</h3><p>Transfer directly from your Bangladesh bank account to our account.</p></div></li>
          <li><b>LC</b><div><h3>Letter of Credit (LC)</h3><p>Open an LC through your bank and send the payment for your order.</p></div></li>
        </ul>
      </div>
    </div>
  </section>

  <section class="section dhaka" id="dhaka">
    <div class="wrap">
      <div class="box">
        <div>
          <p class="eyebrow">06 &mdash; Visit us in Dhaka</p>
          <h2>K Pharma</h2>
          <p>Our sister concern store in Uttara Model Town, Dhaka, Bangladesh.</p>
          <p class="since">Since 2006</p>
          <address>House 2/A, Road 13, Sector 4, Uttara,<br>Dhaka 1230, Bangladesh<br><a href="tel:+8801676213722">{{ $phoneBd }}</a></address>
          <a class="btn btn--gold btn--sm" target="_blank" rel="noopener" href="https://www.google.com/maps/search/?api=1&amp;query={{ urlencode('House 2/A, Road 13, Sector 4, Uttara, Dhaka 1230, Bangladesh') }}">Open in Google Maps<span class="sr-only"> (opens in a new tab)</span></a>
        </div>
        <iframe class="map" title="Map of K Pharma, Uttara, Dhaka" loading="lazy" referrerpolicy="no-referrer-when-downgrade" src="https://www.google.com/maps?q={{ urlencode('House 2/A, Road 13, Sector 4, Uttara, Dhaka 1230, Bangladesh') }}&amp;output=embed"></iframe>
      </div>
    </div>
  </section>

  @if ($reviews->isNotEmpty())
    <section class="section section--alt" id="reviews">
      <div class="wrap">
        <p class="eyebrow">07 &mdash; Reviews</p>
        <h2>What our customers say.</h2>
        <div class="reviews">
          @foreach ($reviews as $review)
            <x-site.review-card :review="$review" :limit="190" />
          @endforeach
        </div>
        <p class="about-more">
          @if ($reviewCount > $reviews->count())
            <a class="link-gold" href="{{ route('site.reviews') }}">More reviews &hellip;<span class="sr-only"> from our customers</span></a>
            &nbsp;&middot;&nbsp;
          @endif
          <a class="link-gold" href="{{ route('site.reviews') }}#write-review">Write a review</a>
        </p>
      </div>
    </section>
  @endif

  <section class="section" id="contact">
    <div class="wrap">
      <div class="contact">
        <div>
          <p class="eyebrow">08 &mdash; Contact</p>
          <h2>Let's work together.</h2>
          <p class="lead">Address, email, phone and WhatsApp are on our contact page.</p>
          <a class="link-gold" href="{{ route('site.contact') }}">Open the contact page &rarr;</a>
        </div>
        <div class="cta-box">
          <h3>Which UK product do you need?</h3>
          <p>Choose your categories and send us the enquiry on WhatsApp or by form.</p>
          <a class="btn btn--gold" href="{{ route('site.enquiry') }}">Send an enquiry</a>
          @if (strlen($waDigits) >= 8)<a class="btn btn--line" href="https://wa.me/{{ $waDigits }}" target="_blank" rel="noopener">WhatsApp<span class="sr-only"> (opens in a new tab)</span></a>@endif
        </div>
      </div>
    </div>
  </section>
</x-site.layout>