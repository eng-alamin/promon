<?php

declare(strict_types=1);

namespace Tests\Feature\Admin;

use App\Livewire\Admin\Categories\CategoryTable;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CatalogueAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_are_redirected_to_login(): void
    {
        $this->get(route('admin.categories.index'))->assertRedirect(route('admin.login'));
    }

    public function test_categories_page_contains_the_livewire_component(): void
    {
        $this->actingAs(User::factory()->create())
            ->get(route('admin.categories.index'))
            ->assertOk()
            ->assertSeeLivewire(CategoryTable::class);
    }

    public function test_the_product_and_brand_pages_no_longer_exist(): void
    {
        $this->actingAs(User::factory()->create());

        $this->get('/admin/products')->assertNotFound();
        $this->get('/admin/products/create')->assertNotFound();
        $this->get('/admin/brands')->assertNotFound();
    }

    public function test_the_menu_has_no_products_or_brands_link(): void
    {
        $this->actingAs(User::factory()->create())
            ->get(route('admin.categories.index'))
            ->assertOk()
            ->assertDontSee('Brands')
            ->assertDontSee('Products');
    }
}
