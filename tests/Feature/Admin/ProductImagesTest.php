<?php

declare(strict_types=1);

namespace Tests\Feature\Admin;

use App\Livewire\Admin\Products\ProductImages;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class ProductImagesTest extends TestCase
{
    use RefreshDatabase;

    private Product $product;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');
        $this->actingAs(User::factory()->create());

        $category = Category::create(['name' => 'Supplements', 'slug' => 'supplements']);
        $this->product = Product::create([
            'category_id' => $category->id,
            'name' => 'Vitamin C',
            'slug' => 'vitamin-c',
            'price' => 10,
        ])->fresh();
    }

    private function upload(int $count = 1): array
    {
        $files = [];

        for ($i = 1; $i <= $count; $i++) {
            $files[] = UploadedFile::fake()->image("photo-{$i}.jpg", 600, 600);
        }

        return $files;
    }

    private function images()
    {
        return ProductImage::where('product_id', $this->product->id)->orderBy('sort_order')->get();
    }

    public function test_it_uploads_images_and_the_first_one_is_primary(): void
    {
        Livewire::test(ProductImages::class, ['product' => $this->product])
            ->set('uploads', $this->upload(2))
            ->assertHasNoErrors()
            ->assertDispatched('toast');

        $images = $this->images();

        $this->assertCount(2, $images);
        $this->assertTrue($images[0]->is_primary);
        $this->assertFalse($images[1]->is_primary);
        $this->assertSame([0, 1], $images->pluck('sort_order')->all());
        $this->assertSame('Vitamin C', $images[0]->alt_text);

        foreach ($images as $image) {
            Storage::disk('public')->assertExists($image->path);
        }
    }

    public function test_it_rejects_files_that_are_not_images(): void
    {
        Livewire::test(ProductImages::class, ['product' => $this->product])
            ->set('uploads', [UploadedFile::fake()->create('price-list.pdf', 10, 'application/pdf')])
            ->assertHasErrors('uploads');

        $this->assertCount(0, $this->images());
    }

    public function test_it_rejects_images_bigger_than_2_mb(): void
    {
        Livewire::test(ProductImages::class, ['product' => $this->product])
            ->set('uploads', [UploadedFile::fake()->image('big.jpg')->size(3000)])
            ->assertHasErrors('uploads');

        $this->assertCount(0, $this->images());
    }

    public function test_it_stops_at_ten_images(): void
    {
        $component = Livewire::test(ProductImages::class, ['product' => $this->product])
            ->set('uploads', $this->upload(10));

        $this->assertCount(10, $this->images());

        $component->set('uploads', $this->upload(1))->assertHasErrors('uploads');

        $this->assertCount(10, $this->images());
    }

    public function test_it_changes_the_primary_image(): void
    {
        Livewire::test(ProductImages::class, ['product' => $this->product])
            ->set('uploads', $this->upload(2));

        $second = $this->images()[1];

        Livewire::test(ProductImages::class, ['product' => $this->product])
            ->call('setPrimary', $second->id);

        $this->assertTrue($second->fresh()->is_primary);
        $this->assertSame(1, ProductImage::where('product_id', $this->product->id)->where('is_primary', true)->count());
    }

    public function test_it_reorders_images(): void
    {
        Livewire::test(ProductImages::class, ['product' => $this->product])
            ->set('uploads', $this->upload(3));

        [$a, $b, $c] = $this->images()->all();

        $component = Livewire::test(ProductImages::class, ['product' => $this->product]);

        $component->call('moveDown', $a->id);
        $this->assertSame([$b->id, $a->id, $c->id], $this->images()->pluck('id')->all());

        $component->call('moveUp', $c->id);
        $this->assertSame([$b->id, $c->id, $a->id], $this->images()->pluck('id')->all());

        // The first image cannot move up: nothing changes.
        $component->call('moveUp', $b->id);
        $this->assertSame([$b->id, $c->id, $a->id], $this->images()->pluck('id')->all());
    }

    public function test_deleting_removes_the_file_and_gives_the_primary_flag_to_the_next_image(): void
    {
        Livewire::test(ProductImages::class, ['product' => $this->product])
            ->set('uploads', $this->upload(2));

        [$first, $second] = $this->images()->all();

        Livewire::test(ProductImages::class, ['product' => $this->product])
            ->call('delete', $first->id)
            ->assertDispatched('toast');

        Storage::disk('public')->assertMissing($first->path);
        $this->assertDatabaseMissing('product_images', ['id' => $first->id]);
        $this->assertTrue($second->fresh()->is_primary);
    }

    public function test_images_of_another_product_cannot_be_changed(): void
    {
        $other = Product::create([
            'category_id' => $this->product->category_id,
            'name' => 'Fish Oil',
            'slug' => 'fish-oil',
            'price' => 8,
        ])->fresh();

        Livewire::test(ProductImages::class, ['product' => $other])
            ->set('uploads', $this->upload(1));

        $foreign = ProductImage::where('product_id', $other->id)->firstOrFail();

        Livewire::test(ProductImages::class, ['product' => $this->product])
            ->call('delete', $foreign->id)
            ->assertNotFound();

        $this->assertDatabaseHas('product_images', ['id' => $foreign->id]);
    }
}
