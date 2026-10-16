<?php

declare(strict_types=1);

namespace Tests\Feature\Site;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NavigationTest extends TestCase
{
    use RefreshDatabase;

    public function test_header_has_home_and_the_section_links(): void
    {
        $html = $this->get(route('site.contact'))->assertOk()->getContent();
        $header = substr($html, (int) strpos($html, '<header'), (int) strpos($html, '</header>') - (int) strpos($html, '<header'));

        foreach (['>Home<', '#about">About<', '#services">Wholesale &amp; Export<', '#how">How it works<', '#payment">Payment<', '>Contact<'] as $needle) {
            $this->assertStringContainsString($needle, $header);
        }

        $this->assertStringNotContainsString('>Reviews<', $header);
    }

    public function test_footer_has_the_page_links(): void
    {
        $html = $this->get(route('site.home'))->assertOk()->getContent();
        $footer = substr($html, (int) strpos($html, '<footer'));

        $this->assertStringContainsString(route('site.contact'), $footer);
        $this->assertStringContainsString(route('site.reviews'), $footer);
        $this->assertStringContainsString(route('site.faq'), $footer);
        $this->assertStringContainsString('#about">About<', $footer);
        $this->assertStringContainsString('#products">Products<', $footer);
    }
}