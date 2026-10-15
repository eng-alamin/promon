<x-site.layout title="FAQ" description="Quick answers about ordering, payment, delivery and getting in touch." nav="faq">
  <section class="page-head">
    <div class="wrap">
      <p class="eyebrow">FAQ</p>
      <h1>Frequently asked questions.</h1>
      <p class="lead">Quick answers about ordering, payment, delivery and getting in touch.</p>
    </div>
  </section>
  <section class="page-body">
    <div class="wrap faq" style="max-width: 820px">
      @forelse ($faqs as $faq)
        <details name="faq">
          <summary>{{ $faq->question }}</summary>
          <div class="ans">{{ $faq->answer }}</div>
        </details>
      @empty
        <p class="lead" style="margin-top:0">No questions have been added yet. Please <a class="link-gold" href="{{ route('site.home') }}#contact">contact us</a>.</p>
      @endforelse
      <p class="lead">Cannot find your answer? <a class="link-gold" href="{{ route('site.home') }}#contact">Contact us</a>.</p>
    </div>
  </section>
</x-site.layout>
