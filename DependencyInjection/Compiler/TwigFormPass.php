<?php

namespace Webridge\LargeFileBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface,
    Symfony\Component\DependencyInjection\ContainerBuilder;

class TwigFormPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasParameter('twig.form.resources')) {
            return;
        }
        $container->setParameter('twig.form.resources', array_merge(
            array('WebridgeLargeFileBundle:Form:largefile.html.twig'),
            $container->getParameter('twig.form.resources')
        ));
    }
}
