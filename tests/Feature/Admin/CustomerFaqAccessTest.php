<?php

declare(strict_types=1);

namespace Tests\Feature\Admin;

use App\Livewire\Admin\Customers\CustomerTable;
use App\Livewire\Admin\Faqs\FaqTable;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerFaqAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_are_redirected_to_login(): void
    {
        $this->get(route('admin.customers.index'))->assertRedirect(route('admin.login'));
        $this->get(route('admin.faqs.index'))->assertRedirect(route('admin.login'));
    }

    public function test_customers_page_contains_the_livewire_component(): void
    {
        $this->actingAs(User::factory()->create())
            ->get(route('admin.customers.index'))
            ->assertOk()
            ->assertSeeLivewire(CustomerTable::class);
    }

    public function test_faqs_page_contains_the_livewire_component(): void
    {
        $this->actingAs(User::factory()->create())
            ->get(route('admin.faqs.index'))
            ->assertOk()
            ->assertSeeLivewire(FaqTable::class);
    }

    public function test_menu_links_to_the_new_pages(): void
    {
        $this->actingAs(User::factory()->create())
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee(route('admin.customers.index'))
            ->assertSee(route('admin.faqs.index'));
    }
}
