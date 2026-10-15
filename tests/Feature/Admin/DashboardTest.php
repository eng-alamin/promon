<?php

declare(strict_types=1);

namespace Tests\Feature\Admin;

use App\Enums\EnquiryChannel;
use App\Enums\UserRole;
use App\Models\Customer;
use App\Models\Enquiry;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_shows_empty_state_when_there_are_no_enquiries(): void
    {
        $this->actingAs(User::factory()->create(['role' => UserRole::SuperAdmin]))
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('Welcome back')
            ->assertSee('No enquiries yet');
    }

    public function test_dashboard_lists_recent_enquiries(): void
    {
        $customer = Customer::create([
            'business_name' => 'Dhaka Mart',
            'contact_name' => 'Test Buyer',
        ]);

        $enquiry = Enquiry::create([
            'customer_id' => $customer->id,
            'channel' => EnquiryChannel::Whatsapp,
        ]);

        $this->actingAs(User::factory()->create(['role' => UserRole::SuperAdmin]))
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee($enquiry->reference)
            ->assertSee('Dhaka Mart')
            ->assertSee('WhatsApp')
            ->assertDontSee('No enquiries yet');
    }
}
