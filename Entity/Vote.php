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
     * @ORM\ManyToOne(targetEntity="Chasse\RatingBundle\Entity\Rating", inversedBy="votes")
     * @ORM\JoinColumn(name="rating_id", referencedColumnName="id")
     */
    protected $rating;

    /**
     * @ORM\ManyToOne(targetEntity="Chasse\UserBundle\Entity\User")
     */
    protected $voter;
}
