<?php

namespace Discutea\RatingBundle\Entity;

use Discutea\RatingBundle\Model\Rating as BaseRating;

/**
 * @ORM\Entity()
 * @ORM\Table(name="rating_rating")
 * @ORM\ChangeTrackingPolicy("DEFERRED_EXPLICIT")
 */
class Rating extends BaseRating
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string")
     */
    protected $id;

    /**
     * @ORM\OneToMany(targetEntity="Vote", mappedBy="rating")
     */
    protected $votes;
}
