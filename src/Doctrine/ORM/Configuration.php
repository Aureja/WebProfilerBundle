<?php

namespace Aureja\Bundle\WebProfilerBundle\Doctrine\ORM;

use Doctrine\ORM\Configuration as BaseConfiguration;

class Configuration extends BaseConfiguration
{
    /**
     * Gets a value of a configuration attribute
     *
     * @param string $name
     * @param mixed $default
     *
     * @return mixed
     */
    public function getAttribute($name, $default = null)
    {
        return isset($this->_attributes[$name]) ? $this->_attributes[$name] : $default;
    }

    /**
     * Sets a value of a configuration attribute
     *
     * @param string $name
     * @param mixed $value
     */
    public function setAttribute($name, $value)
    {
        $this->_attributes[$name] = $value;
    }
}
