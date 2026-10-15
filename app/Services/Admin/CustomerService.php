<?php

declare(strict_types=1);

namespace App\Services\Admin;

use App\Models\Customer;
use DomainException;

class CustomerService
{
    /**
     * Create a customer, or update it when one is given.
     * Empty optional values are saved as NULL.
     *
     * @param  array<string, mixed>  $data
     */
    public function save(?Customer $customer, array $data): Customer
    {
        $payload = [
            'contact_name' => trim((string) $data['contact_name']),
            'business_name' => $this->nullable($data['business_name'] ?? null),
            'email' => $this->nullable($data['email'] ?? null),
            'phone' => $this->nullable($data['phone'] ?? null),
            'whatsapp' => $this->nullable($data['whatsapp'] ?? null),
            'city' => $this->nullable($data['city'] ?? null),
            'country' => $this->nullable($data['country'] ?? null),
            'type' => $data['type'],
            'notes' => $this->nullable($data['notes'] ?? null),
        ];

        if ($customer === null) {
            return Customer::create($payload);
        }

        $customer->update($payload);

        return $customer;
    }

    /**
     * @throws DomainException when the customer has enquiries (they are business records)
     */
    public function delete(Customer $customer): void
    {
        if ($customer->enquiries()->exists()) {
            $name = $customer->business_name ?: $customer->contact_name;

            throw new DomainException(
                "Cannot delete \"{$name}\" because this customer has enquiries. Enquiries are business records and must be kept."
            );
        }

        $customer->delete();
    }

    private function nullable(mixed $value): ?string
    {
        $text = $value === null ? '' : trim((string) $value);

        return $text === '' ? null : $text;
    }
}
