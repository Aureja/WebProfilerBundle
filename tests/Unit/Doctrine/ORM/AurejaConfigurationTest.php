<?php

namespace Aureja\Bundle\WebProfilerBundle\Tests\Unit\Doctrine\ORM;

use Aureja\Bundle\WebProfilerBundle\Doctrine\ORM\AurejaConfiguration;
use PHPUnit_Framework_TestCase as TestCase;

class AurejaConfigurationTest extends TestCase
{
    public function testSetAttribute()
    {
        $config = new AurejaConfiguration();
        $config->setAttribute('test_name', 'test_value');

        $this->assertEquals('test_value', $config->getAttribute('test_name'));
    }

    public function testNonExistingAttribute()
    {
        $config = new AurejaConfiguration();

        $this->assertEmpty($config->getAttribute('non_existing_name'));
        $this->assertEquals('non_existing_value', $config->getAttribute('non_existing_name', 'non_existing_value'));
    }
}
