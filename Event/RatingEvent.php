<?php

namespace Discutea\RatingBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use Discutea\RatingBundle\Model\RatingInterface;

/**
 * Class RatingEvent
 * @package Discutea\RatingBundle\Event
 * @copyright 2014 damianociarla https://github.com/damianociarla/DCSRatingBundle
 */
class RatingEvent extends Event
{
    /**
     * @var \Discutea\RatingBundle\Model\RatingInterface
     */
    private $rating;

    public function __construct(RatingInterface $rating)
    {
        $this->rating = $rating;
    }

    /**
     * Get rating
     * 
     * @return \Discutea\RatingBundle\Model\RatingInterface
     */
    public function getRating()
    {
        return $this->rating;
    }
}
