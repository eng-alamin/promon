<x-site.layout title="Reviews" description="Read what retailers, wholesalers and personal customers say about PROMON LTD and our UK to Bangladesh sourcing service." nav="reviews">
  <section class="page-head">
    <div class="wrap">
      <p class="eyebrow">Reviews</p>
      <h1>What our customers say.</h1>
      @if ($average !== null)
        <p class="lead">{{ number_format($average, 1) }} out of 5 &middot; {{ $reviews->count() }} {{ $reviews->count() === 1 ? 'review' : 'reviews' }}</p>
      @else
        <p class="lead">Feedback from retailers, wholesalers and personal customers.</p>
      @endif
    </div>
  </section>

  <section class="page-body">
    <div class="wrap">
      @forelse ($reviews as $review)
        @if ($loop->first)<div class="reviews">@endif
        <x-site.review-card :review="$review" />
        @if ($loop->last)</div>@endif
      @empty
        <p class="lead" style="margin-top:0">No reviews have been added yet. Please <a class="link-gold" href="{{ route('site.contact') }}">contact us</a>.</p>
      @endforelse

      <div class="about-cta" style="margin-top:48px">
        <div>
          <h3>Ready to order from the UK?</h3>
          <p>Send us the product name, quantity and delivery destination and we will reply with the details.</p>
        </div>
        <div class="about-cta__btns">
          <a class="btn btn--gold" href="{{ route('site.enquiry') }}">Send an enquiry</a>
          @if (strlen($waDigits) >= 8)<a class="btn btn--line" href="https://wa.me/{{ $waDigits }}" target="_blank" rel="noopener">WhatsApp<span class="sr-only"> (opens in a new tab)</span></a>@endif
        </div>
      </div>
    </div>
  </section>
</x-site.layout>
