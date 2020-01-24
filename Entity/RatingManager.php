<?php

namespace Discutea\RatingBundle\Entity;

use Discutea\RatingBundle\Model\RatingManager as BaseRatingManager;
use Discutea\RatingBundle\Model\RatingInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class RatingManager extends BaseRatingManager
{
    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * @var \Doctrine\Persistence\ObjectRepository
     */
    protected $repository;

    /**
     * @var string
     */
    protected $class;

    /**
     * RatingManager constructor.
     * @param EventDispatcherInterface $dispatcher
     * @param EntityManagerInterface $em
     */
    public function __construct(EventDispatcherInterface $dispatcher, EntityManagerInterface $em)
    {
        parent::__construct($dispatcher);

        $this->em = $em;
        $this->repository = $em->getRepository(Rating::class);

        $metadata = $em->getClassMetadata(Rating::class);
        $this->class = $metadata->name;
    }

    /**
     * @param RatingInterface $rating
     * @return RatingInterface
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function updateRatingStats(RatingInterface $rating)
    {
        $qb = $this->em->createQueryBuilder();
        $qb
            ->select('COUNT(v) AS totalVotes, SUM(v.value) AS totalValue')
            ->from($this->getClass(), 'r')
            ->join('r.votes', 'v')
            ->where('r = :r')
            ->setParameter('r', $rating)
        ;

        extract($qb->getQuery()->getSingleResult(\Doctrine\ORM\AbstractQuery::HYDRATE_ARRAY));

        $rating->setNumVotes($totalVotes);
        $rating->setRate(round(($totalValue / $totalVotes), 1));

        $this->saveRating($rating);

        return $rating;
    }

    /**
     * @param array $criteria
     * @return array|object|null
     */
    public function findBy(array $criteria)
    {
        return $this->repository->findOneBy($criteria);
    }

    /**
     * @param RatingInterface $rating
     */
    protected function doSaveRating(RatingInterface $rating)
    {
        $this->em->persist($rating);
        $this->em->flush();
    }

    /**
     * @return string
     */
    public function getClass(): string
    {
        return $this->class;
    }
}
