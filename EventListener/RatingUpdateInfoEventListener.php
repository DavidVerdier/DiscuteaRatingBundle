<?php

namespace Discutea\RatingBundle\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Discutea\RatingBundle\RatingEvents;
use Discutea\RatingBundle\Event\RatingEvent;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class RatingUpdateInfoEventListener
 * @package Discutea\RatingBundle\EventListener
 * @copyright 2014 damianociarla https://github.com/damianociarla/DCSRatingBundle
 */
class RatingUpdateInfoEventListener implements EventSubscriberInterface
{
    /**
     * @var Request
     */
    private $request;

    /**
     * RatingUpdateInfoEventListener constructor.
     * @param RequestStack|null $request
     */
    public function __construct(RequestStack $request = null)
    {
        if (method_exists($request->getCurrentRequest(), 'getLocale'))
        {
            $this->request = $request->getCurrentRequest();
        }
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return array(
            RatingEvents::RATING_PRE_PERSIST => 'updatePermalink',
        );
    }

    /**
     * @param RatingEvent $event
     */
    public function updatePermalink(RatingEvent $event): void
    {
        if (null === $this->request) {
            return;
        }

        $rating = $event->getRating();

        if (null === $rating->getPermalink()) {
            $rating->setPermalink($this->request->get('permalink'));
        }

        if (null === $rating->getSecurityRole()) {
            $rating->setSecurityRole($this->request->get('securityRole'));
        }
    }
}
