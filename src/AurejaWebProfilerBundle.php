<?php

namespace Aureja\Bundle\WebProfilerBundle;

use Aureja\Bundle\WebProfilerBundle\DependencyInjection\Compiler\DataCollectorCompilerPass;
use Aureja\Bundle\WebProfilerBundle\DependencyInjection\Compiler\OrmCompilerPass;
use Symfony\Component\ClassLoader\ClassLoader;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\HttpKernel\KernelInterface;

class AurejaWebProfilerBundle extends Bundle
{
    public function __construct(KernelInterface $kernel)
    {
        // register logging hydrators class loader
        $loader = new ClassLoader();
        $loader->addPrefix(
            'AurejaLoggingHydrator\\',
            $kernel->getCacheDir() . DIRECTORY_SEPARATOR . 'aureja_web_profiler'
        );
        $loader->register();
    }

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
