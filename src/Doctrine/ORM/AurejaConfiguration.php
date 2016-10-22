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

use Doctrine\ORM\Configuration as BaseConfiguration;

class AurejaConfiguration extends BaseConfiguration
{
    const HYDRATORS = 'aureja_hydrators';
    const LOGGER = 'aureja_logger';

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
