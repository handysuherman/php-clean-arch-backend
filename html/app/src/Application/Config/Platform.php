<?php

namespace app\src\Application\Config;

class Platform
{
    private string $platform_header_key;
    private string $mobile_platform_key;
    private string $website_platform_key;

    const APP_PLATFORM_HEADER_KEY = "APP_PLATFORM_HEADER_KEY";
    const APP_PLATFORM_MOBILE_KEY = "APP_PLATFORM_MOBILE_KEY";
    const APP_PLATFORM_WEBSITE_KEY = "APP_PLATFORM_WEBSITE_KEY";

    public function __construct()
    {
        if (!$_SERVER[self::APP_PLATFORM_HEADER_KEY]) {
            throw new \RuntimeException("APP_PLATFORM_HEADER_KEY.undefined");
        }

        $this->platform_header_key = $_SERVER[self::APP_PLATFORM_HEADER_KEY];

        if (!$_SERVER[self::APP_PLATFORM_MOBILE_KEY]) {
            throw new \RuntimeException("APP_PLATFORM_MOBILE_KEY.undefined");
        }

        $this->mobile_platform_key = $_SERVER[self::APP_PLATFORM_MOBILE_KEY];

        if (!$_SERVER[self::APP_PLATFORM_WEBSITE_KEY]) {
            throw new \RuntimeException("APP_PLATFORM_WEBSITE_KEY.undefined");
        }

        $this->website_platform_key = $_SERVER[self::APP_PLATFORM_WEBSITE_KEY];
    }

    public function getPlatform_header_key(): string
    {
        return $this->platform_header_key;
    }

    public function getMobile_platform_key(): string
    {
        return $this->mobile_platform_key;
    }

    public function getWebsite_platform_key(): string
    {
        return $this->website_platform_key;
    }
}
