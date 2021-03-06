<?php

/*
 * This file is part of the Aureja package.
 *
 * (c) Tadas Gliaubicas <tadas.gliaubicas@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Aureja\Bundle\WebProfilerBundle\Doctrine\ORM;

class LoggingHydratorGenerator
{
    /**
     * @param HydratorMetadata $metadata
     * 
     * @return string
     */
    public static function generate(HydratorMetadata $metadata)
    {
        return <<<PHP
<?php

namespace {$metadata->getNamespace()};

use Aureja\Bundle\WebProfilerBundle\Doctrine\ORM\AurejaConfiguration;

class {$metadata->getClassName()} extends \\{$metadata->getParentClass()}
{
    public function hydrateAll(\$stmt, \$resultSetMapping, array \$hints = [])
    {
        if (\$logger = \$this->_em->getConfiguration()->getAttribute(AurejaConfiguration::LOGGER)) {
            \$logger->startHydration('{$metadata->getName()}');
            \$result = parent::hydrateAll(\$stmt, \$resultSetMapping, \$hints);
            \$logger->stopHydration(count(\$result), \$resultSetMapping->getAliasMap());
        
            return \$result;
        }
        
        return parent::hydrateAll(\$stmt, \$resultSetMapping, \$hints);
    }
}
PHP;
    }
}
