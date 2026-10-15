<?php

declare(strict_types=1);

namespace Tests\Feature\Admin;

use App\Enums\EnquiryChannel;
use App\Enums\EnquiryStatus;
use App\Livewire\Admin\Enquiries\EnquiryForm;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Enquiry;
use App\Models\Product;
use App\Models\ProductPriceTier;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class EnquiryFormTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    private Product $vitamin;

    private Product $fishOil;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->actingAs($this->user);

        $category = Category::create(['name' => 'Supplements', 'slug' => 'supplements']);
        $this->vitamin = Product::create(['category_id' => $category->id, 'name' => 'Vitamin C', 'slug' => 'vitamin-c', 'price' => 10]);
        $this->fishOil = Product::create(['category_id' => $category->id, 'name' => 'Fish Oil', 'slug' => 'fish-oil', 'price' => 8]);
        ProductPriceTier::create(['product_id' => $this->vitamin->id, 'min_qty' => 12, 'price' => 9]);
        ProductPriceTier::create(['product_id' => $this->vitamin->id, 'min_qty' => 50, 'price' => 8]);
    }

    private function newCustomer()
    {
        return Livewire::test(EnquiryForm::class)
            ->set('customerMode', 'new')
            ->set('contact_name', 'Rahim Uddin')
            ->set('phone', '01711000000');
    }

    public function test_it_creates_an_enquiry_for_a_new_customer_with_price_snapshots(): void
    {
        $this->newCustomer()
            ->set('business_name', 'Dhaka Mart')
            ->set('message', 'Phone call')
            ->set('items', [
                ['product_id' => (string) $this->vitamin->id, 'quantity' => '60'],
                ['product_id' => (string) $this->fishOil->id, 'quantity' => '3'],
            ])
            ->call('save')
            ->assertHasNoErrors()
            ->assertRedirect();

        $enquiry = Enquiry::with('items', 'customer')->firstOrFail();

        $this->assertSame(EnquiryChannel::Manual, $enquiry->channel);
        $this->assertSame(EnquiryStatus::New, $enquiry->status);
        $this->assertSame($this->user->id, $enquiry->handled_by);
        $this->assertSame('Phone call', $enquiry->message);
        $this->assertSame('Dhaka Mart', $enquiry->customer->business_name);
        $this->assertNull($enquiry->customer->email);
        $this->assertCount(2, $enquiry->items);

        $prices = $enquiry->items->pluck('unit_price', 'product_id');
        $this->assertSame('8.00', $prices[$this->vitamin->id]);
        $this->assertSame('8.00', $prices[$this->fishOil->id]);
    }

    public function test_the_middle_tier_price_is_used(): void
    {
        $this->newCustomer()
            ->set('items', [['product_id' => (string) $this->vitamin->id, 'quantity' => '12']])
            ->call('save')
            ->assertHasNoErrors();

        $this->assertSame('9.00', Enquiry::firstOrFail()->items()->firstOrFail()->unit_price);
    }

    public function test_it_creates_an_enquiry_for_an_existing_customer(): void
    {
        $customer = Customer::create(['business_name' => 'Dhaka Mart', 'contact_name' => 'Rahim Uddin']);

        Livewire::test(EnquiryForm::class)
            ->set('customerSearch', 'Dhaka')
            ->assertSee('Dhaka Mart')
            ->call('selectCustomer', $customer->id)
            ->assertSet('customerId', (string) $customer->id)
            ->set('items', [['product_id' => (string) $this->fishOil->id, 'quantity' => '2']])
            ->call('save')
            ->assertHasNoErrors();

        $this->assertSame(1, Customer::count());
        $this->assertSame($customer->id, Enquiry::firstOrFail()->customer_id);
    }

    public function test_an_existing_customer_must_be_chosen(): void
    {
        Livewire::test(EnquiryForm::class)
            ->set('items', [['product_id' => (string) $this->fishOil->id, 'quantity' => '2']])
            ->call('save')
            ->assertHasErrors(['customerId' => 'required']);
    }

    public function test_a_new_customer_needs_a_name_and_a_way_to_be_reached(): void
    {
        Livewire::test(EnquiryForm::class)
            ->set('customerMode', 'new')
            ->set('items', [['product_id' => (string) $this->fishOil->id, 'quantity' => '2']])
            ->call('save')
            ->assertHasErrors(['contact_name' => 'required', 'phone' => 'required_without_all']);

        $this->assertSame(0, Customer::count());
    }

    public function test_a_product_is_required_and_cannot_repeat(): void
    {
        $this->newCustomer()
            ->set('items', [['product_id' => '', 'quantity' => '1']])
            ->call('save')
            ->assertHasErrors(['items.0.product_id' => 'required']);

        $this->newCustomer()
            ->set('items', [
                ['product_id' => (string) $this->fishOil->id, 'quantity' => '1'],
                ['product_id' => (string) $this->fishOil->id, 'quantity' => '2'],
            ])
            ->call('save')
            ->assertHasErrors(['items.0.product_id' => 'distinct']);
    }

    public function test_the_quantity_must_be_a_whole_number_of_one_or_more(): void
    {
        $this->newCustomer()
            ->set('items', [['product_id' => (string) $this->fishOil->id, 'quantity' => '0']])
            ->call('save')
            ->assertHasErrors(['items.0.quantity' => 'min']);

        $this->newCustomer()
            ->set('items', [['product_id' => (string) $this->fishOil->id, 'quantity' => '1.5']])
            ->call('save')
            ->assertHasErrors(['items.0.quantity' => 'integer']);
    }

    public function test_a_deleted_product_cannot_be_chosen(): void
    {
        $this->fishOil->delete();

        $this->newCustomer()
            ->set('items', [['product_id' => (string) $this->fishOil->id, 'quantity' => '1']])
            ->call('save')
            ->assertHasErrors(['items.0.product_id' => 'exists']);
    }

    public function test_rows_can_be_added_and_removed_but_one_always_stays(): void
    {
        Livewire::test(EnquiryForm::class)
            ->assertCount('items', 1)
            ->call('addItem')
            ->assertCount('items', 2)
            ->call('removeItem', 0)
            ->assertCount('items', 1)
            ->call('removeItem', 0)
            ->assertCount('items', 1);
    }
}
