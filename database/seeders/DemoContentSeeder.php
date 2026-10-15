<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Faq;
use App\Models\Setting;
use Illuminate\Database\Seeder;

/**
 * Company settings and FAQ answers from the static site.
 * Existing settings and FAQs (matched by key / question) are left untouched, so admin edits are kept.
 * The three FAQs whose answers are still placeholders are saved as INACTIVE, so visitors never see
 * "[Add ...]" text. Write the real answer in Admin > FAQs, then switch them on.
 */
class DemoContentSeeder extends Seeder
{
    /** question, answer, active */
    private const FAQS = [
        ['What currency are your prices in?', 'Every price is in pounds sterling (£ GBP).', true],
        ['Who can order from Promon?', 'We welcome wholesalers and retailers.', true],
        ['How do I find a product category?', 'Open the Products page and use the category buttons above the products. You can also choose a category from the home page.', true],
        ['How do I place an order?', 'This website has no online checkout. Message us on WhatsApp or through the contact page with the products and quantities you need.', true],
        ['Can I place an advance order?', 'Yes, advance orders are available. Message us with the products you need.', true],
        ['How long does delivery take?', 'Products are sent from the UK by air cargo. Delivery time: 10 working days.', true],
        ['What are the delivery charges, customs and import duty?', '[Add delivery charges and the customs and import duty details.]', false],
        ['What is your returns policy?', '[Add the returns window, condition requirements and how to start a return.]', false],
        ['Which payment methods do you accept?', '[Add the accepted payment methods.]', false],
        ['How can I contact you?', 'Use the WhatsApp number on this site, or send an enquiry from the Enquiry list page.', true],
    ];

    /** Email, phone, address and opening hours are left out: nothing is invented. */
    private const SETTINGS = [
        'company_name' => 'Promon',
        'tagline' => 'UK Products | Bangladesh',
        'whatsapp' => '+447405124730',
        'delivery_time' => 'Delivery time: 10 working days.',
    ];

    public function run(): void
    {
        foreach (self::SETTINGS as $key => $value) {
            Setting::firstOrCreate(['key' => $key], ['value' => $value]);
        }

        foreach (self::FAQS as $index => [$question, $answer, $active]) {
            Faq::firstOrCreate(
                ['question' => $question],
                ['answer' => $answer, 'sort_order' => $index + 1, 'is_active' => $active],
            );
        }
    }
}
