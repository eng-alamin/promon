<?php

declare(strict_types=1);

namespace Tests\Feature\Site;

use App\Models\Category;
use App\Models\Faq;
use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_shows_company_details_and_payment_methods(): void
    {
        $this->get(route('site.home'))
            ->assertOk()
            ->assertSee('PROMON LTD')
            ->assertSee('13358479')
            ->assertSee('promonltd@gmail.com')
            ->assertSee('K Pharma')
            ->assertSee('Telegraphic Transfer')
            ->assertSee('GB66HBUK40180001211277');
    }

    public function test_home_about_section_has_a_more_link_to_the_about_page(): void
    {
        $this->get(route('site.home'))
            ->assertOk()
            ->assertSee('Established in 2021')
            ->assertSee(route('site.about'), false)
            ->assertDontSee('Why Choose PROMON LTD?');
    }

    public function test_about_page_shows_the_full_description(): void
    {
        $this->get(route('site.about'))
            ->assertOk()
            ->assertSee('Your Trusted UK Shopping &amp; Global Delivery Partner', false)
            ->assertSee('Established in 2021')
            ->assertSee('Medicines & Healthcare Products')
            ->assertSee('Subject to applicable import and export regulations')
            ->assertSee('Wholesale & Bulk Supply')
            ->assertSee('Why Choose PROMON LTD?')
            ->assertSee('+44 7405 124730')
            ->assertSee('13358479')
            ->assertDontSee('Since 2018');
    }

    public function test_home_page_lists_only_active_categories_and_no_prices(): void
    {
        Category::create(['name' => 'Grocery', 'slug' => 'grocery', 'sort_order' => 1, 'is_active' => true]);
        Category::create(['name' => 'Closed Group', 'slug' => 'closed', 'sort_order' => 2, 'is_active' => false]);

        $this->get(route('site.home'))
            ->assertOk()
            ->assertSee('Grocery')
            ->assertDontSee('Closed Group')
            ->assertDontSee('£');
    }

    public function test_admin_settings_override_the_default_email(): void
    {
        Setting::create(['key' => 'email', 'value' => 'hello@example.co.uk']);

        $this->get(route('site.home'))->assertOk()->assertSee('hello@example.co.uk');
    }

    public function test_old_product_and_contact_links_redirect_to_the_home_page(): void
    {
        $this->get('/products')->assertRedirect('/#products');
        $this->get('/contact')->assertRedirect('/#contact');
        $this->get('/products/some-product')->assertNotFound();
    }

    public function test_faq_page_shows_only_active_questions_in_order(): void
    {
        Faq::create(['question' => 'Second one?', 'answer' => 'B', 'sort_order' => 2, 'is_active' => true]);
        Faq::create(['question' => 'First one?', 'answer' => 'A', 'sort_order' => 1, 'is_active' => true]);
        Faq::create(['question' => 'Hidden one?', 'answer' => 'C', 'sort_order' => 3, 'is_active' => false]);

        $this->get(route('site.faq'))
            ->assertOk()
            ->assertSeeInOrder(['First one?', 'Second one?'])
            ->assertDontSee('Hidden one?');
    }

    public function test_faq_page_escapes_html_in_answers(): void
    {
        Faq::create(['question' => 'Q?', 'answer' => '<script>alert(1)</script>', 'sort_order' => 1, 'is_active' => true]);

        $this->get(route('site.faq'))->assertOk()->assertDontSee('<script>alert(1)</script>', false);
    }

    public function test_sitemap_lists_the_public_pages(): void
    {
        Category::create(['name' => 'Grocery', 'slug' => 'grocery', 'sort_order' => 1, 'is_active' => true]);

        $this->get(route('site.sitemap'))
            ->assertOk()
            ->assertSee(route('site.enquiry', ['category' => 'grocery']), false);
    }

    public function test_unknown_page_shows_the_site_404(): void
    {
        $this->get('/no-such-page')->assertNotFound()->assertSee('Page not found');
    }

    public function test_pages_are_hidden_from_search_engines_outside_production(): void
    {
        $this->get(route('site.home'))->assertSee('noindex', false);
    }
}