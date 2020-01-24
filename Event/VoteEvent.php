<?php

namespace Discutea\RatingBundle\Event;

use Symfony\Contracts\EventDispatcher\Event;
use Discutea\RatingBundle\Model\VoteInterface;

/**
 * Class VoteEvent
 * @package Discutea\RatingBundle\Event
 * @copyright 2014 damianociarla https://github.com/damianociarla/DCSRatingBundle
 */
class VoteEvent extends Event
{
    /**
     * @var VoteInterface
     */
    private $vote;

    /**
     * VoteEvent constructor.
     * @param VoteInterface $vote
     */
    public function __construct(VoteInterface $vote)
    {
        $this->vote = $vote;
    }

    /**
     * @return VoteInterface
     */
    public function getVote(): VoteInterface
    {
        return $this->vote;
    }
}
