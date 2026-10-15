<x-site.layout title="Enquiry sent" description="Your enquiry has been received." nav="enquiry">
  <section class="page-head">
    <div class="wrap">
      <p class="eyebrow">Enquiry sent</p>
      <h1>Thank you.</h1>
    </div>
  </section>
  <section class="page-body">
    <div class="wrap" style="max-width: 760px">
      <p class="lead" style="margin-top:0">Your reference is <strong style="color:var(--gold-soft)">{{ $reference }}</strong>. We will reply with products and a quote using the contact details you gave. Nothing has been ordered or charged.</p>
      <p style="margin-top:32px"><a href="{{ route('site.home') }}" class="btn btn--gold">Back to home</a></p>
    </div>
  </section>
</x-site.layout>
