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
 * and no prices, so the chosen categories are written at the top of the
 * enquiry message and the enquiry has no product lines.
 */
class EnquirySubmissionService
{
    /**
     * @param  array<string, mixed>  $customer  business_name, contact_name, email, phone, whatsapp, city, country, type
     * @param  array<int, string>  $categorySlugs
     */
    public function submit(array $customer, array $categorySlugs, ?string $message): Enquiry
    {
        $names = Category::active()
            ->whereIn('slug', $categorySlugs)
            ->ordered()
            ->pluck('name')
            ->all();

        $text = 'Categories: ' . implode(', ', $names);
        $message = trim((string) $message);
        if ($message !== '') {
            $text .= "\n\n" . $message;
        }

        return DB::transaction(function () use ($customer, $text): Enquiry {
            return Enquiry::create([
                'customer_id' => $this->customer($customer)->id,
                'channel' => EnquiryChannel::Form,
                'status' => EnquiryStatus::New,
                'message' => $text,
            ]);
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
