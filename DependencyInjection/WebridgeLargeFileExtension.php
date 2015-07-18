<?php

namespace Webridge\LargeFileBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;

/**
 */
class WebridgeLargeFileExtension extends Extension implements PrependExtensionInterface
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {

        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
    }

    public function prepend(ContainerBuilder $container)
    {
        $bundles = $container->getParameter('kernel.bundles');
        if (isset($bundles['OneupUploaderBundle'])) {
            $config = [
                'mappings' => [
                    'video' => [
                        'frontend' => 'blueimp',
                        'error_handler' => 'webridge.largefile_error_handler'
                    ]
                ]
            ];
            $container->prependExtensionConfig('oneup_uploader', $config);
        }
    }
}
