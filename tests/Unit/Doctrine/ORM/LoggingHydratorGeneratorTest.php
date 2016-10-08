<?php

namespace Aureja\Bundle\WebProfilerBundle\Tests\Unit\Doctrine\ORM;

use Aureja\Bundle\WebProfilerBundle\Doctrine\ORM\HydratorMap;
use Aureja\Bundle\WebProfilerBundle\Doctrine\ORM\HydratorMetadata;
use Aureja\Bundle\WebProfilerBundle\Doctrine\ORM\LoggingHydratorGenerator;
use Doctrine\ORM\Query;
use PHPUnit_Framework_TestCase as TestCase;

class LoggingHydratorGeneratorTest extends TestCase
{
    public function testGenerate()
    {
        $hydrators = HydratorMap::getHydrators();
        $metadata = HydratorMetadata::create($hydrators[Query::HYDRATE_ARRAY]);

        $expected = <<<PHP
<?php

namespace AurejaLoggingHydrator;

use Aureja\Bundle\WebProfilerBundle\Doctrine\ORM\AurejaConfiguration;

class LoggingArrayHydrator extends \Doctrine\ORM\Internal\Hydration\ArrayHydrator
{
    public function hydrateAll(\$stmt, \$resultSetMapping, array \$hints = [])
    {
        if (\$logger = \$this->_em->getConfiguration()->getAttribute(AurejaConfiguration::LOGGER)) {
            \$logger->startHydration('ArrayHydrator');
            \$result = parent::hydrateAll(\$stmt, \$resultSetMapping, \$hints);
            \$logger->stopHydration(count(\$result), \$resultSetMapping->getAliasMap());
        
            return \$result;
        }
        
        return parent::hydrateAll(\$stmt, \$resultSetMapping, \$hints);
    }
}
PHP;


        $this->assertEquals($expected, LoggingHydratorGenerator::generate($metadata));
    }
}
