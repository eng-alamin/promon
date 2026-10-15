<x-site.layout title="Page not found" description="" nav="">
  <section class="page-head">
    <div class="wrap">
      <p class="eyebrow">Error 404</p>
      <h1>Page not found</h1>
      <p class="lead">The page you asked for does not exist or has moved. Check the address, or use one of the links below.</p>
    </div>
  </section>
  <section class="page-body">
    <div class="wrap">
      <a class="btn btn--gold" href="{{ route('site.home') }}">Go to the home page</a>
      <a class="btn btn--line" href="{{ route('site.enquiry') }}">Send an enquiry</a>
    </div>
  </section>
</x-site.layout>
