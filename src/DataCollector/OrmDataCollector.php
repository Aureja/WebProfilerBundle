<?php

/*
 * This file is part of the Aureja package.
 *
 * (c) Tadas Gliaubicas <tadas.gliaubicas@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Aureja\Bundle\WebProfilerBundle\DataCollector;

use Aureja\Bundle\WebProfilerBundle\Doctrine\ORM\Logger;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\DataCollector\DataCollector;

class OrmDataCollector extends DataCollector
{
    /**
     * @var Logger 
     */
    private $logger;

    /**
     * @param Logger $logger
     */
    public function __construct(Logger $logger)
    {
        $this->logger = $logger;
    }

    /**
     * {@inheritdoc}
     */
    public function collect(Request $request, Response $response, \Exception $exception = null)
    {
        $this->data['hydrations'] = $this->logger->getHydrations();
        $this->data['statistics'] = $this->logger->getStatistics();
        $this->data['statistics_time'] = $this->logger->getStatisticsTime();
    }

    /**
     * Gets executed hydrations.
     *
     * @return array
     */
    public function getHydrations()
    {
        return $this->data['hydrations'];
    }

    /**
     * Gets a number of executed hydrations.
     *
     * @return int
     */
    public function getHydrationCount()
    {
        return count($this->data['hydrations']);
    }

    /**
     * Gets a total time of all executed hydrations.
     *
     * @return float
     */
    public function getHydrationTime()
    {
        $time = 0;
        foreach ($this->data['hydrations'] as $hydration) {
            if (isset($hydration['time'])) {
                $time += $hydration['time'];
            }
        }

        return $time;
    }

    /**
     * Gets a number of hydrated entities.
     *
     * @return int
     */
    public function getHydratedEntities()
    {
        $result = 0;
        foreach ($this->data['hydrations'] as $hydration) {
            if (isset($hydration['resultCount'])) {
                $result += $hydration['resultCount'];
            }
        }

        return $result;
    }

    /**
     * Gets statistic of executed ORM operations.
     *
     * @return array
     */
    public function getStatistics()
    {
        return $this->data['statistics'];
    }

    /**
     * Gets a total time of all executed hydrations and executed ORM operations.
     *
     * @return float
     */
    public function getTotalTime()
    {
        return $this->getHydrationTime() + $this->data['statistics_time'];
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'aureja_orm';
    }
}
