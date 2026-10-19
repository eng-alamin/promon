<?php

declare(strict_types=1);

namespace App\Http\Controllers\Site;

use App\Enums\CustomerType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Site\StoreEnquiryRequest;
use App\Services\Site\CatalogueService;
use App\Services\Site\ContactService;
use App\Services\Site\EnquirySubmissionService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Mail\NewEnquiryMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class EnquiryController extends Controller
{
    public function show(Request $request, CatalogueService $catalogue, ContactService $contact): View
    {
        $categories = $catalogue->categories();

        // /enquiry?category=grocery (from a category tile) ticks that category. Unknown values are ignored.
        $preselected = $categories->contains('slug', (string) $request->query('category'))
            ? [(string) $request->query('category')]
            : [];

        $type = CustomerType::tryFrom((string) $request->query('type'));

        return view('site.enquiry', [
            'types' => CustomerType::cases(),
            'categories' => $categories,
            'preselected' => $preselected,
            'defaultType' => $type?->value ?? CustomerType::Retailer->value,
            'waDigits' => $contact->details()['wa_digits'],
        ]);
    }

    public function store(
        StoreEnquiryRequest $request,
        EnquirySubmissionService $service,
        ContactService $contact
    ): RedirectResponse {
        $data = $request->validated();

        $enquiry = $service->submit(
            collect($data)->only(['business_name', 'contact_name', 'email', 'phone', 'whatsapp', 'city', 'country', 'type'])->all(),
            array_values($data['categories']),
            $data['message'] ?? null,
        );

        try {
            Mail::to($contact->details()['email'])->send(new NewEnquiryMail($enquiry));
        } catch (\Throwable $e) {
            Log::error('Enquiry mail failed: ' . $e->getMessage());
        }

        return redirect()->route('site.enquiry.thanks')->with('enquiry_reference', $enquiry->reference);
    }

    public function thanks(): View|RedirectResponse
    {
        $reference = session('enquiry_reference');

        return $reference === null
            ? redirect()->route('site.enquiry')
            : view('site.enquiry-thanks', ['reference' => $reference]);
    }
}