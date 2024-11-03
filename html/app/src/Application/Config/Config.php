<?php

namespace app\src\Application\Config;

use app\src\Application\Config\PasetoKeyManager;

class Config
{
    private App $app;
    private PasetoKeyManager $paseto;

    public function __construct()
    {
        $this->app = new App();
        $this->paseto = new PasetoKeyManager();
    }

    public function getApp(): App
    {
        return $this->app;
    }

    public function getPaseto(): PasetoKeyManager
    {
        return $this->paseto;
    }
}
