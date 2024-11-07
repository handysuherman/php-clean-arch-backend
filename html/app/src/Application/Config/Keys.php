<?php

namespace app\src\Application\Config;

class Keys
{
    const APP_ID = "APP_ID";
    const APP_KEY = "APP_KEY";
    const APP_IDENTIFIER_KEY = "APP_IDENTIFIER_KEY";

    private string $app_id;
    private string $app_key;
    private string $app_identifier_key;

    public function __construct()
    {
        $this->app_id = $_SERVER[self::APP_ID];

        $this->app_key = $_SERVER[self::APP_KEY];

        $this->app_identifier_key = $_SERVER[self::APP_IDENTIFIER_KEY];
    }

    public function getApp_id(): string
    {
        return $this->app_id;
    }

    public function getApp_key(): string
    {
        return $this->app_key;
    }

    public function getApp_identifier_key(): string
    {
        return base64_decode($this->app_identifier_key);
    }
}
