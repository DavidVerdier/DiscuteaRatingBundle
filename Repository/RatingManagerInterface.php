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
     * @param array $criteria
     * @param array|null $orderBy
     * @return RatingInterface|null
     */
    public function findOneBy(array $criteria, array $orderBy = null): ?RatingInterface;

    /**
     * @param RatingInterface $rating
     * @return RatingInterface
     */
    public function updateRatingStats(RatingInterface $rating): RatingInterface;

    /**
     * @param int|null $id
     * @return RatingInterface
     */
    public function createRating(int $id = null): RatingInterface;

    /**
     * @param RatingInterface $rating
     */
    public function saveRating(RatingInterface $rating): void;
}
