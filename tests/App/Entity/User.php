<?php

namespace Aureja\Bundle\WebProfilerBundle\Tests\App\Entity;

class User
{
    /**
     * @var int
     */
    private $id;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
}
