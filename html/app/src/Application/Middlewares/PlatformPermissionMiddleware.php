<?php

namespace  app\src\Application\Middlewares;

use app\src\Application\Config\Config;
use app\src\Common\Constants\Exceptions\PlatformPermissionMiddlewareExceptionMessageConstants;
use app\src\Common\Exceptions\PlatformPermissionMiddlewareExceptions\InvalidPlatformException;
use app\src\Common\Loggers\Logger;

class PlatformPermissionMiddleware
{
    private Config $cfg;

    public function __construct(Config $cfg)
    {
        $this->cfg = $cfg;
    }

    public function Validate(string|null|array $requested_platform_key)
    {
        if (empty($requested_platform_key)) {
            throw new InvalidPlatformException(PlatformPermissionMiddlewareExceptionMessageConstants::ERR_INVALID_PLATFORM_KEY);
        }

        if (!$this->IsValidPlatform($requested_platform_key)) {
            throw new InvalidPlatformException(PlatformPermissionMiddlewareExceptionMessageConstants::ERR_INVALID_PLATFORM_KEY);
        }
    }

    private function IsValidPlatform(string $requested_platform_key): bool
    {
        return $requested_platform_key === $this->cfg->getApp()->getPlatform()->getWebsite_platform_key() || $requested_platform_key === $this->cfg->getApp()->getPlatform()->getMobile_platform_key();
    }
}
