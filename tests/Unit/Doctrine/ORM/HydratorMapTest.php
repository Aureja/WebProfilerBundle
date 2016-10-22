<?php

namespace Aureja\Bundle\WebProfilerBundle\Tests\Unit\Doctrine\ORM;

use Aureja\Bundle\WebProfilerBundle\Doctrine\ORM\HydratorMap;
use PHPUnit\Framework\TestCase;

class HydratorMapTest extends TestCase
{
    public function testDefaultHydrators()
    {
        $this->assertCount(5, HydratorMap::getHydrators());
    }
}
