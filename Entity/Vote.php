<?php

namespace Discutea\RatingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Discutea\RatingBundle\Model\Vote as BaseVote;

/**
 * Class Vote
 *
 * @ORM\Entity()
 * @ORM\Table(name="rating_vote")
 * @ORM\ChangeTrackingPolicy("DEFERRED_EXPLICIT")
 * @ORM\Entity(repositoryClass="Discutea\RatingBundle\Repository\VoteRepository")
 *
 * @package Discutea\RatingBundle\Entity
 * @copyright 2014 damianociarla https://github.com/damianociarla/DCSRatingBundle
 */
class Vote extends BaseVote
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Discutea\RatingBundle\Entity\Rating", inversedBy="votes")
     * @ORM\JoinColumn(name="rating_id", referencedColumnName="id")
     */
    protected $rating;

    /**
     * @ORM\ManyToOne(targetEntity="Symfony\Component\Security\Core\User\UserInterface")
     */
    protected $voter;
}
