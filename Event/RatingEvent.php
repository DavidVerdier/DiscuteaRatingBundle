<?php

namespace Discutea\RatingBundle\Event;

use Symfony\Contracts\EventDispatcher\Event;
use Discutea\RatingBundle\Model\RatingInterface;

/**
 * Class RatingEvent
 * @package Discutea\RatingBundle\Event
 * @copyright 2014 damianociarla https://github.com/damianociarla/DCSRatingBundle
 */
class RatingEvent extends Event
{
    /**
     * @var RatingInterface
     */
    private $rating;

    /**
     * RatingEvent constructor.
     * @param RatingInterface $rating
     */
    public function __construct(RatingInterface $rating)
    {
        $this->rating = $rating;
    }

    /**
     * @return RatingInterface
     */
    public function getRating(): RatingInterface
    {
        return $this->rating;
    }
}
