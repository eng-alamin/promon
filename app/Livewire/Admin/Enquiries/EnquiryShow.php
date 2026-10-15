<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Enquiries;

use App\Enums\EnquiryStatus;
use App\Livewire\Concerns\RequiresSuperAdmin;
use App\Models\Enquiry;
use App\Services\Admin\EnquiryService;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Locked;
use Livewire\Component;

class EnquiryShow extends Component
{
    use RequiresSuperAdmin;

    #[Locked]
    public int $enquiryId;

    public ?string $admin_notes = null;

    public bool $confirmingDelete = false;

    public function mount(Enquiry $enquiry): void
    {
        $this->enquiryId = $enquiry->id;
        $this->admin_notes = $enquiry->admin_notes;
    }

    public function setStatus(string $value, EnquiryService $enquiries): void
    {
        $status = EnquiryStatus::tryFrom($value);

        if ($status === null) {
            $this->addError('status', 'Please choose a valid status.');

            return;
        }

        $this->resetErrorBag('status');
        $enquiries->changeStatus($this->enquiry(), $status, auth()->user());
        $this->dispatch('toast', message: 'Status updated.');
    }

    public function saveNotes(EnquiryService $enquiries): void
    {
        $this->validate(['admin_notes' => ['nullable', 'string', 'max:5000']]);

        $notes = $this->admin_notes === null ? '' : trim($this->admin_notes);
        $this->admin_notes = $notes === '' ? null : $notes;

        $enquiries->saveNotes($this->enquiry(), $this->admin_notes);
        $this->dispatch('toast', message: 'Notes saved.');
    }

    public function assignToMe(EnquiryService $enquiries): void
    {
        $enquiries->assign($this->enquiry(), auth()->user());
        $this->dispatch('toast', message: 'You are now handling this enquiry.');
    }

    public function confirmDelete(): void
    {
        $this->authorizeSuperAdmin();

        $this->confirmingDelete = true;
    }

    public function cancelDelete(): void
    {
        $this->confirmingDelete = false;
    }

    public function delete(EnquiryService $enquiries): void
    {
        $this->authorizeSuperAdmin();

        $enquiries->delete($this->enquiry());

        session()->flash('success', 'Enquiry deleted.');

        $this->redirectRoute('admin.enquiries.index');
    }

    public function render(): View
    {
        $enquiry = Enquiry::with(['customer', 'handler', 'items.product'])->findOrFail($this->enquiryId);

        return view('livewire.admin.enquiries.enquiry-show', [
            'enquiry' => $enquiry,
            'statuses' => EnquiryStatus::cases(),
            'total' => $enquiry->items->sum(fn ($item) => $item->quantity * (float) $item->unit_price),
        ]);
    }

    private function enquiry(): Enquiry
    {
        return Enquiry::findOrFail($this->enquiryId);
    }
}
