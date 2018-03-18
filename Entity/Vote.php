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
<<<<<<< HEAD
     * @ORM\ManyToOne(targetEntity="Discutea\RatingBundle\Entity\Rating", inversedBy="votes")
=======
     * @ORM\ManyToOne(targetEntity="Chasse\RatingBundle\Entity\Rating", inversedBy="votes")
>>>>>>> 33c10b7b2196601f33c6c0e519ca822ea8d32a67
     * @ORM\JoinColumn(name="rating_id", referencedColumnName="id")
     */
    protected $rating;

    /**
<<<<<<< HEAD
     * @ORM\ManyToOne(targetEntity="Symfony\Component\Security\Core\User\UserInterface")
=======
     * @ORM\ManyToOne(targetEntity="Chasse\UserBundle\Entity\User")
>>>>>>> 33c10b7b2196601f33c6c0e519ca822ea8d32a67
     */
    protected $voter;
}
