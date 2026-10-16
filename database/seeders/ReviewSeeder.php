<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Review;
use Illuminate\Database\Seeder;

/**
 * 10 sample reviews so the Reviews section is never empty on a fresh install.
 * Reviews are matched by customer name, so running this again never creates duplicates
 * and never overwrites anything an admin has edited.
 * These are SAMPLE texts. Replace them with real customer reviews in Admin > Reviews before launch.
 */
class ReviewSeeder extends Seeder
{
    /** name, title, rating, review */
    private const REVIEWS = [
        ['Rahim Uddin', 'Retail shop owner, Dhaka', 5, 'We order baby food and groceries every month. The products are genuine and the parcel reached Dhaka in the time they promised.'],
        ['Nusrat Jahan', 'Online seller, Chattogram', 5, 'I sell UK skincare on my page and my customers trust the products because they are original. The team answers every message on WhatsApp.'],
        ['Md. Kamal Hossain', 'Wholesale buyer, Uttara, Dhaka', 4, 'Good wholesale support. They sent the product list and quantities clearly, and the packing was neat and safe.'],
        ['Farzana Akter', 'Personal customer, Sylhet', 5, 'I asked for a UK product that I could not find anywhere in Bangladesh. They sourced it for me and kept me updated until it arrived.'],
        ['Tanvir Ahmed', 'Pharmacy owner, Dhaka', 5, 'Fast replies and very clear about the import rules for healthcare products. I felt comfortable ordering with them.'],
        ['Sabrina Rahman', 'Gift buyer, Manchester, UK', 5, 'I send gifts to my family in Bangladesh every Eid. Ordering is simple and my family always receives everything in good condition.'],
        ['Imran Hasan', 'Reseller, Khulna', 4, 'Genuine products and honest communication. Delivery depends on customs, but they told me this from the start.'],
        ['Mahmuda Begum', 'Boutique owner, Rajshahi', 5, 'I bought bags and fashion accessories for my boutique. The quality matched the photos and my customers were happy.'],
        ['Shafiq Alam', 'Distributor, Cumilla', 4, 'The pre-order service is useful. I sent the product name and they arranged it from the UK without any confusion.'],
        ['Ayesha Siddiqua', 'Personal customer, Dhaka', 5, 'Friendly team and easy process. I send an enquiry, they reply with the details, and the order arrives well packed.'],
    ];

    public function run(): void
    {
        foreach (self::REVIEWS as $index => [$name, $title, $rating, $body]) {
            Review::firstOrCreate(
                ['reviewer_name' => $name],
                [
                    'reviewer_title' => $title,
                    'rating' => $rating,
                    'body' => $body,
                    'sort_order' => $index + 1,
                    'is_active' => true,
                ],
            );
        }
    }
}
