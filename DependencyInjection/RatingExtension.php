<?php

namespace Discutea\RatingBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

/**
 * Class RatingExtension
 * @package Discutea\RatingBundle\DependencyInjection
 * @copyright 2014 damianociarla https://github.com/damianociarla/DCSRatingBundle
 */
class RatingExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        $container->setParameter($this->getAlias() . '.base_security_role', $config['base_security_role']);
        $container->setParameter($this->getAlias() . '.unique_vote', $config['unique_vote']);
        $container->setParameter($this->getAlias() . '.max_value', $config['max_value']);
    }

    public function getAlias()
    {
        return 'discutea_rating';
    }
}