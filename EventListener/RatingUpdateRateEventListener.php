<?php

namespace Discutea\RatingBundle\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Discutea\RatingBundle\RatingEvents;
use Discutea\RatingBundle\Event\VoteEvent;
use Discutea\RatingBundle\Model\RatingManagerInterface;
use Discutea\RatingBundle\Model\VoteManagerInterface;

/**
 * Class RatingUpdateRateEventListener
 * @package Discutea\RatingBundle\EventListener
 * @copyright 2014 damianociarla https://github.com/damianociarla/DCSRatingBundle
 */
class RatingUpdateRateEventListener implements EventSubscriberInterface
{
    /**
     * @var RatingManagerInterface
     */
    private $ratingManager;

    /**
     * @var VoteManagerInterface
     */
    private $voteManager;

    /**
     * RatingUpdateRateEventListener constructor.
     * @param RatingManagerInterface $ratingManager
     * @param VoteManagerInterface $voteManager
     */
    public function __construct(RatingManagerInterface $ratingManager, VoteManagerInterface $voteManager)
    {
        $this->ratingManager = $ratingManager;
        $this->voteManager = $voteManager;
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
     */
    public function onCreateVote(VoteEvent $event): void
    {
        $rating = $event->getVote()->getRating();
        $this->ratingManager->updateRatingStats($rating);
    }
}
