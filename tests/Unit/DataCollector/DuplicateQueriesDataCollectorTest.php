<?php

namespace Aureja\Bundle\WebProfilerBundle\Tests\Unit\DataCollector;

use Aureja\Bundle\WebProfilerBundle\DataCollector\DuplicateQueriesDataCollector;
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

    public function testEmpty()
    {
        $this->collector->collect(new Request(), new Response());

        $this->assertEquals([], $this->collector->getIdenticalQueries());
        $this->assertEquals(0, $this->collector->getIdenticalQueriesCount());
        $this->assertEquals([], $this->collector->getSimilarQueries());
        $this->assertEquals(0, $this->collector->getSimilarQueriesCount());
        $this->assertEquals(0, $this->collector->getQueriesCount());
    }
}
