<?php

declare(strict_types=1);

namespace Tests\Feature\Site;

use App\Models\Review;
use Database\Seeders\ReviewSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReviewsPageTest extends TestCase
{
    use RefreshDatabase;

    private function makeReview(string $name, int $order, bool $active = true, int $rating = 5, string $body = 'Good service.'): Review
    {
        return Review::create([
            'reviewer_name' => $name,
            'rating' => $rating,
            'body' => $body,
            'sort_order' => $order,
            'is_active' => $active,
        ]);
    }

    public function test_the_seeder_creates_ten_reviews_and_can_run_twice(): void
    {
        $this->seed(ReviewSeeder::class);
        $this->seed(ReviewSeeder::class);

        $this->assertDatabaseCount('reviews', 10);
        $this->assertSame(10, Review::active()->count());
    }

    public function test_home_page_shows_the_first_three_active_reviews_and_a_more_link(): void
    {
        $this->makeReview('Hidden Person', 1, false);
        $this->makeReview('Person One', 2);
        $this->makeReview('Person Two', 3);
        $this->makeReview('Person Three', 4);
        $this->makeReview('Person Four', 5);

        $this->get(route('site.home'))
            ->assertOk()
            ->assertSee('What our customers say.')
            ->assertSeeInOrder(['Person One', 'Person Two', 'Person Three'])
            ->assertDontSee('Hidden Person')
            ->assertDontSee('Person Four')
            ->assertSee(route('site.reviews'), false);
    }

    public function test_home_page_hides_the_more_link_when_every_review_is_already_shown(): void
    {
        $this->makeReview('Person One', 1);

        $this->get(route('site.home'))
            ->assertOk()
            ->assertSee('Person One')
            ->assertDontSee('More reviews');
    }

    public function test_home_page_hides_the_section_when_there_are_no_active_reviews(): void
    {
        $this->makeReview('Hidden Person', 1, false);

        $this->get(route('site.home'))
            ->assertOk()
            ->assertDontSee('What our customers say.')
            ->assertDontSee('Hidden Person');
    }

    public function test_reviews_page_lists_every_active_review_in_order(): void
    {
        $this->makeReview('Person Two', 2);
        $this->makeReview('Person One', 1);
        $this->makeReview('Hidden Person', 3, false);
        $this->makeReview('Person Four', 4);

        $this->get(route('site.reviews'))
            ->assertOk()
            ->assertSeeInOrder(['Person One', 'Person Two', 'Person Four'])
            ->assertDontSee('Hidden Person');
    }

    public function test_reviews_page_shows_the_average_rating(): void
    {
        $this->makeReview('Person One', 1, true, 5);
        $this->makeReview('Person Two', 2, true, 4);

        $this->get(route('site.reviews'))
            ->assertOk()
            ->assertSee('4.5 out of 5')
            ->assertSee('2 reviews');
    }

    public function test_reviews_page_shows_a_message_when_empty(): void
    {
        $this->get(route('site.reviews'))
            ->assertOk()
            ->assertSee('No reviews have been added yet');
    }

    public function test_reviews_page_escapes_html(): void
    {
        $this->makeReview('Person One', 1, true, 5, '<script>alert(1)</script>');

        $this->get(route('site.reviews'))->assertOk()->assertDontSee('<script>alert(1)</script>', false);
    }

    public function test_navigation_and_sitemap_link_to_the_reviews_page(): void
    {
        $this->get(route('site.about'))->assertSee(route('site.reviews'), false);
        $this->get(route('site.sitemap'))->assertSee(route('site.reviews'), false);
    }
}
