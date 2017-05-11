<?php

namespace Loevgaard\Trustpilot\Review;

// @todo add trustpilot review id
class Review
{
    /**
     * @var string
     */
    private $url;

    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $body;

    /**
     * @var int
     */
    private $rating;

    /**
     * @var \DateTime
     */
    private $date;

    /**
     * @param string $title
     * @param string $body
     * @param int $rating
     * @param \DateTime|string $date
     */
    public function __construct($url, $title, $body, $rating, $date)
    {
        $this->url = $url;
        $this->title = $title;
        $this->body = $body;
        $this->rating = (int)$rating;
        $this->setDate($date);
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $url
     * @return Review
     */
    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return Review
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param string $body
     * @return Review
     */
    public function setBody($body)
    {
        $this->body = $body;
        return $this;
    }

    /**
     * @return int
     */
    public function getRating()
    {
        return $this->rating;
    }

    /**
     * @param int $rating
     * @return Review
     */
    public function setRating($rating)
    {
        $this->rating = $rating;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Can parse Trustpilot date strings, i.e. 2017-05-10T19:34:21.000+00:00
     *
     * @param \DateTime|string $date
     * @return Review
     */
    public function setDate($date)
    {
        if(is_string($date)) {
            $date = \DateTime::createFromFormat('Y-m-d\TH:i:s.uP', $date);
        }
        $this->date = $date;
        return $this;
    }
}