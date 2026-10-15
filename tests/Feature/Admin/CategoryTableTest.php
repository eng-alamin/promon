<?php

declare(strict_types=1);

namespace Tests\Feature\Admin;

use App\Livewire\Admin\Categories\CategoryTable;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class CategoryTableTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(User::factory()->create());
    }

    public function test_it_creates_a_category_and_makes_the_slug_from_the_name(): void
    {
        Livewire::test(CategoryTable::class)
            ->call('create')
            ->set('name', 'Baby Food & Milk')
            ->call('save')
            ->assertHasNoErrors()
            ->assertSet('showForm', false)
            ->assertDispatched('toast');

        $this->assertDatabaseHas('categories', [
            'name' => 'Baby Food & Milk',
            'slug' => 'baby-food-milk',
            'sort_order' => 1,
            'is_active' => true,
        ]);
    }

    public function test_name_is_required(): void
    {
        Livewire::test(CategoryTable::class)
            ->call('create')
            ->set('name', '')
            ->call('save')
            ->assertHasErrors(['name' => 'required']);
    }

    public function test_name_must_be_unique(): void
    {
        Category::create(['name' => 'Snacks', 'slug' => 'snacks']);

        Livewire::test(CategoryTable::class)
            ->call('create')
            ->set('name', 'Snacks')
            ->set('slug', 'snacks-two')
            ->call('save')
            ->assertHasErrors(['name' => 'unique']);
    }

    public function test_editing_keeps_its_own_name_without_a_unique_error(): void
    {
        $category = Category::create(['name' => 'Snacks', 'slug' => 'snacks']);

        Livewire::test(CategoryTable::class)
            ->call('edit', $category->id)
            ->assertSet('name', 'Snacks')
            ->call('save')
            ->assertHasNoErrors();
    }

    public function test_it_updates_a_category(): void
    {
        $category = Category::create(['name' => 'Snacks', 'slug' => 'snacks']);

        Livewire::test(CategoryTable::class)
            ->call('edit', $category->id)
            ->set('name', 'Sweet Snacks')
            ->set('slug', 'sweet-snacks')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'name' => 'Sweet Snacks',
            'slug' => 'sweet-snacks',
        ]);
    }

    public function test_it_toggles_active_status(): void
    {
        $category = Category::create(['name' => 'Snacks', 'slug' => 'snacks', 'is_active' => true]);

        Livewire::test(CategoryTable::class)->call('toggleActive', $category->id);

        $this->assertDatabaseHas('categories', ['id' => $category->id, 'is_active' => false]);
    }

    public function test_it_deletes_an_empty_category(): void
    {
        $category = Category::create(['name' => 'Snacks', 'slug' => 'snacks']);

        Livewire::test(CategoryTable::class)
            ->call('confirmDelete', $category->id)
            ->assertSet('deletingId', $category->id)
            ->call('delete')
            ->assertSet('deletingId', null);

        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }

    public function test_it_blocks_deleting_a_category_that_has_products(): void
    {
        $category = Category::create(['name' => 'Snacks', 'slug' => 'snacks']);
        Product::create(['category_id' => $category->id, 'name' => 'Chips', 'slug' => 'chips', 'price' => 1.5]);

        Livewire::test(CategoryTable::class)
            ->call('confirmDelete', $category->id)
            ->call('delete')
            ->assertDispatched('toast');

        $this->assertDatabaseHas('categories', ['id' => $category->id]);
    }

    public function test_it_blocks_deleting_a_category_that_only_has_soft_deleted_products(): void
    {
        $category = Category::create(['name' => 'Snacks', 'slug' => 'snacks']);
        $product = Product::create(['category_id' => $category->id, 'name' => 'Chips', 'slug' => 'chips', 'price' => 1.5]);
        $product->delete();

        Livewire::test(CategoryTable::class)
            ->call('confirmDelete', $category->id)
            ->call('delete');

        $this->assertDatabaseHas('categories', ['id' => $category->id]);
    }

    public function test_search_filters_the_list(): void
    {
        Category::create(['name' => 'Snacks', 'slug' => 'snacks']);
        Category::create(['name' => 'Drinks', 'slug' => 'drinks']);

        Livewire::test(CategoryTable::class)
            ->set('search', 'Snack')
            ->assertSee('Snacks')
            ->assertDontSee('Drinks');
    }
}
