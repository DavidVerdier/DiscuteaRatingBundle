<?php

namespace Discutea\RatingBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

/**
 * Class RatingExtension
 * @package Discutea\RatingBundle\DependencyInjection
 * @copyright 2014 damianociarla https://github.com/damianociarla/DCSRatingBundle
 */
class RatingExtension extends Extension
{
    /**
     * @param array $configs
     * @param ContainerBuilder $container
     * @throws \Exception
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new XmlFileLoader(
            $container,
            new FileLocator(__DIR__.'/../Resources/config')
        );

        $configuration = new Configuration();

        $config = $this->processConfiguration(new Configuration(), $configs);
        $container->setParameter('discutea_rating.configuration', $config);

        $loader->load('services.xml');
    }

    public function getAlias()
    {
        return 'discutea_rating';
    }
}
