<?php

namespace Ice\UsernameGeneratorBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('ice_username_generator');

        $rootNode
            ->children()
            ->scalarNode('username_format')
            ->defaultValue('%s')
            ->info('A printf formatted string with a single %s placeholder.')
            ->end()
            ->scalarNode('sequence_start')
            ->validate()
            ->ifTrue(function ($v) {
            return !is_int($v);
        })
            ->thenInvalid('Must be an integer but %s found.')
            ->end()
            ->defaultValue(1)
            ->info('The lowest number that will be appended to the User\'s initials to form their username.')
            ->end()
            ->end();

        return $treeBuilder;
    }
}
