<?php

declare(strict_types=1);

namespace Tests\Feature\Admin;

use App\Enums\UserRole;
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

class EnquiryDeleteTest extends TestCase
{
    use RefreshDatabase;

    private Enquiry $enquiry;

    protected function setUp(): void
    {
        parent::setUp();

        $customer = Customer::create(['contact_name' => 'Rahim Uddin', 'phone' => '01711111111']);
        $category = Category::create(['name' => 'Supplements', 'slug' => 'supplements']);
        $product = Product::create(['category_id' => $category->id, 'name' => 'Fish Oil', 'slug' => 'fish-oil', 'price' => 7.25]);

        $this->enquiry = Enquiry::create(['customer_id' => $customer->id])->fresh();
        EnquiryItem::create(['enquiry_id' => $this->enquiry->id, 'product_id' => $product->id, 'quantity' => 12, 'unit_price' => 7.25]);
    }

    public function test_a_super_admin_can_delete_an_enquiry_with_its_items(): void
    {
        $this->actingAs(User::factory()->create(['role' => UserRole::SuperAdmin]));

        Livewire::test(EnquiryShow::class, ['enquiry' => $this->enquiry])
            ->call('confirmDelete')
            ->assertSet('confirmingDelete', true)
            ->call('delete')
            ->assertRedirect(route('admin.enquiries.index'));

        $this->assertDatabaseMissing('enquiries', ['id' => $this->enquiry->id]);
        $this->assertDatabaseMissing('enquiry_items', ['enquiry_id' => $this->enquiry->id]);
        $this->assertDatabaseHas('customers', ['contact_name' => 'Rahim Uddin']);
        $this->assertDatabaseHas('products', ['slug' => 'fish-oil']);
    }

    public function test_cancel_keeps_the_enquiry(): void
    {
        $this->actingAs(User::factory()->create(['role' => UserRole::SuperAdmin]));

        Livewire::test(EnquiryShow::class, ['enquiry' => $this->enquiry])
            ->call('confirmDelete')
            ->call('cancelDelete')
            ->assertSet('confirmingDelete', false);

        $this->assertDatabaseHas('enquiries', ['id' => $this->enquiry->id]);
    }

    public function test_staff_do_not_see_the_delete_button(): void
    {
        $this->actingAs(User::factory()->create(['role' => UserRole::Staff]))
            ->get(route('admin.enquiries.show', $this->enquiry))
            ->assertOk()
            ->assertDontSee('Delete enquiry');
    }

    public function test_staff_cannot_delete_even_by_calling_the_action(): void
    {
        $this->actingAs(User::factory()->create(['role' => UserRole::Staff]));

        Livewire::test(EnquiryShow::class, ['enquiry' => $this->enquiry])
            ->call('delete')
            ->assertForbidden();

        $this->assertDatabaseHas('enquiries', ['id' => $this->enquiry->id]);
    }
}
