<?php

declare(strict_types=1);

namespace Tests\Feature\Admin;

use App\Livewire\Admin\Brands\BrandTable;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class BrandTableTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(User::factory()->create());
    }

    public function test_it_creates_a_brand_and_makes_the_slug_from_the_name(): void
    {
        Livewire::test(BrandTable::class)
            ->call('create')
            ->set('name', 'Ela De Pure')
            ->call('save')
            ->assertHasNoErrors()
            ->assertSet('showForm', false);

        $this->assertDatabaseHas('brands', ['name' => 'Ela De Pure', 'slug' => 'ela-de-pure', 'is_active' => true]);
    }

    public function test_name_must_be_unique(): void
    {
        Brand::create(['name' => 'Ela De Pure', 'slug' => 'ela-de-pure']);

        Livewire::test(BrandTable::class)
            ->call('create')
            ->set('name', 'Ela De Pure')
            ->set('slug', 'another-slug')
            ->call('save')
            ->assertHasErrors(['name' => 'unique']);
    }

    public function test_editing_keeps_its_own_name_without_a_unique_error(): void
    {
        $brand = Brand::create(['name' => 'Ela De Pure', 'slug' => 'ela-de-pure']);

        Livewire::test(BrandTable::class)
            ->call('edit', $brand->id)
            ->call('save')
            ->assertHasNoErrors();
    }

    public function test_it_toggles_active_status(): void
    {
        $brand = Brand::create(['name' => 'Ela De Pure', 'slug' => 'ela-de-pure']);

        Livewire::test(BrandTable::class)->call('toggleActive', $brand->id);

        $this->assertDatabaseHas('brands', ['id' => $brand->id, 'is_active' => false]);
    }

    public function test_it_deletes_an_unused_brand(): void
    {
        $brand = Brand::create(['name' => 'Ela De Pure', 'slug' => 'ela-de-pure']);

        Livewire::test(BrandTable::class)
            ->call('confirmDelete', $brand->id)
            ->call('delete');

        $this->assertDatabaseMissing('brands', ['id' => $brand->id]);
    }

    public function test_it_blocks_deleting_a_brand_that_has_products(): void
    {
        $category = Category::create(['name' => 'Beauty', 'slug' => 'beauty']);
        $brand = Brand::create(['name' => 'Ela De Pure', 'slug' => 'ela-de-pure']);
        Product::create([
            'category_id' => $category->id,
            'brand_id' => $brand->id,
            'name' => 'Cleanser',
            'slug' => 'cleanser',
            'price' => 7.25,
        ]);

        Livewire::test(BrandTable::class)
            ->call('confirmDelete', $brand->id)
            ->call('delete')
            ->assertDispatched('toast');

        $this->assertDatabaseHas('brands', ['id' => $brand->id]);
    }
}
