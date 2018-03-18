<?php

namespace Discutea\RatingBundle\Model;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Discutea\RatingBundle\RatingEvents;
use Discutea\RatingBundle\Event;

/**
 * Class RatingManager
 * @package Discutea\RatingBundle\Model
 * @copyright 2014 damianociarla https://github.com/damianociarla/DCSRatingBundle
 */
abstract class RatingManager implements RatingManagerInterface
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
     * Finds one rating by id
     *
     * @param string $id
     * @return array|RatingInterface
     */
    public function findOneById($id)
    {
        return $this->findBy(array('id' => $id));
    }

    /**
     * Creates an empty rating instance
     *
     * @param null $id
     * @return RatingInterface|mixed
     */
    public function createRating($id = null)
    {
        $class = $this->getClass();
        $rating = new $class;

        if (null !== $id) {
            $rating->setId($id);
        }

        $this->dispatcher->dispatch(RatingEvents::RATING_CREATE, new Event\RatingEvent($rating));

        return $rating;
    }

    /**
     * @param RatingInterface $rating
     * @return RatingInterface|void
     */
    public function saveRating(RatingInterface $rating)
    {
        $this->dispatcher->dispatch(RatingEvents::RATING_PRE_PERSIST, new Event\RatingEvent($rating));

        $this->doSaveRating($rating);

        $this->dispatcher->dispatch(RatingEvents::RATING_POST_PERSIST, new Event\RatingEvent($rating));
    }

    /**
     * Performs the persistence of the Rating.
     *
     * @abstract
     * @param RatingInterface $rating
     */
    abstract protected function doSaveRating(RatingInterface $rating);
}
