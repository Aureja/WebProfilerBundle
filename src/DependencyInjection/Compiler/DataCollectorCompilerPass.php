<?php

/*
 * This file is part of the Aureja package.
 *
 * (c) Tadas Gliaubicas <tadas.gliaubicas@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Aureja\Bundle\WebProfilerBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class DataCollectorCompilerPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (false === $container->hasDefinition('aureja_web_profiler.data_collector.duplicate_queries')) {
            return;
        }

        $definition = $container->getDefinition('aureja_web_profiler.data_collector.duplicate_queries');
        $connectionNames = $container->get('doctrine')->getConnectionNames();

        foreach ($connectionNames as $name => $serviceId) {
            $loggerId = 'doctrine.dbal.logger.profiling.' . $name;
                
            if ($container->has($loggerId)) {
                $definition->addMethodCall('addLogger', [$name, new Reference($loggerId)]);
            }
        }
    }
}
