@props(['review', 'limit' => null])
<figure class="review">
  <div class="stars" role="img" aria-label="Rated {{ $review->rating }} out of 5">
    <span aria-hidden="true">{{ str_repeat('★', $review->rating) }}<span class="off">{{ str_repeat('★', 5 - $review->rating) }}</span></span>
  </div>
  <blockquote>
    <p>{{ $limit ? \Illuminate\Support\Str::limit($review->body, $limit) : $review->body }}</p>
  </blockquote>
  <figcaption>
    <b>{{ $review->reviewer_name }}</b>
    @if ($review->reviewer_title)<span>{{ $review->reviewer_title }}</span>@endif
  </figcaption>
</figure>
