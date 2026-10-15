<?php

declare(strict_types=1);

namespace Tests\Feature\Admin;

use App\Enums\EnquiryChannel;
use App\Enums\EnquiryStatus;
use App\Livewire\Admin\Enquiries\EnquiryTable;
use App\Models\Customer;
use App\Models\Enquiry;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class EnquiryTableTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(User::factory()->create());
    }

    private function makeEnquiry(string $contact, array $extra = [], array $customer = []): Enquiry
    {
        $customer = Customer::create(array_merge(['contact_name' => $contact], $customer));

        return Enquiry::create(array_merge(['customer_id' => $customer->id], $extra));
    }

    public function test_it_shows_an_empty_state(): void
    {
        Livewire::test(EnquiryTable::class)->assertSee('No enquiries yet');
    }

    public function test_it_lists_enquiries(): void
    {
        $enquiry = $this->makeEnquiry('Rahim Uddin', ['channel' => EnquiryChannel::Whatsapp], ['business_name' => 'Dhaka Mart']);

        Livewire::test(EnquiryTable::class)
            ->assertSee($enquiry->reference)
            ->assertSee('Dhaka Mart')
            ->assertSee('WhatsApp');
    }

    public function test_search_finds_by_reference_and_by_customer(): void
    {
        $first = $this->makeEnquiry('Rahim Uddin', [], ['phone' => '01711000000']);
        $second = $this->makeEnquiry('Karim Ali');

        Livewire::test(EnquiryTable::class)
            ->set('search', 'Rahim')
            ->assertSee($first->reference)
            ->assertDontSee($second->reference)
            ->set('search', '01711')
            ->assertSee($first->reference)
            ->assertDontSee($second->reference)
            ->set('search', $second->reference)
            ->assertSee($second->reference)
            ->assertDontSee($first->reference);
    }

    public function test_it_filters_by_status_and_channel(): void
    {
        $new = $this->makeEnquiry('Rahim Uddin');
        $quoted = $this->makeEnquiry('Karim Ali', ['status' => EnquiryStatus::Quoted, 'channel' => EnquiryChannel::Manual]);

        Livewire::test(EnquiryTable::class)
            ->set('statusFilter', 'quoted')
            ->assertSee($quoted->reference)
            ->assertDontSee($new->reference)
            ->set('statusFilter', '')
            ->set('channelFilter', 'manual')
            ->assertSee($quoted->reference)
            ->assertDontSee($new->reference);
    }

    public function test_an_unknown_filter_is_ignored(): void
    {
        $enquiry = $this->makeEnquiry('Rahim Uddin');

        Livewire::test(EnquiryTable::class)
            ->set('statusFilter', 'bogus')
            ->set('channelFilter', 'bogus')
            ->assertSee($enquiry->reference);
    }
}
