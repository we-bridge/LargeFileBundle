<?php

namespace Webridge\LargeFileBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Webridge\LargeFileBundle\DependencyInjection\Compiler\TwigFormPass;

class WebridgeLargeFileBundle extends Bundle
{

    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new TwigFormPass());
    }

}
