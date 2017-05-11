<?php

namespace Loevgaard\Trustpilot\Review;

class Review
{
    /**
     * The path where you retrieve a single review, i.e. https://www.trustpilot.com/reviews/4764065d00006400020104b8
     */
    const TRUSTPILOT_REVIEW_PATH = '/reviews/%s';

    /**
     * @var string
     */
    private $id;

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
     * @var User
     */
    private $user;

    /**
     * @param string $id
     * @param string $title
     * @param string $body
     * @param int $rating
     * @param \DateTime|string $date
     * @param User $user
     */
    public function __construct($id, $title, $body, $rating, $date, User $user = null)
    {
        $this->id = $id;
        $this->title = $title;
        $this->body = $body;
        $this->rating = (int)$rating;
        $this->setDate($date);
        $this->user = $user;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return Review
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        if(!$this->url) {
            $this->url = sprintf(self::TRUSTPILOT_REVIEW_PATH, $this->id);
        }
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

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User $user
     * @return Review
     */
    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }
}