<?php

declare(strict_types=1);

namespace App\Services\Admin;

use App\Enums\EnquiryChannel;
use App\Enums\EnquiryStatus;
use App\Models\Customer;
use App\Models\Enquiry;
use App\Models\EnquiryItem;
use App\Models\Product;
use App\Models\User;
use App\Services\PriceResolver;
use Illuminate\Support\Facades\DB;

class EnquiryService
{
    public function __construct(private readonly PriceResolver $prices)
    {
    }

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
     * Delete an enquiry and its product list for good. Only super admins may call this
     * (checked in the Livewire action). Staff use the Closed status instead.
     */
    public function delete(Enquiry $enquiry): void
    {
        DB::transaction(function () use ($enquiry): void {
            $enquiry->items()->delete();
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
     * The unit price of every item is saved as it is in the catalogue today.
     *
     * @param  Customer|array<string, mixed>  $customer  An existing customer, or the data of a new one
     * @param  array<int, array{product_id: int|string, quantity: int|string}>  $items
     */
    public function createManual(Customer|array $customer, array $items, ?string $message, User $handler): Enquiry
    {
        return DB::transaction(function () use ($customer, $items, $message, $handler): Enquiry {
            $customerModel = $customer instanceof Customer ? $customer : Customer::create($customer);

            $enquiry = Enquiry::create([
                'customer_id' => $customerModel->id,
                'handled_by' => $handler->id,
                'channel' => EnquiryChannel::Manual,
                'status' => EnquiryStatus::New,
                'message' => $message,
            ]);

            $products = Product::whereIn('id', array_column($items, 'product_id'))->get()->keyBy('id');

            foreach ($items as $item) {
                $product = $products->get((int) $item['product_id']);
                $quantity = (int) $item['quantity'];

                EnquiryItem::create([
                    'enquiry_id' => $enquiry->id,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'unit_price' => $this->prices->unitPrice($product, $quantity),
                ]);
            }

            return $enquiry;
        });
    }
}
