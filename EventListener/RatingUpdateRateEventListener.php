<?php

namespace Discutea\RatingBundle\EventListener;

use Discutea\RatingBundle\Repository\RatingRepository;
use Discutea\RatingBundle\Repository\VoteRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Discutea\RatingBundle\RatingEvents;
use Discutea\RatingBundle\Event\VoteEvent;

/**
 * Class RatingUpdateRateEventListener
 * @package Discutea\RatingBundle\EventListener
 * @copyright 2014 damianociarla https://github.com/damianociarla/DCSRatingBundle
 */
class RatingUpdateRateEventListener implements EventSubscriberInterface
{
    /**
     * @var RatingRepository
     */
    private $ratingManager;

    /**
     * @var VoteRepository
     */
    private $voteManager;

    /**
     * RatingUpdateRateEventListener constructor.
     * @param RatingRepository $ratingRepository
     * @param VoteRepository $voteRepository
     */
    public function __construct(RatingRepository $ratingRepository, VoteRepository $voteRepository)
    {
        $this->ratingManager = $ratingRepository;
        $this->voteManager = $voteRepository;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return array(
            RatingEvents::VOTE_POST_PERSIST => 'onCreateVote'
        );
    }

    /**
     * @param VoteEvent $event
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function onCreateVote(VoteEvent $event): void
    {
        $rating = $event->getVote()->getRating();
        $this->ratingManager->updateRatingStats($rating);
    }
}
