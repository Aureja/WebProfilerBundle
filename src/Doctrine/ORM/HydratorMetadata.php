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

class HydratorMetadata
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $namespace;

    /**
     * @var string
     */
    private $className;

    /**
     * @var string
     */
    private $parentClass;

    /**
     * HydratorMetadata constructor.
     *
     * @param string $name
     * @param string $namespace
     * @param string $className
     * @param string $parentClass
     */
    public function __construct($name, $namespace, $className, $parentClass)
    {
        $this->name = $name;
        $this->namespace = $namespace;
        $this->className = $className;
        $this->parentClass = $parentClass;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getNamespace()
    {
        return $this->namespace;
    }

    /**
     * @return string
     */
    public function getClassName()
    {
        return $this->className;
    }

    /**
     * @return string
     */
    public function getParentClass()
    {
        return $this->parentClass;
    }

    /**
     * @param array $hydrator
     * 
     * @return HydratorMetadata
     */
    public static function create(array $hydrator)
    {
        $naame = $hydrator['name'];
        $fullClassName  = $hydrator['logging_class'];
        $pos = strrpos($fullClassName, '\\');
        $namespace = substr($fullClassName, 0, $pos);
        $className = substr($fullClassName, $pos + 1);
        $parentClass = $hydrator['class'];
        
        return new self($naame, $namespace, $className, $parentClass);
    }
}
