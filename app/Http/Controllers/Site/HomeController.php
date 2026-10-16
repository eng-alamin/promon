<?php

declare(strict_types=1);

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Services\Site\CatalogueService;
use App\Services\Site\ContactService;
use App\Services\Site\ReviewListingService;
use Illuminate\Contracts\View\View;

class HomeController extends Controller
{
    public function __invoke(CatalogueService $catalogue, ContactService $contact, ReviewListingService $reviews): View
    {
        $details = $contact->details();

        return view('site.home', [
            'categories' => $catalogue->categories(),
            'reviews' => $reviews->featured(3),
            'reviewCount' => $reviews->count(),
            'email' => $details['email'],
            'whatsapp' => $details['whatsapp'],
            'waDigits' => $details['wa_digits'],
        ]);
    }
}
