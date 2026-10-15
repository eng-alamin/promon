<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Enquiry;
use Illuminate\View\View;

class EnquiryController extends Controller
{
    public function index(): View
    {
        return view('admin.enquiries.index');
    }

    public function create(): View
    {
        return view('admin.enquiries.create');
    }

    public function show(Enquiry $enquiry): View
    {
        return view('admin.enquiries.show', ['enquiry' => $enquiry]);
    }
}
