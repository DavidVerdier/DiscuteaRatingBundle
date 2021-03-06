<?php

namespace Discutea\RatingBundle\Twig;

use Symfony\Component\DependencyInjection\Container;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use Twig\TwigFilter;

/**
 * Class RatingExtension
 * @package Discutea\RatingBundle\Twig
 * @copyright 2014 damianociarla https://github.com/damianociarla/DCSRatingBundle
 */
class RatingExtension extends AbstractExtension
{
    private $config;

    public function __construct(array $discuteaRatingConfig)
    {
        $this->config = $discuteaRatingConfig;
    }

    public function getFunctions()
    {
        return array(
            new TwigFunction('getDefaultSecurityRole', array($this, 'getDefaultSecurityRoleFunction'))
        );
    }

    public function getFilters()
    {
        return array(
            new TwigFilter('isHalfStar', array($this, 'isHalfStarFilter'))
        );
    }

    public function getDefaultSecurityRoleFunction()
    {
        return $this->config['base_security_role'];
    }

    public function isHalfStarFilter($value, $compareValue)
    {
        if (ceil($value) == $compareValue) {
            $whole = floor($value);
            $fraction = $value - $whole;

            return $fraction >= 0.5;
        }

        return false;
    }
}
