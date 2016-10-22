<?php

namespace Aureja\Bundle\WebProfilerBundle\Tests\Unit\Analyzer;

use Aureja\Bundle\WebProfilerBundle\Analyzer\DuplicateQueryAnalyzer;
use PHPUnit\Framework\TestCase;

class DuplicateQueryAnalyzerTest extends TestCase
{
    /**
     * @var DuplicateQueryAnalyzer
     */
    private $analyzer;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->analyzer = new DuplicateQueryAnalyzer();
    }

    /**
     * @dataProvider getIdenticalQueriesDataProvider
     *
     * @param array $queries
     * @param array $expected
     */
    public function testGetIdenticalQueries(array $queries, array $expected)
    {
        foreach ($queries as $query) {
            $this->analyzer->addQuery($query['sql'], $query['params']);
        }

        $this->assertEquals($expected, $this->analyzer->getIdenticalQueries());
    }

    /**
     * @dataProvider getSimilarQueriesDataProvider
     *
     * @param array $queries
     * @param array $expected
     */
    public function testGetSimilarQueries(array $queries, array $expected)
    {
        foreach ($queries as $query) {
            $this->analyzer->addQuery($query['sql'], $query['params']);
        }

        $this->assertEquals($expected, $this->analyzer->getSimilarQueries());
    }

    /**
     * @return array
     */
    public function getIdenticalQueriesDataProvider()
    {
        return [
            [
                'queries' => [
                    [
                        'sql' => 'SELECT * FROM aureja',
                        'params' => [],
                    ],
                    [
                        'sql' => 'SELECT * FROM aureja',
                        'params' => [],
                    ],
                    [
                        'sql' => 'SELECT * FROM aureja WHERE id = ?',
                        'params' => [1],
                    ],
                    [
                        'sql' => 'SELECT * FROM aureja WHERE id = ?',
                        'params' => [1],
                    ],
                    [
                        'sql' => 'SELECT * FROM aureja WHERE id = ?',
                        'params' => [1],
                    ],
                    [
                        'sql' => 'SELECT * FROM aureja ORDER BY id',
                        'params' => [],
                    ],
                    [
                        'sql' => 'SELECT * FROM aureja WHERE id = ?',
                        'params' => [2],
                    ],
                ],
                'expected' => [
                    [
                        'sql' => 'SELECT * FROM aureja',
                        'count' => 2,
                        'parameters' => [],
                    ],
                    [
                        'sql' => 'SELECT * FROM aureja WHERE id = ?',
                        'count' => 3,
                        'parameters' => [1],
                    ]
                ],
            ]
        ];
    }

    /**
     * @return array
     */
    public function getSimilarQueriesDataProvider()
    {
        return [
            [
                'queries' => [
                    [
                        'sql' => 'SELECT * FROM aureja WHERE id = ?',
                        'params' => [1],
                    ],
                    [
                        'sql' => 'SELECT * FROM aureja WHERE id = ?',
                        'params' => [2],
                    ],
                    [
                        'sql' => 'SELECT * FROM aureja',
                        'params' => [],
                    ],
                    [
                        'sql' => 'SELECT * FROM aureja',
                        'params' => [],
                    ],
                    [
                        'sql' => 'SELECT * FROM aureja',
                        'params' => [],
                    ],
                    [
                        'sql' => 'SELECT * FROM aureja WHERE number = ?',
                        'params' => [2],
                    ],
                    [
                        'sql' => 'SELECT * FROM aureja WHERE number = ?',
                        'params' => [2],
                    ],
                    [
                        'sql' => 'SELECT * FROM aureja WHERE number = ?',
                        'params' => [1],
                    ],
                    [
                        'sql' => 'SELECT * FROM aureja ORDER BY id',
                        'params' => [],
                    ],
                    [
                        'sql' => 'SELECT * FROM aureja WHERE name = ?',
                        'params' => ['name'],
                    ]
                ],
                'expected' => [
                    [
                        'sql' => 'SELECT * FROM aureja WHERE id = ?',
                        'count' => 2,
                    ],
                    [
                        'sql' => 'SELECT * FROM aureja',
                        'count' => 3,
                    ],
                    [
                        'sql' => 'SELECT * FROM aureja WHERE number = ?',
                        'count' => 3,
                    ],
                ],
            ]
        ];
    }
}
