# WebProfilerBundle

[![Build Status](https://travis-ci.org/Aureja/WebProfilerBundle.svg?branch=master)](https://travis-ci.org/Aureja/WebProfilerBundle)

Profiler orm and duplicate queries.

## Installation

**Step 1**. Install via [Composer](https://getcomposer.org/)

```
composer require aureja/web-profiler-bundle "dev-master"
```

**Step 2**. Add to `AppKernel.php`

```php
class AppKernel extends Kernel
{
    /**
     * {@inheritdoc}
     */
    public function registerBundles()
    {
        $bundles = [
             // ...
             new Aureja\Bundle\WebProfilerBundle\AurejaWebProfilerBundle(),
             // ...
        ];
    }
}
```
