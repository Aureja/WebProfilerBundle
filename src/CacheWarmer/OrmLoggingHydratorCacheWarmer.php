<?php

/*
 * This file is part of the Aureja package.
 *
 * (c) Tadas Gliaubicas <tadas.gliaubicas@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Aureja\Bundle\WebProfilerBundle\CacheWarmer;

use Aureja\Bundle\WebProfilerBundle\Doctrine\ORM\HydratorMetadata;
use Aureja\Bundle\WebProfilerBundle\Doctrine\ORM\LoggingHydratorGenerator;
use Symfony\Component\HttpKernel\CacheWarmer\CacheWarmer;

class OrmLoggingHydratorCacheWarmer extends CacheWarmer
{
    /**
     * @var array
     */
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
        $cacheDir = implode(DIRECTORY_SEPARATOR, [$cacheDir, 'aureja_web_profiler', 'AurejaLoggingHydrator']);

        if (!$this->ensureDirectoryExists($cacheDir)) {
            return;
        }

        foreach ($this->hydrators as $hydrator) {
            $metadata = HydratorMetadata::create($hydrator);
            
            $this->writeCacheFile(
                $cacheDir . DIRECTORY_SEPARATOR . $metadata->getClassName() . '.php',
                LoggingHydratorGenerator::generate($metadata)
            );
        }
    }

    /**
     * {inheritdoc}
     */
    public function isOptional()
    {
        return false;
    }

    /**
     * @param string $directory
     *
     * @return bool
     */
    private function ensureDirectoryExists($directory)
    {
        if (is_dir($directory)) {
            return true;
        }

        return @mkdir($directory, 0777, true);
    }
}
