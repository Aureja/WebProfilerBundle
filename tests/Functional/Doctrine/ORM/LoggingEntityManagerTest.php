<?php

namespace Aureja\Bundle\WebProfilerBundle\Tests\Functional\Doctrine\ORM;

use Aureja\Bundle\TestFrameworkBundle\Test\WebTestCase;
use Aureja\Bundle\WebProfilerBundle\Doctrine\ORM\AurejaConfiguration;
use Aureja\Bundle\WebProfilerBundle\Doctrine\ORM\LoggingEntityManager;
use Aureja\Bundle\WebProfilerBundle\Tests\App\Entity\User;
use Doctrine\ORM\Tools\SchemaTool;

/**
 * @dbIsolationPerTest
 */
class LoggingEntityManagerTest extends WebTestCase
{
    /**
     * @var LoggingEntityManager
     */
    private $em;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->initClient();

        $this->em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $this->createSchema();
    }

    public function testEntityManager()
    {
        $this->assertInstanceOf(LoggingEntityManager::class, $this->em);
    }

    public function testPersist()
    {
        $this->em->persist(new User());

        $this->assertStatistic('persist');
    }

    public function testDetach()
    {
        $user = new User();
        $this->insertUser($user);

        $this->em->detach($user);

        $this->assertStatistic('detach');
    }

    public function testMerge()
    {
        $user = new User();
        $this->insertUser($user);

        $this->em->merge($user);

        $this->assertStatistic('merge');
    }

    public function testRefresh()
    {
        $user = new User();
        $this->insertUser($user);

        $this->em->refresh($user);

        $this->assertStatistic('refresh');
    }

    public function testRemove()
    {
        $user = new User();
        $this->insertUser($user);

        $this->em->remove($user);

        $this->assertStatistic('remove');
    }

    public function testFlush()
    {
        $this->em->flush();

        $this->assertStatistic('flush');
    }

    private function assertStatistic($name)
    {
        $logger = $this->em->getConfiguration()->getAttribute(AurejaConfiguration::LOGGER);
        $statistics = $logger->getStatistics();

        $this->assertEquals(1, $statistics[$name]['count']);
        $this->assertNotEmpty($statistics[$name]['time']);
    }

    private function createSchema()
    {
        $schemaTool = new SchemaTool($this->em);
        $schemaTool->createSchema(
            [
                $this->em->getClassMetadata(User::class),
            ]
        );
    }

    private function insertUser(User $user)
    {
        $this->em->persist($user);
        $this->em->flush();
    }
}
