<?php

namespace app\src\Application\Config;

class App
{
    private Platform $platform;

    public function __construct()
    {
        $this->platform = new Platform();
    }

    public function getPlatform(): Platform
    {
        return $this->platform;
    }
}
