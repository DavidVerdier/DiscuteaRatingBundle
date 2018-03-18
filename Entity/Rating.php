<?php

namespace Discutea\RatingBundle\Entity;

use Discutea\RatingBundle\Model\Rating as BaseRating;
<<<<<<< HEAD
use Doctrine\ORM\Mapping as ORM;
=======
>>>>>>> 33c10b7b2196601f33c6c0e519ca822ea8d32a67

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
