<?php

declare(strict_types=1);

namespace App\Services\Site;

use App\Enums\EnquiryChannel;
use App\Enums\EnquiryStatus;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Enquiry;
use Illuminate\Support\Facades\DB;

/**
 * Saves an enquiry sent from the public website. The website has no products
 * and no prices: an enquiry has the chosen categories and the visitor's message.
 */
class EnquirySubmissionService
{
    /**
     * @param  array<string, mixed>  $customer  business_name, contact_name, email, phone, whatsapp, city, country, type
     * @param  array<int, string>  $categorySlugs
     */
    public function submit(array $customer, array $categorySlugs, ?string $message): Enquiry
    {
        $categoryIds = Category::active()
            ->whereIn('slug', $categorySlugs)
            ->pluck('id')
            ->all();

        $message = trim((string) $message);

        return DB::transaction(function () use ($customer, $categoryIds, $message): Enquiry {
            $enquiry = Enquiry::create([
                'customer_id' => $this->customer($customer)->id,
                'channel' => EnquiryChannel::Form,
                'status' => EnquiryStatus::New,
                'message' => $message === '' ? null : $message,
            ]);

            $enquiry->categories()->sync($categoryIds);

            return $enquiry;
        });
    }

    /**
     * Reuse a customer with the same email (or phone/WhatsApp when there is no email).
     * An existing customer is never changed from public input; staff keep their data.
     *
     * @param  array<string, mixed>  $data
     */
    private function customer(array $data): Customer
    {
        $existing = null;

        if (! empty($data['email'])) {
            $existing = Customer::where('email', $data['email'])->first();
        } else {
            foreach (['whatsapp', 'phone'] as $field) {
                if (! empty($data[$field])) {
                    $existing = Customer::where($field, $data[$field])->first();
                    break;
                }
            }
        }

        return $existing ?? Customer::create($data);
    }
}
