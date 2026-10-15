<?php

declare(strict_types=1);

namespace Tests\Feature\Admin;

use App\Livewire\Admin\Products\ProductForm;
use App\Livewire\Admin\Products\ProductImages;
use App\Livewire\Admin\Products\ProductPriceTiers;
use App\Livewire\Admin\Products\ProductTable;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductAccessTest extends TestCase
{
    use RefreshDatabase;

    private function makeProduct(): Product
    {
        $category = Category::create(['name' => 'Supplements', 'slug' => 'supplements']);

        return Product::create([
            'category_id' => $category->id,
            'name' => 'Vitamin C',
            'slug' => 'vitamin-c',
            'price' => 10,
        ])->fresh();
    }

    public function test_guests_are_redirected_to_login(): void
    {
        $product = $this->makeProduct();

        $this->get(route('admin.products.index'))->assertRedirect(route('admin.login'));
        $this->get(route('admin.products.create'))->assertRedirect(route('admin.login'));
        $this->get(route('admin.products.edit', $product))->assertRedirect(route('admin.login'));
    }

    public function test_products_page_contains_the_table(): void
    {
        $this->actingAs(User::factory()->create())
            ->get(route('admin.products.index'))
            ->assertOk()
            ->assertSeeLivewire(ProductTable::class);
    }

    public function test_create_page_contains_the_form(): void
    {
        $this->actingAs(User::factory()->create())
            ->get(route('admin.products.create'))
            ->assertOk()
            ->assertSeeLivewire(ProductForm::class);
    }

    public function test_edit_page_contains_the_form(): void
    {
        $product = $this->makeProduct();

        $this->actingAs(User::factory()->create())
            ->get(route('admin.products.edit', $product))
            ->assertOk()
            ->assertSeeLivewire(ProductForm::class)
            ->assertSee('Vitamin C');
    }

    public function test_a_deleted_product_cannot_be_edited(): void
    {
        $product = $this->makeProduct();
        $product->delete();

        $this->actingAs(User::factory()->create())
            ->get(route('admin.products.edit', $product->id))
            ->assertNotFound();
    }

    public function test_edit_page_has_images_and_price_tiers_but_create_page_does_not(): void
    {
        $product = $this->makeProduct();
        $this->actingAs(User::factory()->create());

        $this->get(route('admin.products.edit', $product))
            ->assertSeeLivewire(ProductImages::class)
            ->assertSeeLivewire(ProductPriceTiers::class);

        $this->get(route('admin.products.create'))
            ->assertDontSeeLivewire(ProductImages::class)
            ->assertDontSeeLivewire(ProductPriceTiers::class);
    }
}
