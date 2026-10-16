<?php

declare(strict_types=1);

namespace App\Services\Site;

use App\Models\Review;
use Illuminate\Database\Eloquent\Collection;

/**
 * Read-only queries for the public site. Only active reviews are shown.
 */
class ReviewListingService
{
    /**
     * The first few reviews for the home page.
     *
     * @return Collection<int, Review>
     */
    public function featured(int $limit = 3): Collection
    {
        return Review::active()->ordered()->limit($limit)->get();
    }

    /**
     * Every active review for the Reviews page.
     *
     * @return Collection<int, Review>
     */
    public function all(): Collection
    {
        return Review::active()->ordered()->get();
    }

    public function count(): int
    {
        return Review::active()->count();
    }

    /**
     * Average rating of the active reviews, one decimal place. Null when there are none.
     */
    public function average(): ?float
    {
        $average = Review::active()->avg('rating');

        return $average === null ? null : round((float) $average, 1);
    }
}
