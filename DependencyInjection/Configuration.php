<?php

namespace Discutea\RatingBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Class Configuration
 * @package Discutea\RatingBundle\DependencyInjection
 * @copyright 2014 damianociarla https://github.com/damianociarla/DCSRatingBundle
 */
class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
<<<<<<< HEAD
        $rootNode = $treeBuilder->root('discutea');
=======
        $rootNode = $treeBuilder->root('chasse');
>>>>>>> 33c10b7b2196601f33c6c0e519ca822ea8d32a67
        $rootNode
            ->children()
            ->scalarNode('base_security_role')->defaultValue('IS_AUTHENTICATED_FULLY')->end()
            ->booleanNode('unique_vote')->defaultTrue()->end()
            ->integerNode('max_value')->defaultValue(5)->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
