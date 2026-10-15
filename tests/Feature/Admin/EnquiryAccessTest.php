<?php

declare(strict_types=1);

namespace Tests\Feature\Admin;

use App\Livewire\Admin\Enquiries\EnquiryForm;
use App\Livewire\Admin\Enquiries\EnquiryShow;
use App\Livewire\Admin\Enquiries\EnquiryTable;
use App\Models\Customer;
use App\Models\Enquiry;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EnquiryAccessTest extends TestCase
{
    use RefreshDatabase;

    private function makeEnquiry(): Enquiry
    {
        $customer = Customer::create(['contact_name' => 'Test Buyer']);

        return Enquiry::create(['customer_id' => $customer->id])->fresh();
    }

    public function test_guests_are_redirected_to_login(): void
    {
        $enquiry = $this->makeEnquiry();

        $this->get(route('admin.enquiries.index'))->assertRedirect(route('admin.login'));
        $this->get(route('admin.enquiries.create'))->assertRedirect(route('admin.login'));
        $this->get(route('admin.enquiries.show', $enquiry))->assertRedirect(route('admin.login'));
    }

    public function test_pages_contain_their_livewire_components(): void
    {
        $enquiry = $this->makeEnquiry();
        $this->actingAs(User::factory()->create());

        $this->get(route('admin.enquiries.index'))->assertOk()->assertSeeLivewire(EnquiryTable::class);
        $this->get(route('admin.enquiries.create'))->assertOk()->assertSeeLivewire(EnquiryForm::class);
        $this->get(route('admin.enquiries.show', $enquiry))->assertOk()->assertSeeLivewire(EnquiryShow::class)->assertSee($enquiry->reference);
    }

    public function test_an_unknown_enquiry_returns_404(): void
    {
        $this->actingAs(User::factory()->create())
            ->get(route('admin.enquiries.show', 9999))
            ->assertNotFound();
    }
}
