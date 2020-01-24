<?php

namespace Discutea\RatingBundle\Repository;

use Discutea\RatingBundle\Entity\Rating;
use Discutea\RatingBundle\Entity\Vote;
use Discutea\RatingBundle\Event\RatingEvent;
use Discutea\RatingBundle\Model\RatingInterface;
use Discutea\RatingBundle\Model\RatingManagerInterface;
use Discutea\RatingBundle\RatingEvents;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * @method Rating|null find($id, $lockMode = null, $lockVersion = null)
 * @method Rating|null findOneBy(array $criteria, array $orderBy = null)
 * @method Rating[]    findAll()
 * @method Rating[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RatingRepository extends ServiceEntityRepository implements RatingManagerInterface
{
    /**
     * @var EventDispatcherInterface
     */
    protected $dispatcher;

    /**
     * RatingRepository constructor.
     * @param ManagerRegistry $registry
     * @param EventDispatcherInterface $dispatcher
     */
    public function __construct(ManagerRegistry $registry, EventDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
        parent::__construct($registry, Rating::class);
    }

    /**
     * @param RatingInterface $rating
     * @return RatingInterface
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function updateRatingStats(RatingInterface $rating): RatingInterface
    {
        $qb = $this->createQueryBuilder('r');
        $qb
            ->select('COUNT(v) AS totalVotes, SUM(v.value) AS totalValue')
            ->leftJoin(Vote::class, 'v', 'with', 'v.rating = r')
            ->where('r = :r')
            ->setParameter('r', $rating)
        ;

        extract($qb->getQuery()->getSingleResult(\Doctrine\ORM\AbstractQuery::HYDRATE_ARRAY));

        $rating->setNumVotes($totalVotes);
        $rating->setRate(round(($totalValue / $totalVotes), 1));

        $this->saveRating($rating);

        return $rating;
    }


    public function createRating(int $id = null): RatingInterface
    {
        $rating = new Rating();

        if (null !== $id) {
            $rating->setId($id);
        }

        $this->dispatcher->dispatch(new RatingEvent($rating), RatingEvents::RATING_CREATE);

        return $rating;
    }

    /**
     * @param RatingInterface $rating
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function saveRating(RatingInterface $rating): void
    {
        $this->dispatcher->dispatch(new RatingEvent($rating), RatingEvents::RATING_PRE_PERSIST);


        $this->getEntityManager()->persist($rating);
        $this->getEntityManager()->flush();

        $this->dispatcher->dispatch(new RatingEvent($rating), RatingEvents::RATING_POST_PERSIST);
    }
}
