<?php

declare(strict_types=1);

namespace Tests\Feature\Site;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductPriceTier;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CatalogueTest extends TestCase
{
    use RefreshDatabase;

    private function makeCategory(string $name, bool $active = true): Category
    {
        return Category::create(['name' => $name, 'slug' => str($name)->slug()->toString(), 'sort_order' => 1, 'is_active' => $active]);
    }

    private function makeProduct(Category $category, string $name, bool $active = true, string $price = '9.50'): Product
    {
        return Product::create([
            'category_id' => $category->id,
            'name' => $name,
            'slug' => str($name)->slug()->toString(),
            'price' => $price,
            'status' => 'in_stock',
            'is_active' => $active,
            'is_popular' => true,
            'is_new' => true,
            'sort_order' => 1,
        ]);
    }

    public function test_home_shows_active_categories_and_products_only(): void
    {
        $shown = $this->makeCategory('Supplements');
        $hidden = $this->makeCategory('Secret Group', false);
        $this->makeProduct($shown, 'Vitamin C');
        $this->makeProduct($shown, 'Hidden Item', false);
        $this->makeProduct($hidden, 'Inactive Category Item');

        $this->get(route('site.home'))
            ->assertOk()
            ->assertSee('Supplements')
            ->assertSee('Vitamin C')
            ->assertDontSee('Secret Group')
            ->assertDontSee('Hidden Item')
            ->assertDontSee('Inactive Category Item');
    }

    public function test_shop_filters_by_category_and_search(): void
    {
        $a = $this->makeCategory('Grocery');
        $b = $this->makeCategory('Chocolate');
        $this->makeProduct($a, 'Tea Bags');
        $this->makeProduct($b, 'Dark Bar');

        $this->get(route('site.shop', ['category' => 'grocery']))->assertOk()->assertSee('Tea Bags')->assertDontSee('Dark Bar');
        $this->get(route('site.shop', ['q' => 'Dark']))->assertOk()->assertSee('Dark Bar')->assertDontSee('Tea Bags');
    }

    public function test_shop_ignores_unknown_category_and_sort_values(): void
    {
        $this->makeProduct($this->makeCategory('Grocery'), 'Tea Bags');

        $this->get(route('site.shop', ['category' => 'nope', 'sort' => 'drop-table']))->assertOk()->assertSee('Tea Bags');
    }

    public function test_search_treats_percent_sign_as_text(): void
    {
        $category = $this->makeCategory('Grocery');
        $this->makeProduct($category, 'Tea Bags');

        $this->get(route('site.shop', ['q' => '%']))->assertOk()->assertDontSee('Tea Bags');
    }

    public function test_product_page_shows_price_and_tiers(): void
    {
        $product = $this->makeProduct($this->makeCategory('Grocery'), 'Tea Bags');
        ProductPriceTier::create(['product_id' => $product->id, 'min_qty' => 12, 'price' => '8.75']);

        $this->get(route('site.product', 'tea-bags'))
            ->assertOk()
            ->assertSee('£9.50')
            ->assertSee('12+')
            ->assertSee('£8.75');
    }

    public function test_inactive_deleted_or_inactive_category_products_return_404(): void
    {
        $category = $this->makeCategory('Grocery');
        $this->makeProduct($category, 'Off Item', false);
        $deleted = $this->makeProduct($category, 'Deleted Item');
        $deleted->delete();
        $this->makeProduct($this->makeCategory('Closed', false), 'Closed Item');

        $this->get(route('site.product', 'off-item'))->assertNotFound();
        $this->get(route('site.product', 'deleted-item'))->assertNotFound();
        $this->get(route('site.product', 'closed-item'))->assertNotFound();
    }
}
