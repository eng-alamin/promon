<?php

declare(strict_types=1);

namespace Tests\Feature\Admin;

use App\Enums\UserRole;
use App\Livewire\Admin\Enquiries\EnquiryShow;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Enquiry;
use App\Models\User;
use App\Services\Admin\CategoryService;
use DomainException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class EnquiryDeleteTest extends TestCase
{
    use RefreshDatabase;

    private Enquiry $enquiry;

    private Category $category;

    protected function setUp(): void
    {
        parent::setUp();

        $customer = Customer::create(['contact_name' => 'Rahim Uddin', 'phone' => '01711111111']);
        $this->category = Category::create(['name' => 'Supplements', 'slug' => 'supplements']);

        $this->enquiry = Enquiry::create(['customer_id' => $customer->id])->fresh();
        $this->enquiry->categories()->sync([$this->category->id]);
    }

    public function test_a_super_admin_can_delete_an_enquiry_with_its_category_links(): void
    {
        $this->actingAs(User::factory()->create(['role' => UserRole::SuperAdmin]));

        Livewire::test(EnquiryShow::class, ['enquiry' => $this->enquiry])
            ->call('confirmDelete')
            ->assertSet('confirmingDelete', true)
            ->call('delete')
            ->assertRedirect(route('admin.enquiries.index'));

        $this->assertDatabaseMissing('enquiries', ['id' => $this->enquiry->id]);
        $this->assertDatabaseMissing('category_enquiry', ['enquiry_id' => $this->enquiry->id]);
        $this->assertDatabaseHas('customers', ['contact_name' => 'Rahim Uddin']);
        $this->assertDatabaseHas('categories', ['slug' => 'supplements']);
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

    public function test_a_category_used_by_an_enquiry_cannot_be_deleted(): void
    {
        $this->expectException(DomainException::class);

        try {
            app(CategoryService::class)->delete($this->category);
        } finally {
            $this->assertDatabaseHas('categories', ['id' => $this->category->id]);
        }
    }
}
