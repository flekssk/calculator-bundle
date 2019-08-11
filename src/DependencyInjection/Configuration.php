<?php

namespace FKS\StringCalculator\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * Generates the configuration tree builder.
     *
     * @return TreeBuilder The tree builder
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();

        $treeBuilder->root('calculator')
                    ->children()
                    ->arrayNode('options')
                    ->defaultValue(
                        [
                            'calculatorAlias' => 'default',
                            'resultAlias'     => 'default',
                        ]
                    )
                    ->scalarPrototype()
                    ->end()
                    ->end();

        return $treeBuilder;
    }
}