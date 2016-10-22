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

use Aureja\Bundle\WebProfilerBundle\Doctrine\ORM\AurejaConfiguration;
use Aureja\Bundle\WebProfilerBundle\Doctrine\ORM\LoggingClassMetadataFactory;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class OrmCompilerPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (false === $container->hasParameter('doctrine.entity_managers')) {
            return;
        }

        $entityManagers = $container->getParameter('doctrine.entity_managers');

        foreach ($entityManagers as $name => $entityManager) {
            $definition = $container->getDefinition(sprintf('doctrine.orm.%s_configuration', $name));
            $definition->addMethodCall('setClassMetadataFactoryName', [LoggingClassMetadataFactory::class]);
            $definition->addMethodCall(
                'setAttribute',
                [AurejaConfiguration::LOGGER, new Reference('aureja_web_profiler.orm.logger')]
            );
            $definition->addMethodCall(
                'setAttribute',
                [AurejaConfiguration::HYDRATORS, new Reference('aureja_web_profiler.orm.hydrators')]
            );
        }
    }
}
