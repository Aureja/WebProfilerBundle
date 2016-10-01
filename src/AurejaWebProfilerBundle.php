<?php

namespace Aureja\Bundle\WebProfilerBundle;

use Aureja\Bundle\WebProfilerBundle\DependencyInjection\Compiler\DataCollectorCompilerPass;
use Aureja\Bundle\WebProfilerBundle\DependencyInjection\Compiler\OrmCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class AurejaWebProfilerBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        
        $container->addCompilerPass(new DataCollectorCompilerPass());
        $container->addCompilerPass(new OrmCompilerPass());
    }
}
