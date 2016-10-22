<?php

namespace Aureja\Bundle\WebProfilerBundle\Doctrine\ORM;

use Doctrine\Common\EventManager;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\Configuration;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\EntityManager as BaseEntityManager;

class LoggingEntityManager extends BaseEntityManager
{
    /**
     * @var array
     */
    private $hydrators;

    /**
     * @var Logger 
     */
    private $logger;

    /**
     * {@inheritdoc}
     */
    public static function create($conn, Configuration $config, EventManager $eventManager = null)
    {
        if ( ! $config->getMetadataDriverImpl()) {
            throw ORMException::missingMappingDriverImpl();
        }

        if (is_array($conn)) {
            $conn = \Doctrine\DBAL\DriverManager::getConnection(
                $conn, $config, ($eventManager ?: new EventManager())
            );
        } elseif ($conn instanceof Connection) {
            if ($eventManager !== null && $conn->getEventManager() !== $eventManager) {
                throw ORMException::mismatchedEventManager();
            }
        } else {
            throw new \InvalidArgumentException("Invalid argument: " . $conn);
        }

        return new self($conn, $config, $conn->getEventManager());
    }

    /**
     * {@inheritdoc}
     */
    public function newHydrator($hydrationMode)
    {
        if ($logger = $this->getProfilingLogger()) {
            $hydrators = $this->getHydrators();

            if (isset($hydrators[$hydrationMode])) {
                $class = $hydrators[$hydrationMode]['logging_class'];

                if (class_exists($class)) {
                    return new $class($this);
                }
            }
        }

        return parent::newHydrator($hydrationMode);
    }

    /**
     * {@inheritdoc}
     */
    public function persist($entity)
    {
        if ($logger = $this->getProfilingLogger()) {
            $logger->startPersist();
            parent::persist($entity);
            $logger->stopPersist();

            return;
        }

        parent::persist($entity);
    }

    /**
     * {@inheritdoc}
     */
    public function detach($entity)
    {
        if ($logger = $this->getProfilingLogger()) {
            $logger->startDetach();
            parent::detach($entity);
            $logger->stopDetach();

            return;
        }

        parent::detach($entity);
    }


    /**
     * {@inheritdoc}
     */
    public function merge($entity)
    {
        if ($logger = $this->getProfilingLogger()) {
            $logger->startMerge();
            $result = parent::merge($entity);;
            $logger->stopMerge();

            return $result;
        }

        return parent::merge($entity);
    }

    /**
     * {@inheritdoc}
     */
    public function refresh($entity)
    {
        if ($logger = $this->getProfilingLogger()) {
            $logger->startRefresh();
            parent::refresh($entity);
            $logger->stopRefresh();

            return;
        }

        parent::refresh($entity);
    }

    /**
     * {@inheritdoc}
     */
    public function remove($entity)
    {
        if ($logger = $this->getProfilingLogger()) {
            $logger->startRemove();
            parent::remove($entity);
            $logger->stopRemove();

            return;
        }

        parent::remove($entity);;
    }

    /**
     * {@inheritdoc}
     */
    public function flush()
    {
        if ($logger = $this->getProfilingLogger()) {
            $logger->startFlush();
            parent::flush();
            $logger->stopFlush();

            return;
        }

        parent::flush();
    }

    /**
     * @return array|null
     */
    private function getHydrators()
    {
        if (!$this->hydrators) {
            $this->hydrators = $this->getAttribute(AurejaConfiguration::HYDRATORS);
        }

        return $this->hydrators;
    }

    /**
     * @return Logger|null
     */
    private function getProfilingLogger()
    {
        if (!$this->logger) {
            $this->logger = $this->getAttribute(AurejaConfiguration::LOGGER);
        }

        return $this->logger;
    }

    private function getAttribute($name)
    {
        if ($this->getConfiguration() instanceof AurejaConfiguration) {
            return $this->getConfiguration()->getAttribute($name, null);
        }

        return null;
    }
}
