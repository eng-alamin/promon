<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Customers;

use App\Enums\CustomerType;
use App\Livewire\Concerns\WithSearch;
use App\Models\Customer;
use App\Services\Admin\CustomerService;
use DomainException;
use Illuminate\Contracts\View\View;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Livewire\WithPagination;

class CustomerTable extends Component
{
    use WithPagination;
    use WithSearch;

    private const PER_PAGE = 10;

    private const TEXT_FIELDS = ['business_name', 'email', 'phone', 'whatsapp', 'city', 'country', 'notes'];

    public string $typeFilter = '';

    public string $contact_name = '';

    public ?string $business_name = null;

    public ?string $email = null;

    public ?string $phone = null;

    public ?string $whatsapp = null;

    public ?string $city = null;

    public ?string $country = null;

    public string $customer_type = 'retailer';

    public ?string $notes = null;

    public bool $showForm = false;

    #[Locked]
    public ?int $editingId = null;

    #[Locked]
    public ?int $deletingId = null;

    public function updatingTypeFilter(): void
    {
        $this->resetPage();
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    protected function rules(): array
    {
        return [
            'contact_name' => ['required', 'string', 'max:120'],
            'business_name' => ['nullable', 'string', 'max:160'],
            'email' => ['nullable', 'email', 'max:255', 'required_without_all:phone,whatsapp'],
            'phone' => ['nullable', 'string', 'max:30', 'required_without_all:email,whatsapp'],
            'whatsapp' => ['nullable', 'string', 'max:30', 'required_without_all:email,phone'],
            'city' => ['nullable', 'string', 'max:100'],
            'country' => ['nullable', 'string', 'max:80'],
            'customer_type' => ['required', Rule::enum(CustomerType::class)],
            'notes' => ['nullable', 'string', 'max:5000'],
        ];
    }

    /**
     * @return array<string, string>
     */
    protected function messages(): array
    {
        $reach = 'Add a phone, WhatsApp number or email so you can reach this customer.';

        return [
            'contact_name.required' => 'The contact name is required.',
            'email.required_without_all' => $reach,
            'phone.required_without_all' => $reach,
            'whatsapp.required_without_all' => $reach,
        ];
    }

    public function create(): void
    {
        $this->resetForm();
        $this->showForm = true;
    }

    public function edit(int $id): void
    {
        $customer = Customer::findOrFail($id);

        $this->resetForm();
        $this->editingId = $customer->id;
        $this->contact_name = $customer->contact_name;
        $this->business_name = $customer->business_name;
        $this->email = $customer->email;
        $this->phone = $customer->phone;
        $this->whatsapp = $customer->whatsapp;
        $this->city = $customer->city;
        $this->country = $customer->country;
        $this->customer_type = $customer->type->value;
        $this->notes = $customer->notes;
        $this->showForm = true;
    }

    public function closeForm(): void
    {
        $this->resetForm();
        $this->showForm = false;
    }

    public function save(CustomerService $customers): void
    {
        $this->prepareInput();

        $data = $this->validate();
        $data['type'] = $data['customer_type'];

        $customer = $this->editingId !== null ? Customer::findOrFail($this->editingId) : null;
        $customers->save($customer, $data);

        $message = $customer === null ? 'Customer created.' : 'Customer updated.';

        $this->closeForm();
        $this->dispatch('toast', message: $message);
    }

    public function confirmDelete(int $id): void
    {
        $this->deletingId = Customer::findOrFail($id)->id;
    }

    public function cancelDelete(): void
    {
        $this->deletingId = null;
    }

    public function delete(CustomerService $customers): void
    {
        $customer = $this->deletingId !== null ? Customer::find($this->deletingId) : null;
        $this->deletingId = null;

        if ($customer === null) {
            return;
        }

        try {
            $customers->delete($customer);
        } catch (DomainException $exception) {
            $this->dispatch('toast', message: $exception->getMessage(), type: 'err');

            return;
        }

        $this->resetPage();
        $this->dispatch('toast', message: 'Customer deleted.');
    }

    public function render(): View
    {
        $term = $this->likeTerm();

        $customers = Customer::query()
            ->withCount('enquiries')
            ->when(trim($this->search) !== '', function ($query) use ($term) {
                $query->where(function ($inner) use ($term) {
                    $inner->where('business_name', 'like', $term)
                        ->orWhere('contact_name', 'like', $term)
                        ->orWhere('phone', 'like', $term)
                        ->orWhere('whatsapp', 'like', $term)
                        ->orWhere('email', 'like', $term)
                        ->orWhere('city', 'like', $term);
                });
            })
            ->when(CustomerType::tryFrom($this->typeFilter) !== null, fn ($query) => $query->where('type', $this->typeFilter))
            ->orderBy('contact_name')
            ->orderBy('id')
            ->paginate(self::PER_PAGE);

        return view('livewire.admin.customers.customer-table', [
            'customers' => $customers,
            'types' => CustomerType::cases(),
            'deletingCustomer' => $this->deletingId !== null ? Customer::find($this->deletingId) : null,
        ]);
    }

    private function prepareInput(): void
    {
        foreach (self::TEXT_FIELDS as $field) {
            $value = $this->{$field} === null ? '' : trim($this->{$field});
            $this->{$field} = $value === '' ? null : $value;
        }

        $this->contact_name = trim($this->contact_name);
    }

    private function resetForm(): void
    {
        $this->reset([
            'contact_name', 'business_name', 'email', 'phone', 'whatsapp',
            'city', 'country', 'customer_type', 'notes', 'editingId',
        ]);
        $this->resetValidation();
    }
}
