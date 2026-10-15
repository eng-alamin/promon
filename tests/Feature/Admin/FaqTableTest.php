<?php

declare(strict_types=1);

namespace Tests\Feature\Admin;

use App\Livewire\Admin\Faqs\FaqTable;
use App\Models\Faq;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class FaqTableTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(User::factory()->create());
    }

    public function test_it_creates_a_faq(): void
    {
        Livewire::test(FaqTable::class)
            ->call('create')
            ->set('question', '  How long is delivery? ')
            ->set('answer', 'About 10 working days.')
            ->call('save')
            ->assertHasNoErrors()
            ->assertSet('showForm', false)
            ->assertDispatched('toast');

        $this->assertDatabaseHas('faqs', [
            'question' => 'How long is delivery?',
            'answer' => 'About 10 working days.',
            'is_active' => true,
        ]);
    }

    public function test_a_new_faq_gets_the_next_sort_order(): void
    {
        Livewire::test(FaqTable::class)->call('create')->assertSet('sort_order', '1');

        Faq::create(['question' => 'Q', 'answer' => 'A', 'sort_order' => 5]);

        Livewire::test(FaqTable::class)->call('create')->assertSet('sort_order', '6');
    }

    public function test_question_and_answer_are_required(): void
    {
        Livewire::test(FaqTable::class)
            ->call('create')
            ->call('save')
            ->assertHasErrors(['question' => 'required', 'answer' => 'required']);

        $this->assertDatabaseCount('faqs', 0);
    }

    public function test_sort_order_must_be_a_whole_number_in_range(): void
    {
        Livewire::test(FaqTable::class)
            ->call('create')
            ->set('question', 'Q')
            ->set('answer', 'A')
            ->set('sort_order', 'abc')
            ->call('save')
            ->assertHasErrors(['sort_order' => 'integer'])
            ->set('sort_order', '70000')
            ->call('save')
            ->assertHasErrors(['sort_order' => 'max'])
            ->set('sort_order', '')
            ->call('save')
            ->assertHasErrors(['sort_order' => 'required']);

        $this->assertDatabaseCount('faqs', 0);
    }

    public function test_it_edits_a_faq(): void
    {
        $faq = Faq::create(['question' => 'Old question', 'answer' => 'Old answer', 'sort_order' => 3]);

        Livewire::test(FaqTable::class)
            ->call('edit', $faq->id)
            ->assertSet('question', 'Old question')
            ->assertSet('sort_order', '3')
            ->set('question', 'New question')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('faqs', ['id' => $faq->id, 'question' => 'New question', 'sort_order' => 3]);
        $this->assertDatabaseCount('faqs', 1);
    }

    public function test_it_toggles_active_status(): void
    {
        $faq = Faq::create(['question' => 'Q', 'answer' => 'A']);

        Livewire::test(FaqTable::class)->call('toggleActive', $faq->id);

        $this->assertDatabaseHas('faqs', ['id' => $faq->id, 'is_active' => false]);
    }

    public function test_it_deletes_a_faq(): void
    {
        $faq = Faq::create(['question' => 'Q', 'answer' => 'A']);

        Livewire::test(FaqTable::class)
            ->call('confirmDelete', $faq->id)
            ->call('delete')
            ->assertDispatched('toast');

        $this->assertDatabaseMissing('faqs', ['id' => $faq->id]);
    }

    public function test_it_lists_faqs_by_sort_order_and_searches(): void
    {
        Faq::create(['question' => 'Second question', 'answer' => 'About payment', 'sort_order' => 2]);
        Faq::create(['question' => 'First question', 'answer' => 'About delivery', 'sort_order' => 1]);

        Livewire::test(FaqTable::class)
            ->assertSeeInOrder(['First question', 'Second question'])
            ->set('search', 'payment')
            ->assertSee('Second question')
            ->assertDontSee('First question');
    }
}
