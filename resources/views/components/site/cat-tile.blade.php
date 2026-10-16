@props(['category', 'icons' => [], 'blurbs' => [], 'defaultIcon' => ''])
<li style="display:contents">
  <a class="cat" href="{{ route('site.enquiry', ['category' => $category->slug]) }}">
    <svg viewBox="0 0 24 24" aria-hidden="true">{!! $icons[$category->slug] ?? $defaultIcon !!}</svg>
    <span>
      <h3>{{ $category->name }}</h3>
      @if (isset($blurbs[$category->slug]))<p>{{ $blurbs[$category->slug] }}</p>@endif
      <em>Enquire &rarr;</em>
    </span>
  </a>
</li>
