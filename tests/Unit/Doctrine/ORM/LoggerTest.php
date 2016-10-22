<?php

namespace Aureja\Bundle\WebProfilerBundle\Tests\Unit\Doctrine\ORM;

use Aureja\Bundle\WebProfilerBundle\Doctrine\ORM\Logger;
use Doctrine\ORM\Query;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Stopwatch\Stopwatch;

class LoggerTest extends TestCase
{
    /**
     * @var Logger
     */
    private $logger;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->logger = new Logger(new Stopwatch());
    }

    public function testGetHydrations()
    {
        $this->assertEmpty($this->logger->getHydrations());
    }

    public function testHydration()
    {
        $this->logger->startHydration(Query::HYDRATE_ARRAY);
        $this->logger->stopHydration(5, ['test_entity']);

        $hydrations = $this->logger->getHydrations();


        $this->assertEquals(Query::HYDRATE_ARRAY, $hydrations[1]['type']);
        $this->assertNotEmpty($hydrations[1]['time']);
        $this->assertEquals(5, $hydrations[1]['result_count']);
        $this->assertEquals(['test_entity'], $hydrations[1]['alias_map']);
    }

    public function testPersist()
    {
        $this->logger->startPersist();
        $this->logger->stopPersist();

        $this->assertStatistic('persist');
    }

    public function testDetach()
    {
        $this->logger->startDetach();
        $this->logger->stopDetach();

        $this->assertStatistic('detach');
    }

    public function testMerge()
    {
        $this->logger->startMerge();
        $this->logger->stopMerge();

        $this->assertStatistic('merge');
    }

    public function testRefresh()
    {
        $this->logger->startRefresh();
        $this->logger->stopRefresh();

        $this->assertStatistic('refresh');
    }

    public function testRemove()
    {
        $this->logger->startRemove();
        $this->logger->stopRemove();

        $this->assertStatistic('remove');
    }

    public function testFlush()
    {
        $this->logger->startFlush();
        $this->logger->stopFlush();

        $this->assertStatistic('flush');
    }

    public function testGetAllMetadatah()
    {
        $this->logger->startGetAllMetadata();
        $this->logger->stopGetAllMetadata();

        $this->assertStatistic('getAllMetadata');
    }

    public function testGetMetadataFor()
    {
        $this->logger->startGetMetadataFor();
        $this->logger->stopGetMetadataFor();

        $this->assertStatistic('getMetadataFor');
    }

    public function testIsTransient()
    {
        $this->logger->startIsTransient();
        $this->logger->stopIsTransient();

        $this->assertStatistic('isTransient');
    }

    private function assertStatistic($name)
    {
        $statistics = $this->logger->getStatistics();

        $this->assertEquals(1, $statistics[$name]['count']);
        $this->assertNotEmpty($statistics[$name]['time']);
        $this->assertEquals($this->logger->getStatisticsTime(), $statistics[$name]['time']);
    }
}
