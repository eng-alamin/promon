<?php

declare(strict_types=1);

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Http\Requests\Site\StoreReviewRequest;
use App\Services\Site\ReviewSubmissionService;
use Illuminate\Http\RedirectResponse;

class ReviewController extends Controller
{
    public function store(StoreReviewRequest $request, ReviewSubmissionService $service): RedirectResponse
    {
        $service->submit($request->safe()->only(['reviewer_name', 'reviewer_title', 'rating', 'body']));

        return redirect(route('site.reviews') . '#write-review')->with('review_submitted', true);
    }
}
