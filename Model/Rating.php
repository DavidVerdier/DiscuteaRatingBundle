<?php

namespace Discutea\RatingBundle\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Rating
 *
 * @ORM\MappedSuperclass
 *
 * @package Discutea\RatingBundle\Model
 * @copyright 2014 damianociarla https://github.com/damianociarla/DCSRatingBundle
 */
abstract class Rating implements RatingInterface
{
    /**
     * @var int
     *
     * @ORM\Column(type="integer", options={"unsigned"=true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column( name="numVotes", type="integer")
     */
    protected $numVotes;

    /**
     * @ORM\Column(name="rate", type="decimal", precision=4, scale=1)
     */
    protected $rate;

    /**
     * @ORM\Column(name="securityRole", type="string", nullable=true)
     */
    protected $securityRole;

    /**
     * @ORM\Column(name="permalink", type="string", nullable=true)
     */
    protected $permalink;

    /**
     * @ORM\Column(name="createdAt", type="datetime")
     */
    protected $createdAt;


    protected $votes;

    public function __construct()
    {
        $this->rate = 0;
        $this->numVotes = 0;
        $this->createdAt = new \DateTime('now');
        $this->votes = new ArrayCollection();
    }

    /**
     * Set unique string id
     *
     * @param integer $id
     * @return RatingInterface
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get unique string id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set num votes
     *
     * @param integer $numVotes
     * @return RatingInterface
     */
    public function setNumVotes($numVotes)
    {
        $this->numVotes = $numVotes;

        return $this;
    }

    /**
     * Return num votes
     *
     * @return integer
     */
    public function getNumVotes()
    {
        return $this->numVotes;
    }

    /**
     * Set the rate of the votes
     *
     * @param integer $rate
     * @return RatingInterface
     */
    public function setRate($rate)
    {
        $this->rate = $rate;

        return $this;
    }

    /**
     * Get the rate of the votes
     *
     * @return integer
     */
    public function getRate()
    {
        return $this->rate;
    }

    /**
     * Set the permalink of the page
     *
     * @param string $permalink
     * @return RatingInterface
     */
    public function setPermalink($permalink = null)
    {
        $this->permalink = $permalink;

        return $this;
    }

    /**
     * Get the permalink of the page
     *
     * @return string
     */
    public function getPermalink()
    {
        return $this->permalink;
    }

    /**
     * Set the securityRole
     *
     * @param string $securityRole
     * @return RatingInterface
     */
    public function setSecurityRole($securityRole)
    {
        $this->securityRole = $securityRole;

        return $this;
    }

    /**
     * Get the securityRole
     *
     * @return string
     */
    public function getSecurityRole()
    {
        return $this->securityRole;
    }

    /**
     * Sets the date on which the thread was added
     *
     * @param \DateTime $createdAt
     * @return RatingInterface
     */
    public function setCreatedAt(\DateTime $createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get the date on which the thread was added
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function addVote(VoteInterface $vote)
    {
        $this->votes->add($vote);

        return $this;
    }

    public function removeVote(VoteInterface $vote)
    {
        $this->votes->remove($vote);

        return $this;
    }

    public function getVotes()
    {
        return $this->votes;
    }
}
