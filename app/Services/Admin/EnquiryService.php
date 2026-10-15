<?php

declare(strict_types=1);

namespace App\Services\Admin;

use App\Enums\EnquiryChannel;
use App\Enums\EnquiryStatus;
use App\Models\Customer;
use App\Models\Enquiry;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class EnquiryService
{
    /**
     * Change the status. The first staff member who moves a new enquiry on becomes its handler.
     */
    public function changeStatus(Enquiry $enquiry, EnquiryStatus $status, User $user): void
    {
        $data = ['status' => $status];

        if ($enquiry->handled_by === null && $status !== EnquiryStatus::New) {
            $data['handled_by'] = $user->id;
        }

        $enquiry->update($data);
    }

    /**
     * Delete an enquiry and its category links for good. Only super admins may call this
     * (checked in the Livewire action). Staff use the Closed status instead.
     */
    public function delete(Enquiry $enquiry): void
    {
        DB::transaction(function () use ($enquiry): void {
            $enquiry->categories()->detach();
            $enquiry->delete();
        });
    }

    public function saveNotes(Enquiry $enquiry, ?string $notes): void
    {
        $enquiry->update(['admin_notes' => $notes]);
    }

    public function assign(Enquiry $enquiry, User $user): void
    {
        $enquiry->update(['handled_by' => $user->id]);
    }

    /**
     * Create an enquiry typed in by staff (for example a phone call).
     * An enquiry has only categories and a message: no products, prices or totals.
     *
     * @param  Customer|array<string, mixed>  $customer  An existing customer, or the data of a new one
     * @param  array<int, int|string>  $categoryIds
     */
    public function createManual(Customer|array $customer, array $categoryIds, ?string $message, User $handler): Enquiry
    {
        return DB::transaction(function () use ($customer, $categoryIds, $message, $handler): Enquiry {
            $customerModel = $customer instanceof Customer ? $customer : Customer::create($customer);

            $enquiry = Enquiry::create([
                'customer_id' => $customerModel->id,
                'handled_by' => $handler->id,
                'channel' => EnquiryChannel::Manual,
                'status' => EnquiryStatus::New,
                'message' => $message,
            ]);

            $enquiry->categories()->sync(array_values(array_unique(array_map('intval', $categoryIds))));

            return $enquiry;
        });
    }
}
