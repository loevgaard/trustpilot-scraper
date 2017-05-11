<?php

namespace Loevgaard\Trustpilot\Tests;

use Loevgaard\Trustpilot\Scraper;
use PHPUnit\Framework\TestCase;

class ScraperTest extends TestCase
{
    protected function setUp()
    {

    }

    public function testScraper()
    {
        $scraper = new Scraper('www.amazon.com');
        foreach ($scraper->getReviews() as $review) {
            print_r($review);
            break;
        }
    }
}