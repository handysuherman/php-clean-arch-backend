<?php

use app\src\Common\Exceptions\PlatformPermissionMiddlewareExceptions\InvalidPlatformException;
use app\src\Common\Helpers\Generation;
use app\src\Application\Middlewares\PlatformPermissionMiddleware;
use app\src\tests\BaseTest;

class PlatformPermissionMiddlewareTest extends BaseTest
{
    private PlatformPermissionMiddleware $middleware;

    protected function setUp(): void
    {
        parent::setUp();
        $this->middleware = new PlatformPermissionMiddleware($this->config);
    }

    public function testokWebsite()
    {
        $this->middleware->Validate($this->config->getApp()->getPlatform()->getWebsite_platform_key());
        $this->assertTrue(true);
    }

    public function testokMobile()
    {
        $this->middleware->Validate($this->config->getApp()->getPlatform()->getMobile_platform_key());
        $this->assertTrue(true);
    }

    public function testExceptionEmptyPlatformKey()
    {
        $this->expectException(InvalidPlatformException::class);

        $this->middleware->Validate("");
    }

    public function testExceptionInvalidNotEitherStatedPlatformKey()
    {
        $this->expectException(InvalidPlatformException::class);

        $this->middleware->Validate(Generation::randomString(10));
    }
}
