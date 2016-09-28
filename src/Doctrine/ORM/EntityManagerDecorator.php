<?php

namespace Aureja\Bundle\WebProfilerBundle\Doctrine\ORM;

use Doctrine\ORM\Decorator\EntityManagerDecorator as BaseEntityManagerDecorator;

class EntityManagerDecorator extends BaseEntityManagerDecorator
{
    /**
     * @var Logger 
     */
    private $logger;

    /**
     * {@inheritdoc}
     */
    public function newHydrator($hydrationMode)
    {
        if ($logger = $this->getProfilingLogger()) {
            $hydrators = $logger->getHydrators();

            if (isset($hydrators[$hydrationMode])) {
                $className = $hydrators[$hydrationMode]['loggingClass'];

                if (class_exists($className)) {
                    return new $className($this);
                }
            }
        }

        return $this->wrapped->newHydrator($hydrationMode);
    }


    /**
     * {@inheritdoc}
     */
    public function persist($entity)
    {
        if ($logger = $this->getProfilingLogger()) {
            $logger->startPersist();
            $this->wrapped->persist($entity);
            $logger->stopPersist();

            return;
        }

        $this->wrapped->persist($entity);
    }

    /**
     * {@inheritdoc}
     */
    public function detach($entity)
    {
        if ($logger = $this->getProfilingLogger()) {
            $logger->startDetach();
            $this->wrapped->detach($entity);
            $logger->stopDetach();

            return;
        }

        $this->wrapped->detach($entity);
    }


    /**
     * {@inheritdoc}
     */
    public function merge($entity)
    {
        if ($logger = $this->getProfilingLogger()) {
            $logger->startMerge();
            $result = $this->wrapped->merge($entity);;
            $logger->stopMerge();

            return $result;
        }

        return $this->wrapped->merge($entity);
    }

    /**
     * {@inheritdoc}
     */
    public function refresh($entity)
    {
        if ($logger = $this->getProfilingLogger()) {
            $logger->startRefresh();
            $this->wrapped->refresh($entity);
            $logger->stopRefresh();

            return;
        }

        $this->wrapped->refresh($entity);
    }

    /**
     * {@inheritdoc}
     */
    public function remove($entity)
    {
        if ($logger = $this->getProfilingLogger()) {
            $logger->startRemove();
            $this->wrapped->remove($entity);
            $logger->stopRemove();

            return;
        }

        $this->wrapped->remove($entity);;
    }

    /**
     * {@inheritdoc}
     */
    public function flush()
    {
        if ($logger = $this->getProfilingLogger()) {
            $logger->startFlush();
            $this->wrapped->flush();
            $logger->stopFlush();

            return;
        }

        $this->wrapped->flush();
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

        if (false === $this->logger) {
            return null;
        }

        $config = $this->getConfiguration();

        if ($config instanceof Configuration) {
            $this->logger = $config->getAttribute(ConfigurationAttributes::LOGGER, null);
        }

        return $this->logger;
    }
}
