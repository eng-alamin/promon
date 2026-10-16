<?php

declare(strict_types=1);

namespace App\Services\Admin;

use App\Models\Review;

class ReviewService
{
    /**
     * Create a review, or update it when one is given.
     *
     * @param  array{reviewer_name: string, reviewer_title: ?string, rating: int|string, body: string, sort_order: int|string, is_active: bool}  $data
     */
    public function save(?Review $review, array $data): Review
    {
        $title = trim((string) ($data['reviewer_title'] ?? ''));

        $payload = [
            'reviewer_name' => trim($data['reviewer_name']),
            'reviewer_title' => $title !== '' ? $title : null,
            'rating' => (int) $data['rating'],
            'body' => trim($data['body']),
            'sort_order' => (int) $data['sort_order'],
            'is_active' => (bool) $data['is_active'],
        ];

        if ($review === null) {
            return Review::create($payload);
        }

        $review->update($payload);

        return $review;
    }

    public function toggleActive(Review $review): void
    {
        $review->update(['is_active' => ! $review->is_active]);
    }

    public function delete(Review $review): void
    {
        $review->delete();
    }

    /**
     * The sort order a new review gets so that it appears last.
     */
    public function nextSortOrder(): int
    {
        return min(((int) Review::max('sort_order')) + 1, 65535);
    }
}
