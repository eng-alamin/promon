<?php

declare(strict_types=1);

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Contracts\View\View;

class PageController extends Controller
{
    public function faq(): View
    {
        return view('site.faq', [
            'faqs' => Faq::active()->orderBy('sort_order')->orderBy('id')->get(),
        ]);
    }
}
