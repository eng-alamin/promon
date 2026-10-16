<?php

declare(strict_types=1);

namespace Tests\Feature\Site;

use App\Models\Review;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReviewSubmitTest extends TestCase
{
    use RefreshDatabase;

    /** @return array<string, mixed> */
    private function payload(array $override = []): array
    {
        return array_merge([
            'reviewer_name' => 'Rahim Uddin',
            'reviewer_title' => 'Retailer, Dhaka',
            'rating' => 5,
            'body' => 'Very good service and fast replies.',
        ], $override);
    }

    public function test_reviews_page_shows_the_review_form(): void
    {
        $this->get(route('site.reviews'))
            ->assertOk()
            ->assertSee('Write a review.')
            ->assertSee(route('site.reviews.submit'), false);
    }

    public function test_a_visitor_can_submit_a_review_and_it_waits_for_approval(): void
    {
        $this->post(route('site.reviews.submit'), $this->payload())
            ->assertRedirect(route('site.reviews') . '#write-review')
            ->assertSessionHas('review_submitted');

        $this->assertDatabaseHas('reviews', [
            'reviewer_name' => 'Rahim Uddin',
            'rating' => 5,
            'is_active' => false,
        ]);

        $this->get(route('site.reviews'))->assertDontSee('Very good service and fast replies.');
    }

    public function test_validation_errors_are_shown(): void
    {
        $this->from(route('site.reviews'))
            ->post(route('site.reviews.submit'), $this->payload(['reviewer_name' => '', 'rating' => 9, 'body' => 'short']))
            ->assertSessionHasErrors(['reviewer_name', 'rating', 'body']);

        $this->assertDatabaseCount('reviews', 0);
    }

    public function test_the_honeypot_blocks_bots(): void
    {
        $this->post(route('site.reviews.submit'), $this->payload(['website' => 'http://spam.test']))
            ->assertSessionHasErrors('website');

        $this->assertDatabaseCount('reviews', 0);
    }

    public function test_html_in_a_review_is_escaped_after_approval(): void
    {
        $this->post(route('site.reviews.submit'), $this->payload(['body' => '<script>alert(1)</script> hello']));
        Review::query()->update(['is_active' => true]);

        $this->get(route('site.reviews'))->assertDontSee('<script>alert(1)</script>', false);
    }
}
