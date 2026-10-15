<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Enquiries;

use App\Enums\EnquiryChannel;
use App\Enums\EnquiryStatus;
use App\Livewire\Concerns\WithSearch;
use App\Models\Enquiry;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class EnquiryTable extends Component
{
    use WithPagination;
    use WithSearch;

    private const PER_PAGE = 10;

    public string $statusFilter = '';

    public string $channelFilter = '';

    public function updatingStatusFilter(): void
    {
        $this->resetPage();
    }

    public function updatingChannelFilter(): void
    {
        $this->resetPage();
    }

    public function render(): View
    {
        $term = $this->likeTerm();

        $enquiries = Enquiry::query()
            ->with('customer')
            ->withCount('items')
            ->when(trim($this->search) !== '', function ($query) use ($term) {
                $query->where(function ($inner) use ($term) {
                    $inner->where('reference', 'like', $term)
                        ->orWhereHas('customer', function ($customer) use ($term) {
                            $customer->where(function ($c) use ($term) {
                                $c->where('business_name', 'like', $term)
                                    ->orWhere('contact_name', 'like', $term)
                                    ->orWhere('phone', 'like', $term)
                                    ->orWhere('whatsapp', 'like', $term)
                                    ->orWhere('email', 'like', $term);
                            });
                        });
                });
            })
            ->when(EnquiryStatus::tryFrom($this->statusFilter) !== null, fn ($query) => $query->where('status', $this->statusFilter))
            ->when(EnquiryChannel::tryFrom($this->channelFilter) !== null, fn ($query) => $query->where('channel', $this->channelFilter))
            ->latest()
            ->latest('id')
            ->paginate(self::PER_PAGE);

        return view('livewire.admin.enquiries.enquiry-table', [
            'enquiries' => $enquiries,
            'statuses' => EnquiryStatus::cases(),
            'channels' => EnquiryChannel::cases(),
        ]);
    }
}
