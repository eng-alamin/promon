<?php

declare(strict_types=1);

namespace Tests\Feature\Admin;

use App\Enums\EnquiryStatus;
use App\Livewire\Admin\Enquiries\EnquiryShow;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Enquiry;
use App\Models\EnquiryItem;
use App\Models\Product;
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

    private function addItem(string $name, int $quantity, string $unitPrice, bool $deleted = false): Product
    {
        $category = Category::firstOrCreate(['slug' => 'supplements'], ['name' => 'Supplements']);
        $product = Product::create([
            'category_id' => $category->id,
            'name' => $name,
            'slug' => str($name)->slug()->toString(),
            'price' => 99,
        ]);

        EnquiryItem::create([
            'enquiry_id' => $this->enquiry->id,
            'product_id' => $product->id,
            'quantity' => $quantity,
            'unit_price' => $unitPrice,
        ]);

        if ($deleted) {
            $product->delete();
        }

        return $product;
    }

    private function showComponent()
    {
        return Livewire::test(EnquiryShow::class, ['enquiry' => $this->enquiry]);
    }

    public function test_it_shows_customer_message_items_and_the_total_from_the_saved_prices(): void
    {
        $this->addItem('Vitamin C', 12, '9.50');
        $this->addItem('Fish Oil', 2, '5.00');

        $this->showComponent()
            ->assertSee('Dhaka Mart')
            ->assertSee('rahim@example.com')
            ->assertSee('Please send the best price.')
            ->assertSee('Vitamin C')
            ->assertSee('£9.50')
            ->assertSee('£114.00')
            ->assertSee('£124.00')
            ->assertSee('https://wa.me/8801711000000', false);
    }

    public function test_a_deleted_product_is_still_shown(): void
    {
        $this->addItem('Old Product', 1, '3.00', deleted: true);

        $this->showComponent()->assertSee('Old Product')->assertSee('Deleted');
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
