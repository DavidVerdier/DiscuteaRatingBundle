<?php

namespace Discutea\RatingBundle\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Discutea\RatingBundle\RatingEvents;
<<<<<<< HEAD
use Discutea\RatingBundle\Event\RatingEvent;
=======
use Chasse\RatingBundle\Event\RatingEvent;
>>>>>>> 33c10b7b2196601f33c6c0e519ca822ea8d32a67
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
     * Set request
     *
     * @param Request $request
     */
    public function setRequest(RequestStack $request = null)
    {
        if (method_exists($request->getCurrentRequest(), 'getLocale'))
        {
            $this->request = $request->getCurrentRequest();
        }
    }

    public static function getSubscribedEvents()
    {
        return array(
            RatingEvents::RATING_PRE_PERSIST => 'updatePermalink',
        );
    }

    public function updatePermalink(RatingEvent $event)
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
