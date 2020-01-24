<?php

namespace Discutea\RatingBundle\Repository;

use Discutea\RatingBundle\Entity\Vote;
use Discutea\RatingBundle\Event\VoteEvent;
use Discutea\RatingBundle\Model\RatingInterface;
use Discutea\RatingBundle\Model\VoteInterface;
use Discutea\RatingBundle\RatingEvents;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @method Vote|null find($id, $lockMode = null, $lockVersion = null)
 * @method Vote|null findOneBy(array $criteria, array $orderBy = null)
 * @method Vote[]    findAll()
 * @method Vote[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VoteRepository extends ServiceEntityRepository
{
    /**
     * @var EventDispatcherInterface
     */
    protected $dispatcher;


    public function __construct(ManagerRegistry $registry, EventDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;

        parent::__construct($registry, Vote::class);
    }

    /**
     * @param RatingInterface $rating
     * @param UserInterface $voter
     * @return Vote|VoteInterface
     */
    public function createVote(RatingInterface $rating, UserInterface $voter)
    {
        $vote = new Vote();
        $vote->setRating($rating);
        $vote->setVoter($voter);

        $this->dispatcher->dispatch(new VoteEvent($vote), RatingEvents::VOTE_CREATE);

        return $vote;
    }

    /**
     * @param RatingInterface $rating
     * @param UserInterface $voter
     * @return Vote|mixed|null
     */
    public function findOneByRatingAndVoter(RatingInterface $rating, UserInterface $voter)
    {
        return $this->findOneBy(array(
            'rating' => $rating,
            'voter' => $voter,
        ));
    }

    /**
     * @param VoteInterface $vote
     * @return mixed|void
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function saveVote(VoteInterface $vote)
    {
        $this->dispatcher->dispatch(new VoteEvent($vote),RatingEvents::VOTE_PRE_PERSIST);

        $this->getEntityManager()->persist($vote);
        $this->getEntityManager()->flush();

        $this->dispatcher->dispatch(new VoteEvent($vote), RatingEvents::VOTE_POST_PERSIST);
    }
}
