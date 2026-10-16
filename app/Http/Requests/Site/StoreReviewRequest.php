<?php

declare(strict_types=1);

namespace App\Http\Requests\Site;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Public review form. A visitor gives a name, a rating from 1 to 5 and a short text.
 */
class StoreReviewRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'reviewer_name' => ['required', 'string', 'max:120'],
            'reviewer_title' => ['nullable', 'string', 'max:160'],
            'rating' => ['required', 'integer', 'between:1,5'],
            'body' => ['required', 'string', 'min:10', 'max:1000'],
            // Honeypot: a real visitor never sees this field, so it must stay empty.
            'website' => ['nullable', 'max:0'],
        ];
    }

    /** @return array<string, string> */
    public function messages(): array
    {
        return [
            'reviewer_name.required' => 'Please enter your name.',
            'rating.required' => 'Please choose a rating.',
            'rating.between' => 'Please choose a rating from 1 to 5.',
            'body.required' => 'Please write your review.',
            'body.min' => 'Your review must be at least 10 characters.',
            'website.max' => 'Could not send the review.',
        ];
    }

    /**
     * On errors, send the visitor back to the form (not the top of the page).
     */
    protected function getRedirectUrl(): string
    {
        return route('site.reviews') . '#write-review';
    }
}
