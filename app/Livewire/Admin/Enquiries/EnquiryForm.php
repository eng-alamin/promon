<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Enquiries;

use App\Enums\CustomerType;
use App\Models\Customer;
use App\Models\Product;
use App\Services\Admin\EnquiryService;
use Illuminate\Contracts\View\View;
use Illuminate\Validation\Rule;
use Livewire\Component;

class EnquiryForm extends Component
{
    private const MAX_ITEMS = 30;

    private const NEW_CUSTOMER_TEXT = ['business_name', 'email', 'phone', 'whatsapp', 'city', 'country'];

    public string $customerMode = 'existing';

    public string $customerSearch = '';

    public string $customerId = '';

    public string $contact_name = '';

    public ?string $business_name = null;

    public ?string $email = null;

    public ?string $phone = null;

    public ?string $whatsapp = null;

    public ?string $city = null;

    public ?string $country = null;

    public string $customer_type = 'retailer';

    public ?string $message = null;

    /** @var array<int, array{product_id: string, quantity: string}> */
    public array $items = [['product_id' => '', 'quantity' => '1']];

    /**
     * @return array<string, array<int, mixed>>
     */
    protected function rules(): array
    {
        $rules = [
            'customerMode' => ['required', Rule::in(['existing', 'new'])],
            'message' => ['nullable', 'string', 'max:5000'],
            'items' => ['required', 'array', 'min:1', 'max:' . self::MAX_ITEMS],
            'items.*.product_id' => ['required', 'distinct', Rule::exists('products', 'id')->whereNull('deleted_at')],
            'items.*.quantity' => ['required', 'integer', 'min:1', 'max:1000000'],
        ];

        if ($this->customerMode === 'existing') {
            return $rules + ['customerId' => ['required', Rule::exists('customers', 'id')]];
        }

        return $rules + [
            'contact_name' => ['required', 'string', 'max:120'],
            'business_name' => ['nullable', 'string', 'max:160'],
            'email' => ['nullable', 'email', 'max:255', 'required_without_all:phone,whatsapp'],
            'phone' => ['nullable', 'string', 'max:30', 'required_without_all:email,whatsapp'],
            'whatsapp' => ['nullable', 'string', 'max:30', 'required_without_all:email,phone'],
            'city' => ['nullable', 'string', 'max:100'],
            'country' => ['nullable', 'string', 'max:80'],
            'customer_type' => ['required', Rule::enum(CustomerType::class)],
        ];
    }

    /**
     * @return array<string, string>
     */
    protected function messages(): array
    {
        $reach = 'Add a phone, WhatsApp number or email so you can reach this customer.';

        return [
            'customerId.required' => 'Please choose a customer, or switch to "New customer".',
            'email.required_without_all' => $reach,
            'phone.required_without_all' => $reach,
            'whatsapp.required_without_all' => $reach,
            'items.required' => 'Add at least one product.',
            'items.*.product_id.required' => 'Please choose a product.',
            'items.*.product_id.distinct' => 'Each product can be added only once.',
            'items.*.quantity.required' => 'Enter a quantity.',
            'items.*.quantity.min' => 'The quantity must be at least 1.',
        ];
    }

    public function selectCustomer(int $id): void
    {
        $this->customerId = (string) Customer::findOrFail($id)->id;
        $this->customerSearch = '';
    }

    public function clearCustomer(): void
    {
        $this->customerId = '';
    }

    public function addItem(): void
    {
        if (count($this->items) < self::MAX_ITEMS) {
            $this->items[] = ['product_id' => '', 'quantity' => '1'];
        }
    }

    public function removeItem(int $index): void
    {
        if (count($this->items) <= 1) {
            return;
        }

        unset($this->items[$index]);
        $this->items = array_values($this->items);
    }

    public function save(EnquiryService $enquiries): void
    {
        $this->prepareInput();

        $this->validate();

        $customer = $this->customerMode === 'existing'
            ? Customer::findOrFail((int) $this->customerId)
            : [
                'contact_name' => $this->contact_name,
                'business_name' => $this->business_name,
                'email' => $this->email,
                'phone' => $this->phone,
                'whatsapp' => $this->whatsapp,
                'city' => $this->city,
                'country' => $this->country,
                'type' => $this->customer_type,
            ];

        $enquiry = $enquiries->createManual($customer, $this->items, $this->message, auth()->user());

        session()->flash('success', 'Enquiry created.');

        $this->redirectRoute('admin.enquiries.show', ['enquiry' => $enquiry->id]);
    }

    public function render(): View
    {
        $term = '%' . addcslashes(trim($this->customerSearch), '%_\\') . '%';

        $results = $this->customerMode === 'existing' && trim($this->customerSearch) !== ''
            ? Customer::query()
                ->where(function ($query) use ($term) {
                    $query->where('business_name', 'like', $term)
                        ->orWhere('contact_name', 'like', $term)
                        ->orWhere('phone', 'like', $term)
                        ->orWhere('whatsapp', 'like', $term)
                        ->orWhere('email', 'like', $term);
                })
                ->orderBy('contact_name')
                ->limit(8)
                ->get()
            : collect();

        return view('livewire.admin.enquiries.enquiry-form', [
            'results' => $results,
            'selectedCustomer' => $this->customerId !== '' ? Customer::find((int) $this->customerId) : null,
            'products' => Product::orderBy('name')->get(['id', 'name', 'sku']),
            'types' => CustomerType::cases(),
            'maxItems' => self::MAX_ITEMS,
        ]);
    }

    private function prepareInput(): void
    {
        foreach (self::NEW_CUSTOMER_TEXT as $field) {
            $value = $this->{$field} === null ? '' : trim($this->{$field});
            $this->{$field} = $value === '' ? null : $value;
        }

        $message = $this->message === null ? '' : trim($this->message);
        $this->message = $message === '' ? null : $message;
        $this->contact_name = trim($this->contact_name);
    }
}
