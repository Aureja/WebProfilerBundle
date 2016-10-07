<?php

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
    private $parentClassName;

    /**
     * HydratorMetadata constructor.
     *
     * @param string $name
     * @param string $namespace
     * @param string $className
     * @param string $parentClassName
     */
    public function __construct($name, $namespace, $className, $parentClassName)
    {
        $this->name = $name;
        $this->namespace = $namespace;
        $this->className = $className;
        $this->parentClassName = $parentClassName;
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
    public function getParentClassName()
    {
        return $this->parentClassName;
    }

    /**
     * @param array $hydrator
     * 
     * @return HydratorMetadata
     */
    public static function create(array $hydrator)
    {
        $naame = $hydrator['name'];
        $fullClassName  = $hydrator['loggingClass'];
        $pos = strrpos($fullClassName, '\\');
        $namespace = substr($fullClassName, 0, $pos);
        $className = substr($fullClassName, $pos + 1);
        $parentClassName = $hydrator['class'];
        
        return new self($naame, $namespace, $className, $parentClassName);
    }
}
