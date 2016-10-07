<?php

namespace Aureja\Bundle\WebProfilerBundle\Doctrine\ORM;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadataFactory as BaseClassMetadataFactory;

class LoggingClassMetadataFactory extends BaseClassMetadataFactory
{
    /**
     * @var EntityManagerInterface|null
     */
    private $em;

    /**
     * @var Logger
     */
    private $logger;
    
    /**
     * {@inheritdoc}
     */
    public function setEntityManager(EntityManagerInterface $em)
    {
        parent::setEntityManager($em);

        $this->em = $em;
    }

    /**
     * {@inheritdoc}
     */
    public function getAllMetadata()
    {
        if ($logger = $this->getProfilingLogger()) {
            $logger->startGetAllMetadata();
            $result = parent::getAllMetadata();
            $logger->stopGetAllMetadata();

            return $result;
        }

        return parent::getAllMetadata();

    }

    /**
     * {@inheritdoc}
     */
    public function getMetadataFor($className)
    {
        if ($logger = $this->getProfilingLogger()) {
            $logger->startGetMetadataFor();
            $result = parent::getMetadataFor($className);
            $logger->stopGetMetadataFor();

            return $result;
        }

        return parent::getMetadataFor($className);
    }

    /**
     * {@inheritDoc}
     */
    public function isTransient($class)
    {
        if ($logger = $this->getProfilingLogger()) {
            $logger->startIsTransient();
            $result = parent::isTransient($class);
            $logger->stopIsTransient();

            return $result;
        }

        return parent::isTransient($class);
    }

    /**
     * Gets a profiling logger.
     *
     * @return Logger|null
     */
    private function getProfilingLogger()
    {
        if ($this->logger) {
            return $this->logger;
        }

        if (null === $this->em) {
            return null;
        }

        $config = $this->em->getConfiguration();

        if ($config instanceof AurejaConfiguration) {
            $this->logger = $config->getAttribute(AurejaConfiguration::LOGGER, null);
        }

        return $this->logger;
    }
}
