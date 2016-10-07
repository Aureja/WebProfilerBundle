<?php

/*
 * This file is part of the Aureja package.
 *
 * (c) Tadas Gliaubicas <tadas.gliaubicas@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Aureja\Bundle\WebProfilerBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader;

class AurejaWebProfilerExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('doctrine/orm.xml');
        $loader->load('data-collectors.xml');

        $this->loadHydrators($container);
    }

    private function loadHydrators(ContainerBuilder $container)
    {
        $hydrators = [];

        foreach ($container->getParameter($this->getAlias() . '.orm.hydrators') as $key => $value) {
            if (defined($key)) {
                $key = constant($key);
            }
            
            $value['loggingClass'] = 'AurejaLoggingHydrator\Logging' . $value['name'];
            $hydrators[$key] = $value;
        }

        $container->setParameter($this->getAlias() . '.orm.hydrators', $hydrators);
    }
}
