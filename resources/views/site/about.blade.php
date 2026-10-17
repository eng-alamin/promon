<x-site.layout title="About" description="PROMON LTD sources, supplies and delivers original UK products to Bangladesh and customers worldwide. Retail, wholesale, gifts and pre-orders. Since 2021." nav="about">
    @php
        $waShown = $whatsapp ?? '';
        // Small line icons for the About section (same style as the category tiles).
        $ai = [
            'supplements' => '<path d="M10.5 20.5 3.5 13.5a4.95 4.95 0 0 1 7-7l7 7a4.95 4.95 0 0 1-7 7Z"/><path d="m8.5 8.5 7 7"/>',
            'baby' => '<path d="M9 3h6v3H9z"/><path d="M8 6h8v3a3 3 0 0 1-1 2.2V20a1 1 0 0 1-1 1h-4a1 1 0 0 1-1-1v-8.8A3 3 0 0 1 8 9z"/>',
            'grocery' => '<circle cx="9" cy="20" r="1.4"/><circle cx="18" cy="20" r="1.4"/><path d="M2 3h3l2.7 12.4a1 1 0 0 0 1 .8h8.6a1 1 0 0 0 1-.8L20 8H6"/>',
            'beauty' => '<rect x="4" y="9" width="6" height="12" rx="1"/><path d="M5.5 9V6h3v3M14 21V11h6v10zM15.5 11V8h3v3"/>',
            'care' => '<path d="M12 3s6 6.2 6 10.5a6 6 0 0 1-12 0C6 9.2 12 3 12 3Z"/>',
            'fashion' => '<path d="M6 8h12l1 13H5z"/><path d="M9 8V6a3 3 0 0 1 6 0v2"/>',
            'perfume' => '<rect x="6" y="10" width="12" height="11" rx="1"/><path d="M10 10V7h4v3M11 7V4h2v3"/>',
            'gift' => '<rect x="3" y="8" width="18" height="4"/><path d="M12 8v13M5 12v9h14v-9M8 8a2.5 2.5 0 1 1 4-2 2.5 2.5 0 1 1 4 2"/>',
            'home' => '<path d="M3 11.5 12 4l9 7.5"/><path d="M5 10v10h14V10"/><path d="M10 20v-6h4v6"/>',
            'electronics' => '<rect x="7" y="2.5" width="10" height="19" rx="2"/><path d="M11 18.5h2"/>',
            'medicine' => '<path d="M12 21s-8-5-8-11a4.5 4.5 0 0 1 8-2.8A4.5 4.5 0 0 1 20 10c0 6-8 11-8 11Z"/><path d="M7 12h3l1.5-3 2 6 1.5-3h2"/>',
            'other' => '<circle cx="12" cy="12" r="9"/><path d="M12 8v8M8 12h8"/>',
            'user' => '<circle cx="12" cy="8" r="3.5"/><path d="M5 21a7 7 0 0 1 14 0"/>',
            'shop' => '<path d="M4 9 5.5 4h13L20 9"/><path d="M4 9a2.7 2.7 0 0 0 5.3 0 2.7 2.7 0 0 0 5.4 0A2.7 2.7 0 0 0 20 9"/><path d="M5 12v8h14v-8"/>',
            'boxes' => '<path d="M3 8.5 12 4l9 4.5v7L12 20l-9-4.5z"/><path d="M3 8.5 12 13l9-4.5M12 13v7"/>',
            'globe' => '<circle cx="12" cy="12" r="9"/><path d="M3 12h18M12 3c2.6 2.6 3.9 5.6 3.9 9S14.6 18.4 12 21c-2.6-2.6-3.9-5.6-3.9-9S9.4 5.6 12 3Z"/>',
        ];
        $categoriesAbout = [
            ['supplements', 'UK Health Supplements & Wellness Products', null],
            ['baby', 'Baby Food, Formula Milk & Baby Essentials', null],
            ['grocery', 'UK Groceries, Chocolates & Confectionery', null],
            ['beauty', 'Skincare, Cosmetics & Beauty Products', null],
            ['care', 'Personal Care & Hygiene Essentials', null],
            ['fashion', 'Shoes, Bags, Clothing & Fashion Accessories', null],
            ['perfume', 'Perfumes, Fragrances & Gift Sets', null],
            ['gift', 'Toys, Gifts & Lifestyle Products', null],
            ['home', 'Home & Household Essentials', null],
            ['electronics', 'Electronics & Accessories', null],
            ['medicine', 'Medicines & Healthcare Products', 'Subject to applicable import and export regulations'],
            ['other', 'Other UK Products Available on Request', null],
        ];
        $audiences = [
            ['user', 'Personal Shopping', 'Order your favourite UK products for personal use.'],
            ['gift', 'Gift Delivery', 'Send thoughtful gifts and authentic UK products to family, friends, and loved ones.'],
            ['shop', 'Retail Supply', 'Source original UK products for your shop, online store, or growing business.'],
            ['boxes', 'Wholesale & Bulk Supply', 'Competitive sourcing solutions for resellers, distributors, and established businesses.'],
            ['globe', 'International Orders', 'Need UK products delivered outside Bangladesh or the UK? Contact us to discuss available shipping destinations and arrangements.'],
        ];
        $shipping = [
            'Direct UK product sourcing',
            'Reliable packaging and cargo arrangements',
            'Genuine products from trusted UK suppliers',
            'Retail and wholesale order support',
            'UK product pre-order services',
            'Bangladesh delivery, generally within 10 working days, depending on product availability, shipping and customs clearance',
            'International shipping assistance on request',
        ];
    @endphp

  <section class="page-head">
    <div class="wrap">
      <p class="eyebrow">About</p>
      <h1>Your Trusted UK Shopping &amp; Global Delivery Partner.</h1>
      <p class="about-tag">Bringing Original UK Products to Bangladesh &amp; Beyond <i>&bull;</i> Since 2021</p>
      <p class="lead">Shop from the UK. We Deliver Worldwide!</p>
    </div>
  </section>

  <section class="section about-page">
    <div class="wrap">
      <div class="about-intro">
        <p>Looking for genuine UK products at competitive prices? Whether you are shopping for yourself, sending gifts to loved ones, running a retail business, or sourcing products in bulk, PROMON LTD is here to make UK shopping easier, safer, and more convenient.</p>
        <p>Established in 2021, PROMON LTD specialises in sourcing, supplying, and delivering original UK products to individual customers, retailers, wholesalers, and businesses.</p>
        <p>We currently focus on Bangladesh and the United Kingdom, with international sourcing and delivery assistance available for customers in other countries upon request.</p>
        <div class="company-card">
          <dl>
            <dt>Business name</dt><dd>PROMON LTD</dd>
            <dt>Company number</dt><dd>13358479</dd>
            <dt>Registered address</dt><dd>1A Wilmot Street, Bolton, Manchester, BL1 3LL, United Kingdom</dd>
          </dl>
        </div>
      </div>

      <div class="about-block">
        <h3>Our Products &amp; Categories</h3>
        <ul class="about-cats">
          @foreach ($categoriesAbout as [$key, $label, $sub])
            <li>
              <svg viewBox="0 0 24 24" aria-hidden="true">{!! $ai[$key] !!}</svg>
              <span>{{ $label }}@if ($sub)<small>{{ $sub }}</small>@endif</span>
            </li>
          @endforeach
        </ul>
        <p class="note">Can't find the product you're looking for? Simply send us the product name, photo, or website link. Our team can help source it directly from the UK.</p>
      </div>

      <div class="about-block">
        <h3>Retail, Wholesale &amp; Bulk Orders</h3>
        <p class="lead">At PROMON LTD, we serve customers of all sizes.</p>
        <ul class="about-who">
          @foreach ($audiences as [$key, $title, $text])
            <li>
              <svg viewBox="0 0 24 24" aria-hidden="true">{!! $ai[$key] !!}</svg>
              <h4>{{ $title }}</h4>
              <p>{{ $text }}</p>
            </li>
          @endforeach
        </ul>
      </div>

      <div class="story">
        <div>
          <h3>Proudly Serving Customers Since 2021</h3>
          <p>Since our establishment in 2021, PROMON LTD has been committed to connecting customers and businesses with quality UK products.</p>
          <p style="margin-top:12px">From serving customers in Dhaka to expanding our operations in Uttara, we continue to grow with a focus on customer satisfaction, dependable sourcing, and long-term business relationships.</p>
          <p style="margin-top:12px">Our vision is to become a trusted international bridge between UK suppliers and customers around the world, making genuine UK products accessible to everyone, from individual shoppers to large-scale wholesale buyers.</p>
        </div>
        <div>
          <h3>Direct UK to Bangladesh Shipping</h3>
          <p>We make international shopping straightforward with:</p>
          <ul style="margin-top:10px">
            @foreach ($shipping as $item)
              <li>{{ $item }}</li>
            @endforeach
          </ul>
        </div>
      </div>

      <div class="about-why">
        <h3>Why Choose PROMON LTD?</h3>
        <p class="about-why__line">One Trusted Partner. Thousands of UK Products. Global Possibilities.</p>
        <p>We believe international shopping should be accessible, transparent, and convenient. Whether you need one special gift, regular retail stock, or a wholesale shipment, we aim to provide a sourcing solution that meets your needs.</p>
      </div>

      <div class="about-cta">
        <div>
          <h3>Start Your UK Shopping Journey Today</h3>
          <p>Looking for a specific UK product? Need wholesale pricing? Want to send a gift or arrange an international shipment? Send us a message with your product details, quantity, and delivery destination for a personalised quotation.</p>
        </div>
        <div class="about-cta__btns">
          <a class="btn btn--gold" href="{{ route('site.enquiry') }}">Send an enquiry</a>
          @if (strlen($waDigits) >= 8)<a class="btn btn--line" href="https://wa.me/{{ $waDigits }}" target="_blank" rel="noopener">WhatsApp: {{ $waShown }}<span class="sr-only"> (opens in a new tab)</span></a>@endif
        </div>
      </div>
      <p class="about-sign"><b>PROMON LTD</b> &mdash; Your UK Shopping, Wholesale &amp; Worldwide Delivery Connection.<br>United Kingdom <i>&bull;</i> Bangladesh <i>&bull;</i> International Orders Welcome</p>
    </div>
  </section>
</x-site.layout>
