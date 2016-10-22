<?php

namespace Aureja\Bundle\WebProfilerBundle\Tests\Unit\Doctrine\ORM;

use Aureja\Bundle\WebProfilerBundle\Doctrine\ORM\HydratorMap;
use Aureja\Bundle\WebProfilerBundle\Doctrine\ORM\HydratorMetadata;
use Doctrine\ORM\Internal\Hydration\ArrayHydrator;
use Doctrine\ORM\Query;
use PHPUnit\Framework\TestCase;

class HydratorMetadataTest extends TestCase
{
    public function testCreate()
    {
        $hydrators = HydratorMap::getHydrators();
        $metadata = HydratorMetadata::create($hydrators[Query::HYDRATE_ARRAY]);

        $this->assertEquals('ArrayHydrator', $metadata->getName());
        $this->assertEquals(HydratorMap::LOGGING_HYDRATOR_NAMESPACE, $metadata->getNamespace());
        $this->assertEquals('LoggingArrayHydrator', $metadata->getClassName());
        $this->assertEquals(ArrayHydrator::class, $metadata->getParentClass());
    }
}
