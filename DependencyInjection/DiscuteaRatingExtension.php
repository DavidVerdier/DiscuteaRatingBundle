<?php

namespace Discutea\RatingBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * Class DiscuteaRatingExtension
 * @package Discutea\RatingBundle\DependencyInjection
 * @copyright 2014 damianociarla https://github.com/damianociarla/DCSRatingBundle
 */
class DiscuteaRatingExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));

        if (!in_array(strtolower($config['db_driver']), array('orm'))) {
            throw new \InvalidArgumentException(sprintf('Invalid db driver "%s".', $config['db_driver']));
        }

        $loader->load(sprintf('%s.xml', $config['db_driver']));

        $container->setParameter('discutea_rating.base_security_role', $config['base_security_role']);
        $container->setParameter('discutea_rating.base_path_to_redirect', $config['base_path_to_redirect']);
        $container->setParameter('discutea_rating.unique_vote', $config['unique_vote']);
        $container->setParameter('discutea_rating.max_value', $config['max_value']);
        $container->setParameter('discutea_rating.model.rating.class', $config['model']['rating']);
        $container->setParameter('discutea_rating.model.vote.class', $config['model']['vote']);

        $container->setAlias('discutea_rating.manager.rating', $config['service']['manager']['rating']);
        $container->setAlias('discutea_rating.manager.vote', $config['service']['manager']['vote']);

        $loader->load('event.xml');
        $loader->load('twig.xml');
    }
}
