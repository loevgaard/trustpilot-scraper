<?php

namespace Loevgaard\Trustpilot;

use Goutte\Client;
use Loevgaard\Trustpilot\Review\Review;
use Symfony\Component\DomCrawler\Crawler;

class Scraper
{
    /**
     * We use the Goutte library for scraping
     *
     * @var Client
     */
    private $httpClient;

    /**
     * The Trustpilot website
     *
     * @var string
     */
    private $baseUrl = 'https://www.trustpilot.com';

    /**
     * The overview page where you retrieve multiple reviews, i.e. https://www.trustpilot.com/review/www.amazon.com
     *
     * @var string
     */
    private $reviewsPath = '/review/%s?page=%d';

    /**
     * The path where you retrieve a single review, i.e. https://www.trustpilot.com/reviews/4764065d00006400020104b8
     *
     * @var string
     */
    private $reviewPath = '/reviews/%s';

    /**
     * The website for which you want to scrape reviews
     *
     * @var string
     */
    private $website;

    /**
     * @param string $website
     */
    public function __construct($website)
    {
        $this->website = $website;
    }

    public function getReviews() {
        $httpClient = $this->getHttpClient();
        $page = 1;

        while(true) {
            // @todo check if rel="next" exists and use that for next page
            $crawler = $httpClient->request('GET', $this->baseUrl.sprintf($this->reviewsPath, $this->website, $page));
            $reviewUrls = $crawler->filter('.review')->each(function (Crawler $node) use ($httpClient) {
                $reviewUrl = $this->baseUrl.$node->filter('.review-title a')->first()->attr('href');
                return $reviewUrl;
            });

            foreach ($reviewUrls as $reviewUrl) {
                $crawler = $httpClient->request('GET', $reviewUrl);
                $review = $crawler->filter('[itemprop="review"]');

                $title = $review->filter('.review-title')->text();
                $body = $review->filter('.review-text')->text();
                $dateTime = $review->filter('time')->attr('datetime');
                $rating = $review->filter('meta[itemprop="ratingValue"]')->attr('content');

                $reviewObj = new Review($reviewUrl, $title, $body, $rating, $dateTime);
                yield $reviewObj;
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