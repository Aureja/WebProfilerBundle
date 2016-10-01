<?php

namespace Aureja\Bundle\WebProfilerBundle\DependencyInjection\Compiler;

use Aureja\Bundle\WebProfilerBundle\Doctrine\ORM\ClassMetadataFactory;
use Aureja\Bundle\WebProfilerBundle\Doctrine\ORM\ConfigurationAttributes;
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
            $definition->addMethodCall('setClassMetadataFactoryName', [ClassMetadataFactory::class]);
            $definition->addMethodCall(
                'setAttribute',
                [ConfigurationAttributes::LOGGER, new Reference('aureja_web_profiler.orm.logger')]
            );
        }
    }
}
