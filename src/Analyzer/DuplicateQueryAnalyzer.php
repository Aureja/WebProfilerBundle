<?php

/*
 * This file is part of the Aureja package.
 *
 * (c) Tadas Gliaubicas <tadas.gliaubicas@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Aureja\Bundle\WebProfilerBundle\Analyzer;

class DuplicateQueryAnalyzer
{
    /**
     * @var array
     */
    private $queries = [];

    /**
     * @param string $sql
     * @param array $parameters
     */
    public function addQuery($sql, array $parameters = [])
    {
        $this->queries[] = [
            'sql' => $sql,
            'parameters' => $parameters,
        ];
    }

    /**
     * @return int
     */
    public function getQueriesCount()
    {
        return count($this->queries);
    }

    /**
     * @return array
     */
    public function getIdenticalQueries()
    {
        $identicalQueriesCounter = [];
        $identicalQueries = [];
        
        foreach ($this->queries as $query) {
            $queryKey = $this->generateQueryKey($query['sql'], $query['parameters']);

            if (false === isset($identicalQueriesCounter[$queryKey])) {
                $identicalQueriesCounter[$queryKey] = 0;
            }
            
            $identicalQueriesCounter[$queryKey]++;

            if (1 < $identicalQueriesCounter[$queryKey]) {
                $identicalQueries[$queryKey] = [
                    'sql' => $query['sql'],
                    'parameters' => $query['parameters'],
                    'count' => $identicalQueriesCounter[$queryKey],
                ];
            }
        }

        return array_values($identicalQueries);
    }

    /**
     * @return array
     */
    public function getSimilarQueries()
    {
        $similarQueriesCounter = [];
        $similarQueries = [];
        
        foreach ($this->queries as $query) {
            if (0 === count($query['parameters'])) {
                continue;
            }
            
            $queryKey = $this->generateSqlKey($query['sql']);

            if (false === isset($similarQueriesCounter[$queryKey])) {
                $similarQueriesCounter[$queryKey] = 0;
            }
            
            $similarQueriesCounter[$queryKey]++;

            if (1 < $similarQueriesCounter[$queryKey]) {
                $similarQueries[$queryKey] = [
                    'sql' => $query['sql'],
                    'count' => $similarQueriesCounter[$queryKey],
                ];
            }
        }

        return array_values($similarQueries);
    }

    /**
     * @param string $sql
     * @param array $parameters
     * 
     * @return string
     */
    private function generateQueryKey($sql, array $parameters)
    {
        return $this->generateSqlKey($sql) . ':' . $this->generateParametersKey($parameters);
    }

    /**
     * @param string $value
     * 
     * @return string
     */
    private function generateSqlKey($value)
    {
        return sha1($value);
    }

    /**
     * @param array $value
     * 
     * @return string
     */
    private function generateParametersKey(array $value)
    {
        return sha1(serialize($value));
    }
}
