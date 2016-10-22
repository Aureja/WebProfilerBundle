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

use Aureja\Bundle\WebProfilerBundle\Analyzer\DuplicateQueryAnalyzer;
use Doctrine\DBAL\Logging\DebugStack;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\DataCollector\DataCollector;

class DuplicateQueriesDataCollector extends DataCollector
{
    /**
     * @var array
     */
    private $loggers = [];

    /**
     * DuplicateQueriesDataCollector constructor.
     */
    public function __construct()
    {
        $this->data = [
            'queries_count' => [],
            'identical' => [],
            'similar' => [],
        ];
    }


    /**
     * @param string $name
     * @param DebugStack $logger
     */
    public function addLogger($name, DebugStack $logger)
    {
        $this->loggers[$name] = $logger;
    }

    /**
     * {@inheritdoc}
     */
    public function collect(Request $request, Response $response, \Exception $exception = null)
    {
        foreach ($this->loggers as $name => $logger) {
            $queryAnalyser = new DuplicateQueryAnalyzer();

            foreach ($logger->queries as $query) {
                $queryAnalyser->addQuery($query['sql'], (array)$query['params']);
            }

            $this->data['queries_count'][$name] = $queryAnalyser->getQueriesCount();
            $this->data['identical'][$name] = $queryAnalyser->getIdenticalQueries();
            $this->data['similar'][$name] = $queryAnalyser->getSimilarQueries();
        }
    }

    /**
     * @return mixed
     */
    public function getQueriesCount()
    {
        return array_sum($this->data['queries_count']);
    }

    /**
     * @return array
     */
    public function getIdenticalQueries()
    {
        return $this->data['identical'];
    }

    /**
     * @return int
     */
    public function getIdenticalQueriesCount()
    {
        return $this->countGroupedQueries($this->data['identical']);
    }

    /**
     * @return array
     */
    public function getSimilarQueries()
    {
        return $this->data['similar'];
    }

    /**
     * @return int
     */
    public function getSimilarQueriesCount()
    {
        return $this->countGroupedQueries($this->data['similar']);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'aureja_duplicate_queries';
    }

    /**
     * @param array $queries
     *
     * @return int
     */
    private function countGroupedQueries(array $queries)
    {
        return array_sum(array_map('count', $queries));
    }
}
