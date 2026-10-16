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

      <section class="review-form" id="write-review" aria-labelledby="write-review-title">
        <p class="eyebrow">Share your experience</p>
        <h2 id="write-review-title">Write a review.</h2>
        <p class="lead" style="margin-top:12px">Tell us about your experience with PROMON LTD. Your review will appear on this page after our team approves it.</p>

        @if (session('review_submitted'))
          <div class="notice" role="status">Thank you! Your review has been received. It will appear here after our team approves it.</div>
        @endif

        @if ($errors->any())
          <div class="alert" role="alert" style="margin-top:24px">
            <strong>Please check the form:</strong>
            <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
          </div>
        @endif

        <form method="POST" action="{{ route('site.reviews.submit') }}" novalidate>
          @csrf
          <div class="hp" aria-hidden="true"><label>Website<input type="text" name="website" tabindex="-1" autocomplete="off" value=""></label></div>
          <div class="form-grid">
            <div class="field">
              <label for="reviewer_name">Your name <span class="req">*</span></label>
              <input id="reviewer_name" name="reviewer_name" type="text" value="{{ old('reviewer_name') }}" maxlength="120" required autocomplete="name">
              @error('reviewer_name')<p class="err">{{ $message }}</p>@enderror
            </div>
            <div class="field">
              <label for="reviewer_title">Business or city</label>
              <input id="reviewer_title" name="reviewer_title" type="text" value="{{ old('reviewer_title') }}" maxlength="160" placeholder="e.g. Retailer, Dhaka">
              @error('reviewer_title')<p class="err">{{ $message }}</p>@enderror
            </div>
            <div class="field">
              <label for="rating">Rating <span class="req">*</span></label>
              <select id="rating" name="rating" required>
                @foreach ([5 => 'Excellent', 4 => 'Very good', 3 => 'Good', 2 => 'Fair', 1 => 'Poor'] as $value => $label)
                  <option value="{{ $value }}" @selected((int) old('rating', 5) === $value)>{{ $value }} &mdash; {{ $label }}</option>
                @endforeach
              </select>
              @error('rating')<p class="err">{{ $message }}</p>@enderror
            </div>
            <div class="field field--full">
              <label for="body">Your review <span class="req">*</span></label>
              <textarea id="body" name="body" maxlength="1000" required placeholder="What did you order and how was the service?">{{ old('body') }}</textarea>
              @error('body')<p class="err">{{ $message }}</p>@enderror
            </div>
          </div>
          <div style="margin-top:26px">
            <button type="submit" class="btn btn--gold">Submit review</button>
          </div>
        </form>
      </section>

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
