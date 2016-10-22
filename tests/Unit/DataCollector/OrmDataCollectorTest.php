<?php

namespace Aureja\Bundle\WebProfilerBundle\Tests\Unit\DataCollector;

use Aureja\Bundle\WebProfilerBundle\DataCollector\OrmDataCollector;
use Aureja\Bundle\WebProfilerBundle\Doctrine\ORM\Logger;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Stopwatch\Stopwatch;

class OrmDataCollectorTest extends TestCase
{
    /**
     * @var OrmDataCollector
     */
    private $collector;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->collector = new OrmDataCollector(new Logger(new Stopwatch()));
    }

    public function testEmptyCollector()
    {
        $this->collector->collect(new Request(), new Response());

        $this->assertEquals(0, $this->collector->getHydratedEntities());
        $this->assertEquals([], $this->collector->getHydrations());
        $this->assertEquals(0, $this->collector->getHydrationCount());
        $this->assertEquals(0, $this->collector->getHydrationTime());
        $this->assertEquals(0, $this->collector->getTotalTime());
        $this->assertEquals(
            [
                'metadata' => [
                    'count' => 0,
                    'time' => 0,
                ],
                'getAllMetadata' => [
                    'count' => 0,
                    'time' => 0,
                ],
                'getMetadataFor' => [
                    'count' => 0,
                    'time' => 0,
                ],
                'isTransient' => [
                    'count' => 0,
                    'time' => 0,
                ],
                'persist' => [
                    'count' => 0,
                    'time' => 0,
                ],
                'detach' => [
                    'count' => 0,
                    'time' => 0,
                ],
                'merge' => [
                    'count' => 0,
                    'time' => 0,
                ],
                'remove' => [
                    'count' => 0,
                    'time' => 0,
                ],
                'refresh' => [
                    'count' => 0,
                    'time' => 0,
                ],
                'flush' => [
                    'count' => 0,
                    'time' => 0,
                ],
            ],
            $this->collector->getStatistics()
        );
    }
}
