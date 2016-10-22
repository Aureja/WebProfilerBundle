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

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Stopwatch\Stopwatch;

class Logger
{
    /**
     * @var array
     */
    protected $hydrations = [];

    /**
     * @var float
     */
    protected $startHydration;

    /**
     * @var int
     */
    protected $currentHydration = 0;

    /**
     * @var array
     */
    protected $statistics = [];

    /**
     * @var float
     */
    protected $statisticsTime = 0;

    /**
     * @var int
     */
    protected $hydrationStack = 0;

    /**
     * @var array
     */
    protected $operationStack = [];

    /**
     * @var array
     */
    protected $metadataStack = [];

    /**
     * @var Stopwatch|null
     */
    protected $stopwatch;

    /**
     * Constructor.
     *
     * @param Stopwatch|null $stopwatch
     */
    public function __construct(Stopwatch $stopwatch = null)
    {
        $this->stopwatch = $stopwatch;
        $names = [
            'metadata',
            'getAllMetadata',
            'getMetadataFor',
            'isTransient',
            'persist',
            'detach',
            'merge',
            'remove',
            'refresh',
            'flush'
        ];

        foreach ($names as $name) {
            $this->statistics[$name] = ['count' => 0, 'time' => 0];
        }
    }

    /**
     * Gets all executed hydrations
     *
     * @return array
     */
    public function getHydrations()
    {
        return $this->hydrations;
    }

    /**
     * Gets statistic of all executed operations
     *
     * @return array
     */
    public function getStatistics()
    {
        return $this->statistics;
    }

    /**
     * Gets a total time of all executed operations
     *
     * @return float
     */
    public function getStatisticsTime()
    {
        return $this->statisticsTime;
    }

    /**
     * Marks a hydration as started
     *
     * @param string $hydrationType
     */
    public function startHydration($hydrationType)
    {
        $this->startHydration = microtime(true);
        $this->hydrations[++$this->currentHydration]['type'] = $hydrationType;
        
        if ($this->stopwatch) {
            $this->stopwatch->start('doctrine.orm.hydrations', 'doctrine');
        }
        
        $this->hydrationStack++;
    }

    /**
     * Marks a hydration as stopped
     *
     * @param int   $resultCount
     * @param array $aliasMap
     */
    public function stopHydration($resultCount, array $aliasMap)
    {
        $this->hydrations[$this->currentHydration]['time'] = microtime(true) - $this->startHydration;
        $this->hydrations[$this->currentHydration]['result_count'] = $resultCount;
        $this->hydrations[$this->currentHydration]['alias_map'] = $aliasMap;
        
        if ($this->stopwatch) {
            $this->stopwatch->stop('doctrine.orm.hydrations');
        }
        
        $this->hydrationStack--;
    }

    /**
     * Marks a persist operation as started
     */
    public function startPersist()
    {
        $this->startOperation('persist');
    }

    /**
     * Marks a persist operation as stopped
     */
    public function stopPersist()
    {
        $this->stopOperation('persist');
    }

    /**
     * Marks a detach operation as started
     */
    public function startDetach()
    {
        $this->startOperation('detach');
    }

    /**
     * Marks a detach operation as stopped
     */
    public function stopDetach()
    {
        $this->stopOperation('detach');
    }

    /**
     * Marks a merge operation as started
     */
    public function startMerge()
    {
        $this->startOperation('merge');
    }

    /**
     * Marks a merge operation as stopped
     */
    public function stopMerge()
    {
        $this->stopOperation('merge');
    }

    /**
     * Marks a refresh operation as started
     */
    public function startRefresh()
    {
        $this->startOperation('refresh');
    }

    /**
     * Marks a refresh operation as stopped
     */
    public function stopRefresh()
    {
        $this->stopOperation('refresh');
    }

    /**
     * Marks a remove operation as started
     */
    public function startRemove()
    {
        $this->startOperation('remove');
    }

    /**
     * Marks a remove operation as stopped
     */
    public function stopRemove()
    {
        $this->stopOperation('remove');
    }

    /**
     * Marks a flush operation as started
     */
    public function startFlush()
    {
        $this->startOperation('flush');
    }

    /**
     * Marks a flush operation as stopped
     */
    public function stopFlush()
    {
        $this->stopOperation('flush');
    }

    /**
     * Marks ClassMetadataFactory::getAllMetadata method as started
     */
    public function startGetAllMetadata()
    {
        $this->startMetadata('getAllMetadata');
    }

    /**
     * Marks ClassMetadataFactory::getAllMetadata method as stopped
     */
    public function stopGetAllMetadata()
    {
        $this->stopMetadata('getAllMetadata');
    }

    /**
     * Marks ClassMetadataFactory::getMetadataFor method as started
     */
    public function startGetMetadataFor()
    {
        $this->startMetadata('getMetadataFor');
    }

    /**
     * Marks ClassMetadataFactory::getMetadataFor method as stopped
     */
    public function stopGetMetadataFor()
    {
        $this->stopMetadata('getMetadataFor');
    }

    /**
     * Marks ClassMetadataFactory::isTransient method as started
     */
    public function startIsTransient()
    {
        $this->startMetadata('isTransient');
    }

    /**
     * Marks ClassMetadataFactory::isTransient method as stopped
     */
    public function stopIsTransient()
    {
        $this->stopMetadata('isTransient');
    }

    /**
     * @param string $name
     */
    protected function startOperation($name)
    {
        $startStopwatch = $this->stopwatch && empty($this->operationStack);
        $this->operationStack[$name][] = microtime(true);

        if ($startStopwatch) {
            $this->stopwatch->start('doctrine.orm.operations', 'doctrine');
        }
    }

    /**
     * @param string $name
     */
    protected function stopOperation($name)
    {
        $time = microtime(true) - array_pop($this->operationStack[$name]);
        $this->statistics[$name]['count'] += 1;

        // add to an execution time only if there are no nested operations
        if (empty($this->operationStack[$name])) {
            unset($this->operationStack[$name]);
            $this->statistics[$name]['time'] += $time;

            // add to a total execution time only if there are no nested operations of any type
            if (empty($this->operationStack)) {
                $this->statisticsTime += $time;

                if ($this->stopwatch) {
                    $this->stopwatch->stop('doctrine.orm.operations');
                }
            }
        }
    }

    /**
     * @param string $name
     */
    protected function startMetadata($name)
    {
        $startStopwatch = $this->stopwatch && empty($this->metadataStack);
        $this->metadataStack[$name][] = microtime(true);

        if ($startStopwatch) {
            $this->stopwatch->start('doctrine.orm.metadata', 'doctrine');
        }
    }

    /**
     * @param string $name
     */
    protected function stopMetadata($name)
    {
        $time = microtime(true) - array_pop($this->metadataStack[$name]);
        $this->statistics[$name]['count'] += 1;
        $this->statistics['metadata']['count'] += 1;

        // add to an execution time only if there are no nested metadata related methods
        if (empty($this->metadataStack[$name])) {
            unset($this->metadataStack[$name]);
            $this->statistics[$name]['time'] += $time;

            // add to a total execution time only if it is standalone metadata related method call
            if (empty($this->metadataStack)) {
                $this->statistics['metadata']['time'] += $time;

                if (0 === $this->hydrationStack && empty($this->operationStack)) {
                    $this->statisticsTime += $time;
                }

                if ($this->stopwatch) {
                    $this->stopwatch->stop('doctrine.orm.metadata');
                }
            }
        }
    }
}
