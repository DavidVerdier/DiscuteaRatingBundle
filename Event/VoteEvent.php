<?php

namespace Discutea\RatingBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use Discutea\RatingBundle\Model\VoteInterface;

/**
 * Class VoteEvent
 * @package Discutea\RatingBundle\Event
 * @copyright 2014 damianociarla https://github.com/damianociarla/DCSRatingBundle
 */
class VoteEvent extends Event
{
    /**
     * @var \Discutea\RatingBundle\Model\VoteInterface
     */
    private $vote;

    public function __construct(VoteInterface $vote)
    {
        $this->vote = $vote;
    }

    /**
     * Get vote
     *
     * @return \Discutea\RatingBundle\Model\VoteInterface
     */
    public function getVote()
    {
        return $this->vote;
    }
}
