# Trustpilot Scraper
A PHP scraper for scraping Trustpilot reviews, trust score and review count

## Installation

Add `loevgaard/trustpilot-scraper` as a dependency in your `composer.json` file:

```
composer require loevgaard/trustpilot-scraper
```

## Usage
Create an instance of `Loevgaard\Trustpilot\Scraper`:

```php
use Loevgaard\Trustpilot\Scraper;

$scraper = new Scraper('www.amazon.com');
```

To get all reviews:

```php
foreach($scraper->getReviews() as $review) {
    /** @var \Loevgaard\Trustpilot\Review\Review $review */
    
    var_dump($review);
    var_dump($review->getUser());
}
```

To get reviews on the first page:

```php
foreach($scraper->getReviews(1) as $review) {
    /** @var \Loevgaard\Trustpilot\Review\Review $review */
    
    var_dump($review);
    var_dump($review->getUser());
}
```

To get trust score:

```php
$scraper->getTrustScore(); // returns a float between 0 and 10
```

To get review count:

```php
$scraper->getReviewCount(); // returns an int
```

## License
Trustpilot Scraper is licensed under the MIT license.