<?php

declare(strict_types=1);

namespace App\Services\Admin;

use App\Enums\EnquiryStatus;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Enquiry;
use Illuminate\Database\Eloquent\Collection;

class DashboardService
{
    private const RECENT_ENQUIRIES_LIMIT = 5;

    /**
     * @return array{
     *     new_enquiries: int,
     *     total_enquiries: int,
     *     customers: int,
     *     categories: int,
     *     recent_enquiries: Collection<int, Enquiry>
     * }
     */
    public function summary(): array
    {
        return [
            'new_enquiries' => Enquiry::where('status', EnquiryStatus::New->value)->count(),
            'total_enquiries' => Enquiry::count(),
            'customers' => Customer::count(),
            'categories' => Category::active()->count(),
            'recent_enquiries' => Enquiry::with('customer')
                ->latest()
                ->latest('id')
                ->limit(self::RECENT_ENQUIRIES_LIMIT)
                ->get(),
        ];
    }
}
