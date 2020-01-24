<?php

namespace Discutea\RatingBundle\Entity;

use Doctrine\ORM\EntityManager;
use Discutea\RatingBundle\Model\VoteManager as BaseVoteManager;
use Discutea\RatingBundle\Model\VoteInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class VoteManager
 * @package Discutea\RatingBundle\Entity
 * @copyright 2014 damianociarla https://github.com/damianociarla/DCSRatingBundle
 */
class VoteManager extends BaseVoteManager
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
     * VoteManager constructor.
     * @param EventDispatcherInterface $dispatcher
     * @param EntityManagerInterface $em
     */
    public function __construct(EventDispatcherInterface $dispatcher, EntityManagerInterface $em)
    {
        parent::__construct($dispatcher);

        $this->em = $em;
        $this->repository = $em->getRepository(Vote::class);

        $metadata = $em->getClassMetadata(Vote::class);
        $this->class = $metadata->name;
    }

    public function findBy(array $criteria)
    {
        return $this->repository->findBy($criteria);
    }

    public function findOneBy(array $criteria)
    {
        return $this->repository->findOneBy($criteria);
    }

    protected function doSaveVote(VoteInterface $vote)
    {
        $this->em->persist($vote);
        $this->em->flush();
    }

    public function getClass()
    {
        return $this->class;
    }
}
