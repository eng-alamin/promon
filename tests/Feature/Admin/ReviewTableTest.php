<?php

declare(strict_types=1);

namespace Tests\Feature\Admin;

use App\Livewire\Admin\Reviews\ReviewTable;
use App\Models\Review;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ReviewTableTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(User::factory()->create());
    }

    private function makeReview(array $overrides = []): Review
    {
        return Review::create(array_merge([
            'reviewer_name' => 'Test Customer',
            'rating' => 5,
            'body' => 'Great service.',
            'sort_order' => 1,
            'is_active' => true,
        ], $overrides));
    }

    public function test_guests_are_redirected_to_login(): void
    {
        auth()->logout();

        $this->get(route('admin.reviews.index'))->assertRedirect(route('admin.login'));
    }

    public function test_reviews_page_contains_the_component_and_menu_link(): void
    {
        $this->get(route('admin.reviews.index'))
            ->assertOk()
            ->assertSeeLivewire(ReviewTable::class);

        $this->get(route('admin.dashboard'))->assertSee(route('admin.reviews.index'));
    }

    public function test_it_creates_a_review(): void
    {
        Livewire::test(ReviewTable::class)
            ->call('create')
            ->set('reviewer_name', '  Rahim Uddin ')
            ->set('reviewer_title', 'Shop owner, Dhaka')
            ->set('rating', '4')
            ->set('body', 'Good and genuine products.')
            ->call('save')
            ->assertHasNoErrors()
            ->assertSet('showForm', false)
            ->assertDispatched('toast');

        $this->assertDatabaseHas('reviews', [
            'reviewer_name' => 'Rahim Uddin',
            'reviewer_title' => 'Shop owner, Dhaka',
            'rating' => 4,
            'is_active' => true,
        ]);
    }

    public function test_an_empty_title_is_saved_as_null(): void
    {
        Livewire::test(ReviewTable::class)
            ->call('create')
            ->set('reviewer_name', 'Nusrat')
            ->set('body', 'Nice.')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('reviews', ['reviewer_name' => 'Nusrat', 'reviewer_title' => null]);
    }

    public function test_a_new_review_gets_the_next_sort_order(): void
    {
        Livewire::test(ReviewTable::class)->call('create')->assertSet('sort_order', '1');

        $this->makeReview(['sort_order' => 5]);

        Livewire::test(ReviewTable::class)->call('create')->assertSet('sort_order', '6');
    }

    public function test_name_and_review_are_required(): void
    {
        Livewire::test(ReviewTable::class)
            ->call('create')
            ->call('save')
            ->assertHasErrors(['reviewer_name' => 'required', 'body' => 'required']);

        $this->assertDatabaseCount('reviews', 0);
    }

    public function test_rating_must_be_between_one_and_five(): void
    {
        Livewire::test(ReviewTable::class)
            ->call('create')
            ->set('reviewer_name', 'A')
            ->set('body', 'B')
            ->set('rating', '6')
            ->call('save')
            ->assertHasErrors(['rating' => 'between'])
            ->set('rating', '0')
            ->call('save')
            ->assertHasErrors(['rating' => 'between'])
            ->set('rating', 'abc')
            ->call('save')
            ->assertHasErrors(['rating' => 'integer']);

        $this->assertDatabaseCount('reviews', 0);
    }

    public function test_sort_order_must_be_a_whole_number_in_range(): void
    {
        Livewire::test(ReviewTable::class)
            ->call('create')
            ->set('reviewer_name', 'A')
            ->set('body', 'B')
            ->set('sort_order', 'abc')
            ->call('save')
            ->assertHasErrors(['sort_order' => 'integer'])
            ->set('sort_order', '70000')
            ->call('save')
            ->assertHasErrors(['sort_order' => 'max'])
            ->set('sort_order', '')
            ->call('save')
            ->assertHasErrors(['sort_order' => 'required']);

        $this->assertDatabaseCount('reviews', 0);
    }

    public function test_it_edits_a_review(): void
    {
        $review = $this->makeReview(['reviewer_name' => 'Old Name', 'rating' => 3, 'sort_order' => 3]);

        Livewire::test(ReviewTable::class)
            ->call('edit', $review->id)
            ->assertSet('reviewer_name', 'Old Name')
            ->assertSet('rating', '3')
            ->assertSet('sort_order', '3')
            ->set('reviewer_name', 'New Name')
            ->set('rating', '5')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('reviews', ['id' => $review->id, 'reviewer_name' => 'New Name', 'rating' => 5, 'sort_order' => 3]);
        $this->assertDatabaseCount('reviews', 1);
    }

    public function test_it_toggles_active_status(): void
    {
        $review = $this->makeReview();

        Livewire::test(ReviewTable::class)->call('toggleActive', $review->id);

        $this->assertDatabaseHas('reviews', ['id' => $review->id, 'is_active' => false]);
    }

    public function test_it_deletes_a_review(): void
    {
        $review = $this->makeReview();

        Livewire::test(ReviewTable::class)
            ->call('confirmDelete', $review->id)
            ->call('delete')
            ->assertDispatched('toast');

        $this->assertDatabaseMissing('reviews', ['id' => $review->id]);
    }

    public function test_it_lists_reviews_by_sort_order_and_searches(): void
    {
        $this->makeReview(['reviewer_name' => 'Second Person', 'body' => 'About delivery', 'sort_order' => 2]);
        $this->makeReview(['reviewer_name' => 'First Person', 'body' => 'About packing', 'sort_order' => 1]);

        Livewire::test(ReviewTable::class)
            ->assertSeeInOrder(['First Person', 'Second Person'])
            ->set('search', 'delivery')
            ->assertSee('Second Person')
            ->assertDontSee('First Person');
    }
}
