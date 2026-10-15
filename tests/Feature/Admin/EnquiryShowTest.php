<?php

declare(strict_types=1);

namespace Tests\Feature\Admin;

use App\Enums\EnquiryStatus;
use App\Livewire\Admin\Enquiries\EnquiryShow;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Enquiry;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class EnquiryShowTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    private Enquiry $enquiry;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->actingAs($this->user);

        $customer = Customer::create([
            'business_name' => 'Dhaka Mart',
            'contact_name' => 'Rahim Uddin',
            'whatsapp' => '+880 1711-000000',
            'email' => 'rahim@example.com',
        ]);

        $this->enquiry = Enquiry::create([
            'customer_id' => $customer->id,
            'message' => 'Please send the best price.',
        ])->fresh();
    }

    private function showComponent()
    {
        return Livewire::test(EnquiryShow::class, ['enquiry' => $this->enquiry]);
    }

    public function test_it_shows_the_customer_the_message_and_the_categories(): void
    {
        $supplements = Category::create(['name' => 'Supplements', 'slug' => 'supplements']);
        $grocery = Category::create(['name' => 'Grocery', 'slug' => 'grocery']);
        $this->enquiry->categories()->sync([$supplements->id, $grocery->id]);

        $this->showComponent()
            ->assertSee('Dhaka Mart')
            ->assertSee('rahim@example.com')
            ->assertSee('Please send the best price.')
            ->assertSee('Supplements')
            ->assertSee('Grocery')
            ->assertSee('https://wa.me/8801711000000', false);
    }

    public function test_it_does_not_show_products_prices_or_totals(): void
    {
        $this->showComponent()
            ->assertDontSee('Unit price')
            ->assertDontSee('Line total')
            ->assertDontSee('£');
    }

    public function test_an_enquiry_without_categories_says_so(): void
    {
        $this->showComponent()->assertSee('No category was chosen for this enquiry.');
    }

    public function test_changing_the_status_saves_it_and_assigns_the_handler(): void
    {
        $this->showComponent()
            ->call('setStatus', 'contacted')
            ->assertDispatched('toast');

        $fresh = $this->enquiry->fresh();

        $this->assertSame(EnquiryStatus::Contacted, $fresh->status);
        $this->assertSame($this->user->id, $fresh->handled_by);
    }

    public function test_the_existing_handler_is_not_replaced_by_a_status_change(): void
    {
        $other = User::factory()->create();
        $this->enquiry->update(['handled_by' => $other->id]);

        $this->showComponent()->call('setStatus', 'quoted');

        $this->assertSame($other->id, $this->enquiry->fresh()->handled_by);
    }

    public function test_an_invalid_status_is_rejected(): void
    {
        $this->showComponent()
            ->call('setStatus', 'bogus')
            ->assertHasErrors('status');

        $this->assertSame(EnquiryStatus::New, $this->enquiry->fresh()->status);
    }

    public function test_it_assigns_the_enquiry_to_the_current_user(): void
    {
        $this->showComponent()->call('assignToMe');

        $this->assertSame($this->user->id, $this->enquiry->fresh()->handled_by);
    }

    public function test_it_saves_notes_and_stores_blank_notes_as_null(): void
    {
        $this->showComponent()
            ->set('admin_notes', '  Called on Monday.  ')
            ->call('saveNotes')
            ->assertHasNoErrors();

        $this->assertSame('Called on Monday.', $this->enquiry->fresh()->admin_notes);

        $this->showComponent()
            ->set('admin_notes', '   ')
            ->call('saveNotes');

        $this->assertNull($this->enquiry->fresh()->admin_notes);
    }

    public function test_notes_have_a_length_limit(): void
    {
        $this->showComponent()
            ->set('admin_notes', str_repeat('a', 5001))
            ->call('saveNotes')
            ->assertHasErrors(['admin_notes' => 'max']);
    }
}
