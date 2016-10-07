<?php

namespace Aureja\Bundle\WebProfilerBundle\CacheWarmer;

use Symfony\Component\HttpKernel\CacheWarmer\CacheWarmer;

class OrmLoggingHydratorCacheWarmer extends CacheWarmer
{
    private $hydrators;

    public function __construct(array $hydrators)
    {
        $this->hydrators = $hydrators;
    }

    /**
     * {inheritdoc}
     */
    public function warmUp($cacheDir)
    {
        $this->createLoggingHydrators(
            $cacheDir . DIRECTORY_SEPARATOR . 'aureja_web_profiler' . DIRECTORY_SEPARATOR . 'AurejaLoggingHydrator'
        );
    }

    /**
     * {inheritdoc}
     */
    public function isOptional()
    {
        return false;
    }

    /**
     * Create a proxy class for EmailAddress entity and save it in cache
     *
     * @param string $cacheDir
     */
    protected function createLoggingHydrators($cacheDir)
    {
        if (!$this->ensureDirectoryExists($cacheDir)) {
            return;
        }

        foreach ($this->hydrators as $hydrator) {
            $hydratorName    = $hydrator['name'];
            $fullClassName   = $hydrator['loggingClass'];
            $pos             = strrpos($fullClassName, '\\');
            $namespace       = substr($fullClassName, 0, $pos);
            $className       = substr($fullClassName, $pos + 1);
            $parentClassName = $hydrator['class'];
            $this->writeCacheFile(
                $cacheDir . DIRECTORY_SEPARATOR . $className . '.php',
                <<<PHP
<?php
namespace $namespace;

use Aureja\Bundle\WebProfilerBundle\Doctrine\ORM\AurejaConfiguration;

class $className extends \\$parentClassName
{
    public function hydrateAll(\$stmt, \$resultSetMapping, array \$hints = [])
    {
        if (\$logger = \$this->_em->getConfiguration()->getAttribute(AurejaConfiguration::LOGGER)) {
            \$logger->startHydration('$hydratorName');
            \$result = parent::hydrateAll(\$stmt, \$resultSetMapping, \$hints);
            \$logger->stopHydration(count(\$result), \$resultSetMapping->getAliasMap());
        
            return \$result;
        }
        
        return parent::hydrateAll(\$stmt, \$resultSetMapping, \$hints);
    }
}
PHP
            );
        }
    }

    /**
     * @param string $directory
     *
     * @return bool
     */
    protected function ensureDirectoryExists($directory)
    {
        if (is_dir($directory)) {
            return true;
        }

        return @mkdir($directory, 0777, true);
    }
}
