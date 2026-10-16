<?php

declare(strict_types=1);

namespace App\Services\Site;

use App\Models\Review;

/**
 * Saves a review sent from the public website. It is saved as inactive,
 * so it stays hidden until an admin turns it on in the admin panel.
 */
class ReviewSubmissionService
{
    /**
     * @param  array{reviewer_name: string, reviewer_title?: ?string, rating: int|string, body: string}  $data
     */
    public function submit(array $data): Review
    {
        $title = trim((string) ($data['reviewer_title'] ?? ''));

        return Review::create([
            'reviewer_name' => trim($data['reviewer_name']),
            'reviewer_title' => $title !== '' ? $title : null,
            'rating' => (int) $data['rating'],
            'body' => trim($data['body']),
            'sort_order' => min(((int) Review::max('sort_order')) + 1, 65535),
            'is_active' => false,
        ]);
    }
}
