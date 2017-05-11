<?php

namespace Loevgaard\Trustpilot;

use Goutte\Client;
use Loevgaard\Trustpilot\Review\Review;
use Loevgaard\Trustpilot\Review\User;
use Symfony\Component\DomCrawler\Crawler;

class Scraper
{
    const TRUSTPILOT_BASE_URL = 'https://www.trustpilot.com';

    /**
     * The overview page where you retrieve multiple reviews, i.e. https://www.trustpilot.com/review/www.amazon.com
     */
    const TRUSTPILOT_REVIEWS_PATH = '/review/%s?page=%d';

    /**
     * We use the Goutte library for scraping
     *
     * @var Client
     */
    private $httpClient;

    /**
     * The website for which you want to scrape reviews
     *
     * @var string
     */
    private $website;

    /**
     * The websites trust score
     *
     * @var float
     */
    private $trustScore;

    /**
     * The total number of reviews the website has
     *
     * @var int
     */
    private $reviewCount;

    /**
     * @param string $website
     */
    public function __construct($website)
    {
        $this->website = $website;
    }

    /**
     * @return float
     */
    public function getTrustScore() {
        if(!$this->trustScore) {
            $httpClient = $this->getHttpClient();
            $crawler = $httpClient->request('GET', self::TRUSTPILOT_BASE_URL . sprintf(self::TRUSTPILOT_REVIEWS_PATH, $this->website, 1));

            $this->trustScore = (float)$crawler->filter('.summary-rating .number-rating .average')->text();
        }

        return $this->trustScore;
    }

    /**
     * @return int
     */
    public function getReviewCount() {
        if(!$this->reviewCount) {
            $httpClient = $this->getHttpClient();
            $crawler = $httpClient->request('GET', self::TRUSTPILOT_BASE_URL . sprintf(self::TRUSTPILOT_REVIEWS_PATH, $this->website, 1));

            $this->reviewCount = (int)$crawler->filter('.ratingCount')->text();
        }

        return $this->reviewCount;
    }

    /**
     * @param int $maxPages
     * @param bool $includeUser
     * @return \Generator
     */
    public function getReviews($maxPages = 0, $includeUser = true) {
        $httpClient = $this->getHttpClient();
        $page = 1;

        $nextUrl = self::TRUSTPILOT_BASE_URL.sprintf(self::TRUSTPILOT_REVIEWS_PATH, $this->website, $page);

        while(true) {
            if(!$nextUrl) {
                break;
            }

            $crawler = $httpClient->request('GET', $nextUrl);

            $relNext = $crawler->filter('link[rel="next"]');
            if($relNext->count()) {
                $nextUrl = $relNext->attr('href');
            } else {
                $nextUrl = null;
            }

            $reviewUrls = $crawler->filter('.review')->each(function (Crawler $node) use ($httpClient) {
                $reviewUrl = self::TRUSTPILOT_BASE_URL.$node->filter('.review-title a')->first()->attr('href');
                return $reviewUrl;
            });

            foreach ($reviewUrls as $reviewUrl) {
                $crawler = $httpClient->request('GET', $reviewUrl);
                $review = $crawler->filter('[itemprop="review"]');

                $id = $review->attr('data-reviewmid');
                $title = trim($review->filter('.review-title')->text());
                $body = trim($review->filter('.review-text')->text());
                $dateTime = $review->filter('time')->attr('datetime');
                $rating = (int)$review->filter('meta[itemprop="ratingValue"]')->attr('content');

                $reviewObj = new Review($id, $title, $body, $rating, $dateTime);

                if($includeUser) {
                    $userId = $review->attr('data-review-user-id');
                    $user = trim($review->filter('.user-profile-name')->text());

                    $reviewObj->setUser(new User($userId, $user));
                }

                yield $reviewObj;
            }

            if($maxPages && $maxPages <= $page) {
                break;
            }

            $page++;
        }
    }

    /**
     * @return Client
     */
    private function getHttpClient() {
        if(!$this->httpClient) {
            $this->httpClient = new Client();
        }

        return $this->httpClient;
    }
}