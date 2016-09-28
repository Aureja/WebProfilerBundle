<?php

/**
 * @copyright C UAB NFQ Technologies 2016
 *
 * This Software is the property of NFQ Technologies
 * and is protected by copyright law – it is NOT Freeware.
 *
 * Any unauthorized use of this software without a valid license key
 * is a violation of the license agreement and will be prosecuted by
 * civil and criminal law.
 *
 * Contact UAB NFQ Technologies:
 * E-mail: info@nfq.lt
 * http://www.nfq.lt
 */

namespace Aureja\Bundle\WebProfilerBundle\Doctrine\ORM;

use Doctrine\Common\Persistence\Mapping\ClassMetadata;
use Doctrine\Common\Persistence\Mapping\ClassMetadataFactory;

class ClassMetadataFactoryDecorator implements ClassMetadataFactory
{

    /**
     * @var ClassMetadataFactory
     */
    private $wrapper;

    /**
     * {@inheritdoc}
     */
    public function getAllMetadata()
    {
        // TODO: Implement getAllMetadata() method.
    }

    /**
     * {@inheritdoc}
     */
    public function getMetadataFor($className)
    {
        // TODO: Implement getMetadataFor() method.
    }

    /**
     * {@inheritdoc}
     */
    public function hasMetadataFor($className)
    {
        // TODO: Implement hasMetadataFor() method.
    }

    /**
     * {@inheritdoc}
     */
    public function setMetadataFor($className, $class)
    {
        // TODO: Implement setMetadataFor() method.
    }

    /**
     * {@inheritdoc}
     */
    public function isTransient($className)
    {
        // TODO: Implement isTransient() method.
    }
}
