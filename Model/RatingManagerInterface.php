<?php

namespace Discutea\RatingBundle\Model;

/**
 * Interface RatingManagerInterface
 * @package Discutea\RatingBundle\Model
 * @copyright 2014 damianociarla https://github.com/damianociarla/DCSRatingBundle
 */
interface RatingManagerInterface
{
    /**
     * Finds one rating by id
     *
     * @param string $id
     * @return RatingInterface
     */
    public function findOneById($id);

    /**
     * Finds one rating by id
     *
     * @param RatingInterface $rating
     * @return RatingInterface
     */
    public function updateRatingStats(RatingInterface $rating);

    /**
     * Finds one rating by the given criteria
     *
     * @param array $criteria
     * @return array
     */
    public function findBy(array $criteria);

    /**
     * Creates an empty rating instance
     *
     * @param string $id
     * @return RatingInterface
     */
    public function createRating($id = null);

    /**
     * Save or update rating
     *
     * @param \Discutea\RatingBundle\Model\RatingInterface $rating
     * @return RatingInterface
     */
    public function saveRating(RatingInterface $rating);

    /**
     * Returns the rating fully qualified class name
     *
     * @return string
     */
    public function getClass();
}
