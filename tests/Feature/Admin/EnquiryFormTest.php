<?php

declare(strict_types=1);

namespace Tests\Feature\Admin;

use App\Enums\EnquiryChannel;
use App\Enums\EnquiryStatus;
use App\Livewire\Admin\Enquiries\EnquiryForm;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Enquiry;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class EnquiryFormTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    private Category $supplements;

    private Category $grocery;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->actingAs($this->user);

        $this->supplements = Category::create(['name' => 'Supplements', 'slug' => 'supplements', 'sort_order' => 1]);
        $this->grocery = Category::create(['name' => 'Grocery', 'slug' => 'grocery', 'sort_order' => 2]);
    }

    private function newCustomer()
    {
        return Livewire::test(EnquiryForm::class)
            ->set('customerMode', 'new')
            ->set('contact_name', 'Rahim Uddin')
            ->set('phone', '01711000000');
    }

    public function test_it_creates_an_enquiry_for_a_new_customer_with_categories_and_a_message(): void
    {
        $this->newCustomer()
            ->set('business_name', 'Dhaka Mart')
            ->set('message', 'Phone call')
            ->set('categoryIds', [(string) $this->supplements->id, (string) $this->grocery->id])
            ->call('save')
            ->assertHasNoErrors()
            ->assertRedirect();

        $enquiry = Enquiry::with('categories', 'customer')->firstOrFail();

        $this->assertSame(EnquiryChannel::Manual, $enquiry->channel);
        $this->assertSame(EnquiryStatus::New, $enquiry->status);
        $this->assertSame($this->user->id, $enquiry->handled_by);
        $this->assertSame('Phone call', $enquiry->message);
        $this->assertSame('Dhaka Mart', $enquiry->customer->business_name);
        $this->assertNull($enquiry->customer->email);
        $this->assertSame(['Supplements', 'Grocery'], $enquiry->categories->pluck('name')->all());
    }

    public function test_the_message_is_optional(): void
    {
        $this->newCustomer()
            ->set('categoryIds', [(string) $this->grocery->id])
            ->call('save')
            ->assertHasNoErrors();

        $this->assertNull(Enquiry::firstOrFail()->message);
    }

    public function test_it_creates_an_enquiry_for_an_existing_customer(): void
    {
        $customer = Customer::create(['business_name' => 'Dhaka Mart', 'contact_name' => 'Rahim Uddin']);

        Livewire::test(EnquiryForm::class)
            ->set('customerSearch', 'Dhaka')
            ->assertSee('Dhaka Mart')
            ->call('selectCustomer', $customer->id)
            ->assertSet('customerId', (string) $customer->id)
            ->set('categoryIds', [(string) $this->grocery->id])
            ->call('save')
            ->assertHasNoErrors();

        $this->assertSame(1, Customer::count());
        $this->assertSame($customer->id, Enquiry::firstOrFail()->customer_id);
    }

    public function test_an_existing_customer_must_be_chosen(): void
    {
        Livewire::test(EnquiryForm::class)
            ->set('categoryIds', [(string) $this->grocery->id])
            ->call('save')
            ->assertHasErrors(['customerId' => 'required']);
    }

    public function test_a_new_customer_needs_a_name_and_a_way_to_be_reached(): void
    {
        Livewire::test(EnquiryForm::class)
            ->set('customerMode', 'new')
            ->set('categoryIds', [(string) $this->grocery->id])
            ->call('save')
            ->assertHasErrors(['contact_name' => 'required', 'phone' => 'required_without_all']);

        $this->assertSame(0, Customer::count());
    }

    public function test_at_least_one_category_is_required(): void
    {
        $this->newCustomer()
            ->call('save')
            ->assertHasErrors(['categoryIds' => 'required']);

        $this->assertSame(0, Enquiry::count());
    }

    public function test_an_inactive_category_cannot_be_chosen(): void
    {
        $closed = Category::create(['name' => 'Closed', 'slug' => 'closed', 'is_active' => false]);

        $this->newCustomer()
            ->set('categoryIds', [(string) $closed->id])
            ->call('save')
            ->assertHasErrors(['categoryIds.0' => 'exists']);

        $this->assertSame(0, Enquiry::count());
    }

    public function test_an_unknown_category_cannot_be_chosen(): void
    {
        $this->newCustomer()
            ->set('categoryIds', ['9999'])
            ->call('save')
            ->assertHasErrors(['categoryIds.0' => 'exists']);
    }

    public function test_only_active_categories_are_offered(): void
    {
        Category::create(['name' => 'Hidden Stuff', 'slug' => 'hidden-stuff', 'is_active' => false]);

        Livewire::test(EnquiryForm::class)
            ->assertSee('Supplements')
            ->assertSee('Grocery')
            ->assertDontSee('Hidden Stuff');
    }
}
