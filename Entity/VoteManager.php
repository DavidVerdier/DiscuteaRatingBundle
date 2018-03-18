<?php

namespace Discutea\RatingBundle\Entity;

use Doctrine\ORM\EntityManager;
use Discutea\RatingBundle\Model\VoteManager as BaseVoteManager;
use Discutea\RatingBundle\Model\VoteInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Discutea\RatingBundle\Model\RatingInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class VoteManager
 * @package Discutea\RatingBundle\Entity
 * @copyright 2014 damianociarla https://github.com/damianociarla/DCSRatingBundle
 */
class VoteManager extends BaseVoteManager
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $em;

    /**
     * @var \Doctrine\ORM\EntityRepository
     */
    protected $repository;

    /**
     * @var string
     */
    protected $class;

    public function __construct(EventDispatcherInterface $dispatcher, EntityManager $em, $class)
    {
        parent::__construct($dispatcher);

        $this->em = $em;
        $this->repository = $em->getRepository($class);

        $metadata = $em->getClassMetadata($class);
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
