<?php

namespace Cortez\SymfonyHybridViews\DependencyInjection;

use Cortez\SymfonyHybridViews\DependencyInjection\Configuration;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class SymfonyHybridViewsExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $dir = dirname(__DIR__, 2).DIRECTORY_SEPARATOR."Resources".DIRECTORY_SEPARATOR."config";
        $loader = new YamlFileLoader($container, new FileLocator($dir));
        $loader->load("services.yaml");
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);
        foreach ($config as $key => $value) {
            $container->setParameter('symfony_hybrid_views.' . $key, $value);
        }
    }
}