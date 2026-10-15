<?php

declare(strict_types=1);

namespace Tests\Feature\Admin;

use App\Livewire\Admin\Brands\BrandTable;
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
        $this->get(route('admin.brands.index'))->assertRedirect(route('admin.login'));
    }

    public function test_categories_page_contains_the_livewire_component(): void
    {
        $this->actingAs(User::factory()->create())
            ->get(route('admin.categories.index'))
            ->assertOk()
            ->assertSeeLivewire(CategoryTable::class);
    }

    public function test_brands_page_contains_the_livewire_component(): void
    {
        $this->actingAs(User::factory()->create())
            ->get(route('admin.brands.index'))
            ->assertOk()
            ->assertSeeLivewire(BrandTable::class);
    }
}
