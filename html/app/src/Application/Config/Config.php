<?php

namespace app\src\Application\Config;

use app\src\Application\Config\PasetoKeyManager;

class Config
{
    private App $app;
    private Databases $databases;
    private PasetoKeyManager $paseto;
    private Keys $keys;

    public function __construct()
    {
        $this->app = new App();
        $this->paseto = new PasetoKeyManager();
        $this->databases = new Databases();
        $this->keys = new Keys();
    }

    public function getApp(): App
    {
        return $this->app;
    }

    public function getPaseto(): PasetoKeyManager
    {
        return $this->paseto;
    }

    public function getDatabases(): Databases
    {
        return $this->databases;
    }

    public function getKeys(): Keys
    {
        return $this->keys;
    }
}
