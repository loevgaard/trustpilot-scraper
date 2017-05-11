<?php

namespace Loevgaard\Trustpilot\Tests;

use Loevgaard\Trustpilot\Review\Review;
use Loevgaard\Trustpilot\Scraper;
use PHPUnit\Framework\TestCase;

class ScraperTest extends TestCase
{
    protected function setUp()
    {

    }

    public function testGetReviews()
    {
        $scraper = new Scraper('www.amazon.com');

        /** @var Review $review */
        $review = $scraper->getReviews()->current();

        // assert review
        $this->assertInstanceOf('Loevgaard\\Trustpilot\\Review\\Review', $review);
        $this->assertTrue(is_string($review->getId()));
        $this->assertTrue(is_string($review->getUrl()));
        $this->assertTrue(is_string($review->getTitle()));
        $this->assertTrue(is_string($review->getBody()));
        $this->assertTrue(is_int($review->getRating()));
        $this->assertInstanceOf('\\DateTime', $review->getDate());

        // assert user
        $user = $review->getUser();
        $this->assertInstanceOf('Loevgaard\\Trustpilot\\Review\\User', $user);
        $this->assertTrue(is_string($user->getId()));
        $this->assertTrue(is_string($user->getName()));
        $this->assertTrue(is_string($user->getUrl()));
    }

    public function testGetTrustScore()
    {
        $scraper = new Scraper('www.amazon.com');
        $trustScore = $scraper->getTrustScore();

        $this->assertTrue(is_float($trustScore));
        $this->assertGreaterThanOrEqual(0, $trustScore);
    }

    public function testGetReviewCount()
    {
        $scraper = new Scraper('www.amazon.com');
        $reviewCount = $scraper->getReviewCount();

        $this->assertTrue(is_int($reviewCount));
        $this->assertGreaterThanOrEqual(0, $reviewCount);
    }
}