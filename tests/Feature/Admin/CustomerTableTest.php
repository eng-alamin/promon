<?php

declare(strict_types=1);

namespace Tests\Feature\Admin;

use App\Livewire\Admin\Customers\CustomerTable;
use App\Models\Customer;
use App\Models\Enquiry;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class CustomerTableTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(User::factory()->create());
    }

    public function test_it_creates_a_customer_and_saves_empty_optional_fields_as_null(): void
    {
        Livewire::test(CustomerTable::class)
            ->call('create')
            ->set('contact_name', '  Rahim Uddin ')
            ->set('phone', '01700000000')
            ->set('business_name', '   ')
            ->call('save')
            ->assertHasNoErrors()
            ->assertSet('showForm', false)
            ->assertDispatched('toast');

        $this->assertDatabaseHas('customers', [
            'contact_name' => 'Rahim Uddin',
            'phone' => '01700000000',
            'business_name' => null,
            'email' => null,
            'type' => 'retailer',
        ]);
    }

    public function test_contact_name_is_required(): void
    {
        Livewire::test(CustomerTable::class)
            ->call('create')
            ->set('phone', '01700000000')
            ->call('save')
            ->assertHasErrors(['contact_name' => 'required']);

        $this->assertDatabaseCount('customers', 0);
    }

    public function test_one_way_to_reach_the_customer_is_required(): void
    {
        Livewire::test(CustomerTable::class)
            ->call('create')
            ->set('contact_name', 'Rahim Uddin')
            ->call('save')
            ->assertHasErrors(['phone', 'whatsapp', 'email']);

        $this->assertDatabaseCount('customers', 0);
    }

    public function test_email_must_be_valid_and_type_must_exist(): void
    {
        Livewire::test(CustomerTable::class)
            ->call('create')
            ->set('contact_name', 'Rahim Uddin')
            ->set('email', 'not-an-email')
            ->set('customer_type', 'vip')
            ->call('save')
            ->assertHasErrors(['email', 'customer_type']);
    }

    public function test_it_edits_a_customer(): void
    {
        $customer = Customer::create([
            'contact_name' => 'Rahim Uddin',
            'phone' => '01700000000',
            'type' => 'retailer',
        ]);

        Livewire::test(CustomerTable::class)
            ->call('edit', $customer->id)
            ->assertSet('contact_name', 'Rahim Uddin')
            ->assertSet('customer_type', 'retailer')
            ->set('city', 'Dhaka')
            ->set('customer_type', 'wholesaler')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('customers', [
            'id' => $customer->id,
            'city' => 'Dhaka',
            'type' => 'wholesaler',
        ]);
        $this->assertDatabaseCount('customers', 1);
    }

    public function test_it_searches_by_business_name_and_phone(): void
    {
        Customer::create(['contact_name' => 'Rahim', 'business_name' => 'Green Mart', 'phone' => '01711111111']);
        Customer::create(['contact_name' => 'Karim', 'business_name' => 'Blue Store', 'phone' => '01822222222']);

        Livewire::test(CustomerTable::class)
            ->set('search', 'Green')
            ->assertSee('Green Mart')
            ->assertDontSee('Blue Store')
            ->set('search', '0182')
            ->assertSee('Blue Store')
            ->assertDontSee('Green Mart');
    }

    public function test_it_filters_by_type(): void
    {
        Customer::create(['contact_name' => 'Shop Owner', 'phone' => '01711111111', 'type' => 'retailer']);
        Customer::create(['contact_name' => 'Big Buyer', 'phone' => '01822222222', 'type' => 'wholesaler']);

        Livewire::test(CustomerTable::class)
            ->set('typeFilter', 'wholesaler')
            ->assertSee('Big Buyer')
            ->assertDontSee('Shop Owner');
    }

    public function test_it_deletes_a_customer_without_enquiries(): void
    {
        $customer = Customer::create(['contact_name' => 'Rahim', 'phone' => '01711111111']);

        Livewire::test(CustomerTable::class)
            ->call('confirmDelete', $customer->id)
            ->call('delete');

        $this->assertDatabaseMissing('customers', ['id' => $customer->id]);
    }

    public function test_it_blocks_deleting_a_customer_who_has_enquiries(): void
    {
        $customer = Customer::create(['contact_name' => 'Rahim', 'phone' => '01711111111']);
        Enquiry::create(['customer_id' => $customer->id]);

        Livewire::test(CustomerTable::class)
            ->call('confirmDelete', $customer->id)
            ->call('delete')
            ->assertDispatched('toast');

        $this->assertDatabaseHas('customers', ['id' => $customer->id]);
    }
}
