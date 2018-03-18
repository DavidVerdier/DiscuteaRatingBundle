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
        $rootNode = $treeBuilder->root('chasse');
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
