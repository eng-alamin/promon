<?php

declare(strict_types=1);

namespace App\Services\Admin;

use App\Enums\EnquiryStatus;
use App\Enums\ProductStatus;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Enquiry;
use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;

class DashboardService
{
    private const RECENT_ENQUIRIES_LIMIT = 5;

    /**
     * @return array{
     *     new_enquiries: int,
     *     total_enquiries: int,
     *     customers: int,
     *     active_products: int,
     *     categories: int,
     *     brands: int,
     *     advance_order_products: int,
     *     unavailable_products: int,
     *     recent_enquiries: Collection<int, Enquiry>
     * }
     */
    public function summary(): array
    {
        return [
            'new_enquiries' => Enquiry::where('status', EnquiryStatus::New->value)->count(),
            'total_enquiries' => Enquiry::count(),
            'customers' => Customer::count(),
            'active_products' => Product::active()->count(),
            'categories' => Category::active()->count(),
            'brands' => Brand::active()->count(),
            'advance_order_products' => Product::active()
                ->where('status', ProductStatus::AdvanceOrder->value)
                ->count(),
            'unavailable_products' => Product::active()
                ->where('status', ProductStatus::Unavailable->value)
                ->count(),
            'recent_enquiries' => Enquiry::with('customer')
                ->latest()
                ->latest('id')
                ->limit(self::RECENT_ENQUIRIES_LIMIT)
                ->get(),
        ];
    }
}
