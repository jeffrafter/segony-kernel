<?php
/*
 * This file is part of the segony package.
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */
namespace Segony\Config;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * @author    Marc Binder <marcandrebinder@gmail.com>
 * @copyright Segony
 * @license   http://opensource.org/licenses/MIT
 */
class MainConfigDefinition implements ConfigurationInterface
{

    /**
     * {@inheritdoc}
     *
     * @api
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode    = $treeBuilder->root('config');

        $rootNode
            ->children()
                ->variableNode('routing')
                ->end()
                ->arrayNode('website')
                    ->children()
                        ->scalarNode('title')->end()
                        ->scalarNode('section_separator')->end()
                    ->end()
                ->end()
                ->arrayNode('twig')
                    ->children()
                        ->scalarNode('cache')
                            ->defaultValue(false)
                        ->end()
                        ->booleanNode('autoescape')
                            ->defaultValue(false)
                        ->end()
                        ->booleanNode('debug')
                            ->defaultValue(false)
                        ->end()
                        ->booleanNode('auto_reload')
                            ->defaultValue(true)
                        ->end()
                        ->booleanNode('strict_variables')
                            ->defaultValue(false)
                        ->end()
                        ->scalarNode('charset')
                            ->defaultValue('utf-8')
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('segment')
                    ->children()
                        ->booleanNode('require_configuration_interface')
                            ->defaultValue(false)
                        ->end()
                        ->booleanNode('allow_view_variation')
                            ->defaultValue(true)
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('debug')
                    ->children()
                        ->booleanNode('enabled')
                            ->defaultValue(false)
                        ->end()
                        ->arrayNode('memorize_bridge')
                            ->children()
                                ->scalarNode('class')
                                    ->defaultValue('Segony\Debug\MemorizeBridge\BlackHoleMemorizer')
                                ->end()
                                ->variableNode('options')->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();


        return $treeBuilder;
    }

}