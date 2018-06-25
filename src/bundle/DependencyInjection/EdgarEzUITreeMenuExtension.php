<?php

namespace Edgar\EzUITreeMenuBundle\DependencyInjection;

use eZ\Bundle\EzPublishCoreBundle\DependencyInjection\Configuration\SiteAccessAware\ConfigurationProcessor;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class EdgarEzUITreeMenuExtension extends Extension implements PrependExtensionInterface
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader(
            $container,
            new FileLocator(__DIR__ . '/../Resources/config')
        );

        $loader->load('default_settings.yml');
        $loader->load('services.yml');

        $configuration = $this->getConfiguration($configs, $container);
        $config = $this->processConfiguration($configuration, $configs);

        $processor = new ConfigurationProcessor($container, 'edgar_ez_ui_tree_menu');
        $processor->mapSetting('pagination_children', $config);
        $processor->mapSetting('exclude_content_types', $config);
    }

    public function prepend(ContainerBuilder $container)
    {
        $container->prependExtensionConfig('assetic', array('bundles' => array('EdgarEzUITreeMenuBundle')));
    }
}
