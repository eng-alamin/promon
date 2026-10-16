<?php

declare(strict_types=1);

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use App\Services\Site\ContactService;
use App\Services\Site\ReviewListingService;
use Illuminate\Contracts\View\View;

class PageController extends Controller
{
    public function about(ContactService $contact): View
    {
        $details = $contact->details();

        return view('site.about', [
            'whatsapp' => $details['whatsapp'],
            'waDigits' => $details['wa_digits'],
        ]);
    }

    public function faq(): View
    {
        return view('site.faq', [
            'faqs' => Faq::active()->orderBy('sort_order')->orderBy('id')->get(),
        ]);
    }

    public function reviews(ReviewListingService $reviews, ContactService $contact): View
    {
        $details = $contact->details();

        return view('site.reviews', [
            'reviews' => $reviews->all(),
            'average' => $reviews->average(),
            'waDigits' => $details['wa_digits'],
        ]);
    }

    public function contact(ContactService $contact): View
    {
        $details = $contact->details();

        return view('site.contact', [
            'email' => $details['email'],
            'whatsapp' => $details['whatsapp'],
            'waDigits' => $details['wa_digits'],
        ]);
    }
}