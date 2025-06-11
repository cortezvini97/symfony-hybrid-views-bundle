<?php

namespace Cortez\SymfonyHybridViews\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('symfony_hybrid_views');
        $treeBuilder->getRootNode()
            ->children()
                ->scalarNode("dir_views")->end()
                ->scalarNode("cache_dir")->end()
                ->scalarNode("directives_dir")->end()
                ->scalarNode("functions_dir")->end()
                ->booleanNode("cache")->end()
                ->scalarNode("encore")->end()
            ->end();
        
        return $treeBuilder;
    }
}