<?php

declare(strict_types=1);

namespace App\Http\Requests\Site;

use App\Enums\CustomerType;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Public enquiry form. A visitor chooses one or more categories (no products,
 * no prices) and tells us what they need in the message.
 */
class StoreEnquiryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        $phone = ['nullable', 'string', 'max:30', 'regex:/^[0-9+()\-\s.]{6,30}$/'];

        return [
            'business_name' => ['required', 'string', 'max:150'],
            'contact_name' => ['required', 'string', 'max:100'],
            'email' => ['nullable', 'email', 'max:150'],
            'phone' => $phone,
            'whatsapp' => $phone,
            'city' => ['nullable', 'string', 'max:100'],
            'country' => ['nullable', 'string', 'max:100'],
            'type' => ['required', Rule::enum(CustomerType::class)],
            'message' => ['nullable', 'string', 'max:2000'],
            // Honeypot: a real visitor never sees this field, so it must stay empty.
            'website' => ['nullable', 'max:0'],
            'categories' => ['required', 'array', 'min:1', 'max:20'],
            'categories.*' => [
                'required',
                'string',
                'distinct',
                Rule::exists('categories', 'slug')->where('is_active', true),
            ],
        ];
    }

    /** @return array<string, string> */
    public function messages(): array
    {
        return [
            'categories.required' => 'Please choose at least one category.',
            'categories.min' => 'Please choose at least one category.',
            'categories.*.exists' => 'A category you chose is no longer available. Please refresh the page.',
            'website.max' => 'Could not send the enquiry.',
        ];
    }

    /** @return array<int, \Closure(Validator): void> */
    public function after(): array
    {
        return [
            function (Validator $validator): void {
                if ($validator->errors()->isNotEmpty()) {
                    return;
                }

                if (! $this->filled('email') && ! $this->filled('phone') && ! $this->filled('whatsapp')) {
                    $validator->errors()->add('email', 'Please give an email, phone or WhatsApp number so we can reply.');
                }
            },
        ];
    }
}
