<?php

namespace Discutea\RatingBundle\Model;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Discutea\RatingBundle\RatingEvents;
use Discutea\RatingBundle\Event;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class VoteManager
 * @package Discutea\RatingBundle\Model
 * @copyright 2014 damianociarla https://github.com/damianociarla/DCSRatingBundle
 */
abstract class VoteManager implements VoteManagerInterface
{
    /**
     * @var \Symfony\Component\EventDispatcher\EventDispatcherInterface
     */
    protected $dispatcher;

    public function __construct(EventDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     * Creates an empty vote instance
     *
     * @param RatingInterface $rating
     * @param UserInterface $voter
     * @return VoteInterface|mixed
     */
    public function createVote(RatingInterface $rating, UserInterface $voter)
    {
        $class = $this->getClass();
        $vote = new $class;
        $vote->setRating($rating);
        $vote->setVoter($voter);

        $this->dispatcher->dispatch(RatingEvents::VOTE_CREATE, new Event\VoteEvent($vote));

        return $vote;
    }

    /**
     * Finds one vote by Rating and User
     *
     * @param RatingInterface $rating
     * @param UserInterface $voter
     * @return VoteInterface
     */
    public function findOneByRatingAndVoter(RatingInterface $rating, UserInterface $voter)
    {
        return $this->findOneBy(array(
            'rating' => $rating,
            'voter' => $voter,
        ));
    }

    /**
     * @param VoteInterface $vote
     * @return RatingInterface|void
     */
    public function saveVote(VoteInterface $vote)
    {
        $this->dispatcher->dispatch(RatingEvents::VOTE_PRE_PERSIST, new Event\VoteEvent($vote));

        $this->doSaveVote($vote);

        $this->dispatcher->dispatch(RatingEvents::VOTE_POST_PERSIST, new Event\VoteEvent($vote));
    }

    /**
     * Performs the persistence of the Vote.
     *
     * @abstract
     * @param VoteInterface $vote
     */
    abstract protected function doSaveVote(VoteInterface $vote);
}
