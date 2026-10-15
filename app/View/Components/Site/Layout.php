<?php

declare(strict_types=1);

namespace App\View\Components\Site;

use App\Services\Site\ContactService;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

/**
 * Public page frame. Reads the company name and contact details once so every
 * page shows the same values (admin Settings first, PROMON LTD defaults after).
 */
class Layout extends Component
{
    public string $company;

    public string $email;

    public string $whatsapp;

    /** WhatsApp number as digits only, for wa.me links. */
    public string $waDigits;

    public function __construct(
        ContactService $contact,
        public string $title = '',
        public string $description = '',
        public string $nav = '',
    ) {
        $details = $contact->details();

        $this->company = $details['company'];
        $this->email = $details['email'];
        $this->whatsapp = $details['whatsapp'];
        $this->waDigits = $details['wa_digits'];
    }

    public function render(): View
    {
        return view('components.site.layout');
    }
}
