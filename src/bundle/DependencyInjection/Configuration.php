<?php

namespace Edgar\EzUITreeMenuBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use eZ\Bundle\EzPublishCoreBundle\DependencyInjection\Configuration\SiteAccessAware\Configuration as SiteAccessConfiguration;

class Configuration extends SiteAccessConfiguration
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('edgar_ez_ui_tree_menu');
        $systemNode = $this->generateScopeBaseNode($rootNode);
        $systemNode
            ->scalarNode('pagination_children')
                ->info('Default pagination for childre node')
            ->end()
            ->arrayNode('exclude_content_types')
                ->info('Exclude content from tree menu by content type')
                ->scalarPrototype()
            ->end();

        return $treeBuilder;
    }
}
