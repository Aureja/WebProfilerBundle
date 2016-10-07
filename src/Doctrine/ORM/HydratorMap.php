<?php

/*
 * This file is part of the Aureja package.
 *
 * (c) Tadas Gliaubicas <tadas.gliaubicas@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Aureja\Bundle\WebProfilerBundle\Doctrine\ORM;

use Doctrine\ORM\Internal\Hydration\ArrayHydrator;
use Doctrine\ORM\Internal\Hydration\ObjectHydrator;
use Doctrine\ORM\Internal\Hydration\ScalarHydrator;
use Doctrine\ORM\Internal\Hydration\SimpleObjectHydrator;
use Doctrine\ORM\Internal\Hydration\SingleScalarHydrator;
use Doctrine\ORM\Query;

final class HydratorMap
{
    const LOGGING_HYDRATOR_NAMESPACE = 'AurejaLoggingHydrator';

    private static $map = [
        Query::HYDRATE_ARRAY => [
            'name' => 'ArrayHydrator',
            'class' => ArrayHydrator::class,
        ],
        Query::HYDRATE_OBJECT => [
            'name' => 'ObjectHydrator',
            'class' => ObjectHydrator::class,
        ],
        Query::HYDRATE_SCALAR => [
            'name' => 'ScalarHydrator',
            'class' => ScalarHydrator::class,
        ],
        Query::HYDRATE_SIMPLEOBJECT => [
            'name' => 'SimpleObjectHydrator',
            'class' => SimpleObjectHydrator::class,
        ],
        Query::HYDRATE_SINGLE_SCALAR => [
            'name' => 'SingleScalarHydrator',
            'class' => SingleScalarHydrator::class,
        ],
    ];

    /**
     * @param int $key
     * @param string $name
     * @param string $class
     */
    public static function add($key, $name, $class)
    {
        self::$map[$key] = ['name' => $name, 'class' => $class];
    }

    /**
     * @return array
     */
    public static function getHydrators()
    {
        $hydrators = [];

        foreach (self::$map as $key => $value) {
            $value['logging_class'] = self::LOGGING_HYDRATOR_NAMESPACE . '\Logging' . $value['name'];
            $hydrators[$key] = $value;
        }
        
        return $hydrators;
    }
}
