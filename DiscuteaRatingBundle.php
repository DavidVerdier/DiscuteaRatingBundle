<?php

namespace Discutea\RatingBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Discutea\RatingBundle\DependencyInjection\RatingExtension;

class DiscuteaRatingBundle extends Bundle
{
    public function getContainerExtension()
    {
        return new RatingExtension();
    }
}
