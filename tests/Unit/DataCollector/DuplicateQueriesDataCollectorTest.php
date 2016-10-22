<?php

namespace Aureja\Bundle\WebProfilerBundle\Tests\Unit\DataCollector;

use Aureja\Bundle\WebProfilerBundle\DataCollector\DuplicateQueriesDataCollector;
use Doctrine\DBAL\Logging\DebugStack;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DuplicateQueriesDataCollectorTest extends TestCase
{
    /**
     * @var DuplicateQueriesDataCollector
     */
    private $collector;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->collector = new DuplicateQueriesDataCollector();
    }

    /**
     * @dataProvider collectDataProvider
     *
     * @param array $loggers
     * @param array $expected
     */
    public function testCollect(array $loggers, array $expected)
    {
        foreach ($loggers as $loggerName => $queries) {
            $logger = new DebugStack();
            $logger->queries = $queries;

            $this->collector->addLogger($loggerName, $logger);
        }

        $this->collector->collect(new Request(), new Response());

        $this->assertEquals($expected['identical_queries'], $this->collector->getIdenticalQueries());
        $this->assertEquals($expected['identical_queries_count'], $this->collector->getIdenticalQueriesCount());
        $this->assertEquals($expected['similar_queries'], $this->collector->getSimilarQueries());
        $this->assertEquals($expected['similar_queries_count'], $this->collector->getSimilarQueriesCount());
        $this->assertEquals($expected['queries_count'], $this->collector->getQueriesCount());
    }

    /**
     * @return array
     */
    public function collectDataProvider()
    {
        return [
            'empty' => [
                'loggers' => [],
                'expected' => [
                    'identical_queries' => [],
                    'identical_queries_count' => 0,
                    'similar_queries' => [],
                    'similar_queries_count' => 0,
                    'queries_count' => 0,
                ],
            ],
            'not empty' => [
                'loggers' => [
                    'default' => [
                        [
                            'sql' => 'SELECT * FROM aureja_table WHERE id = ?',
                            'params' => [1],
                        ],
                        [
                            'sql' => 'SELECT * FROM aureja_table WHERE id = ?',
                            'params' => [2],
                        ],
                        [
                            'sql' => 'SELECT * FROM aureja_table',
                            'params' => [],
                        ],
                        [
                            'sql' => 'SELECT * FROM aureja_table',
                            'params' => [],
                        ],
                        [
                            'sql' => 'SELECT * FROM aureja_table',
                            'params' => [],
                        ],
                    ],
                    'config' => [
                        [
                            'sql' => 'SELECT * FROM aureja_table WHERE number = ?',
                            'params' => [2],
                        ],
                        [
                            'sql' => 'SELECT * FROM aureja_table WHERE number = ?',
                            'params' => [2],
                        ],
                        [
                            'sql' => 'SELECT * FROM aureja_table WHERE number = ?',
                            'params' => [1],
                        ],
                        [
                            'sql' => 'SELECT * FROM aureja_table ORDER BY id',
                            'params' => [],
                        ],
                        [
                            'sql' => 'SELECT * FROM aureja_table WHERE name = ?',
                            'params' => ['name'],
                        ]],
                ],
                'expected' => [
                    'identical_queries' => [
                        'default' => [
                            [
                                'sql' => 'SELECT * FROM aureja_table',
                                'parameters' => [],
                                'count' => 3,
                            ],
                        ],
                        'config' => [
                            [
                                'sql' => 'SELECT * FROM aureja_table WHERE number = ?',
                                'parameters' => [2],
                                'count' => 2,
                            ],
                        ],
                    ],
                    'identical_queries_count'  => 2,
                    'similar_queries' => [
                        'default' => [
                            [
                                'sql' => 'SELECT * FROM aureja_table WHERE id = ?',
                                'count' => 2,
                            ],
                            [
                                'sql' => 'SELECT * FROM aureja_table',
                                'count' => 3,
                            ],
                        ],
                        'config' => [
                            [
                                'sql' => 'SELECT * FROM aureja_table WHERE number = ?',
                                'count' => 3,
                            ],
                        ],
                    ],
                    'similar_queries_count' => 3,
                    'queries_count' => 10,
                ],
            ]
        ];
    }
}
