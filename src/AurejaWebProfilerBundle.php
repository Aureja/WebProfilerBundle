<?php

/*
 * This file is part of the Aureja package.
 *
 * (c) Tadas Gliaubicas <tadas.gliaubicas@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Aureja\Bundle\WebProfilerBundle;

use Aureja\Bundle\WebProfilerBundle\DependencyInjection\Compiler\DataCollectorCompilerPass;
use Aureja\Bundle\WebProfilerBundle\DependencyInjection\Compiler\OrmCompilerPass;
use Aureja\Bundle\WebProfilerBundle\Doctrine\ORM\HydratorMap;
use Symfony\Component\ClassLoader\ClassLoader;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\HttpKernel\KernelInterface;

class AurejaWebProfilerBundle extends Bundle
{
    const CACHE_DIR = 'aureja_web_profiler';

    /**
     * AurejaWebProfilerBundle constructor.
     * 
     * @param KernelInterface $kernel
     */
    public function __construct(KernelInterface $kernel)
    {
        $loader = new ClassLoader();
        $loader->addPrefix(
            HydratorMap::LOGGING_HYDRATOR_NAMESPACE . '\\',
            $kernel->getCacheDir() . DIRECTORY_SEPARATOR . self::CACHE_DIR
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
