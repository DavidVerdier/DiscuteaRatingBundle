<?php

namespace Discutea\RatingBundle\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Discutea\RatingBundle\DiscuteaRatingEvents;
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
    private $ratingManager;
    private $voteManager;

    public function __construct(RatingManagerInterface $ratingManager, VoteManagerInterface $voteManager)
    {
        $this->ratingManager = $ratingManager;
        $this->voteManager = $voteManager;
    }

    public static function getSubscribedEvents()
    {
        return array(
            DiscuteaRatingEvents::VOTE_POST_PERSIST => 'onCreateVote'
        );
    }

    public function onCreateVote(VoteEvent $event)
    {
        $rating = $event->getVote()->getRating();
        $this->ratingManager->updateRatingStats($rating);
    }
}
