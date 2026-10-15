<?php

declare(strict_types=1);

namespace Tests\Feature\Site;

use App\Models\Category;
use App\Models\Customer;
use App\Models\Enquiry;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EnquirySubmitTest extends TestCase
{
    use RefreshDatabase;

    private function category(string $slug = 'grocery', string $name = 'Grocery', bool $active = true): Category
    {
        return Category::create(['name' => $name, 'slug' => $slug, 'sort_order' => 1, 'is_active' => $active]);
    }

    /** @param array<string, mixed> $over */
    private function payload(array $over = []): array
    {
        return array_merge([
            'business_name' => 'Dhaka Mart', 'contact_name' => 'Rahim', 'type' => 'retailer',
            'email' => 'rahim@example.com', 'categories' => ['grocery'],
        ], $over);
    }

    public function test_enquiry_page_ticks_the_category_from_the_link(): void
    {
        $this->category();
        $this->category('chocolate', 'Chocolate');

        $this->get(route('site.enquiry', ['category' => 'grocery']))
            ->assertOk()
            ->assertSee('value="grocery" checked', false)
            ->assertDontSee('value="chocolate" checked', false);
    }

    public function test_valid_enquiry_is_saved_with_the_chosen_categories(): void
    {
        $this->category();
        $this->category('chocolate', 'Chocolate');

        $this->post(route('site.enquiry.submit'), $this->payload([
            'categories' => ['grocery', 'chocolate'], 'message' => '50 cartons of tea',
        ]))->assertRedirect(route('site.enquiry.thanks'));

        $enquiry = Enquiry::firstOrFail();
        $this->assertSame('form', $enquiry->channel->value);
        $this->assertSame('new', $enquiry->status->value);
        $this->assertStringContainsString('Categories: Chocolate, Grocery', (string) $enquiry->message);
        $this->assertStringContainsString('50 cartons of tea', (string) $enquiry->message);
        $this->assertSame(0, $enquiry->items()->count());
        $this->assertSame('Dhaka Mart', $enquiry->customer->business_name);
    }

    public function test_at_least_one_category_is_required(): void
    {
        $this->category();

        $this->post(route('site.enquiry.submit'), $this->payload(['categories' => []]))
            ->assertSessionHasErrors('categories');
        $this->assertSame(0, Enquiry::count());
    }

    public function test_hidden_or_unknown_categories_are_rejected(): void
    {
        $this->category('closed', 'Closed', false);

        $this->post(route('site.enquiry.submit'), $this->payload(['categories' => ['closed']]))
            ->assertSessionHasErrors('categories.0');
        $this->post(route('site.enquiry.submit'), $this->payload(['categories' => ['nope']]))
            ->assertSessionHasErrors('categories.0');
        $this->assertSame(0, Enquiry::count());
    }

    public function test_a_way_to_reply_is_required(): void
    {
        $this->category();

        $this->post(route('site.enquiry.submit'), $this->payload(['email' => null]))
            ->assertSessionHasErrors('email');
    }

    public function test_honeypot_blocks_bots(): void
    {
        $this->category();

        $this->post(route('site.enquiry.submit'), $this->payload(['website' => 'http://spam.example']))
            ->assertSessionHasErrors('website');
        $this->assertSame(0, Enquiry::count());
    }

    public function test_an_existing_customer_is_reused_by_email(): void
    {
        $this->category();

        $this->post(route('site.enquiry.submit'), $this->payload());
        $this->post(route('site.enquiry.submit'), $this->payload(['contact_name' => 'Someone else']));

        $this->assertSame(1, Customer::count());
        $this->assertSame(2, Enquiry::count());
        $this->assertSame('Rahim', Customer::first()->contact_name);
    }

    public function test_thanks_page_needs_a_fresh_submission(): void
    {
        $this->get(route('site.enquiry.thanks'))->assertRedirect(route('site.enquiry'));
    }
}
