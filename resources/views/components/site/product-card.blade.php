@props(['product'])
@php
    $image = $product->primaryImage?->url;
    $letter = mb_substr($product->category?->name ?? $product->name, 0, 1);
    $href = route('site.product', $product->slug);
@endphp
<li>
  <article class="card card-hover flex h-full flex-col overflow-hidden">
    <a href="{{ $href }}" class="block" tabindex="-1" aria-hidden="true">
      <div class="relative aspect-4/3 overflow-hidden bg-brand-50" aria-hidden="true">
        <span class="grid size-full place-items-center font-display text-4xl text-brand-100">{{ $letter }}</span>
        @if ($image)
          <img src="{{ $image }}" alt="" loading="lazy" decoding="async" class="absolute inset-0 size-full object-cover" onerror="this.remove()">
        @endif
      </div>
    </a>
    <div class="flex flex-1 flex-col p-4">
      <p class="flex flex-wrap items-center gap-2 text-xs font-semibold uppercase tracking-wide text-muted">
        <span>{{ $product->category?->name }}</span>
        @if ($product->is_new)<span class="rounded-full bg-accent-500 px-2 py-0.5 text-canvas">New</span>@endif
      </p>
      <h3 class="mt-1 flex-1 text-base leading-snug"><a class="hover:underline" href="{{ $href }}">{{ $product->name }}</a></h3>
      <p class="mt-3"><span class="sr-only">Price: </span><span class="text-lg font-bold text-ink">£{{ number_format((float) $product->price, 2) }}</span></p>
      <p class="mt-1 text-sm text-muted">
        @if ($product->moq) Min. order: {{ $product->moq }} · @endif{{ $product->status->label() }}
      </p>
      <button type="button" class="btn-ghost mt-4 min-h-11 text-center" data-enquiry-id="{{ $product->id }}" data-enquiry-name="{{ $product->name }}">Add to enquiry list</button>
    </div>
  </article>
</li>
