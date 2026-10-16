<?php

declare(strict_types=1);

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Services\Site\CatalogueService;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function __invoke(CatalogueService $catalogue): Response
    {
        $urls = [route('site.home'), route('site.about'), route('site.enquiry'), route('site.contact'), route('site.faq'), route('site.reviews')];

        foreach ($catalogue->categories() as $category) {
            $urls[] = route('site.enquiry', ['category' => $category->slug]);
        }

        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n"
            . '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
        foreach ($urls as $url) {
            $xml .= '  <url><loc>' . e($url) . '</loc></url>' . "\n";
        }
        $xml .= '</urlset>' . "\n";

        return response($xml, 200, ['Content-Type' => 'application/xml']);
    }
}