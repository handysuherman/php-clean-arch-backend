<?php

namespace app\src\tests;

use app\src\Application\Config\Config;
use app\src\Common\Helpers\Token;
use app\src\Common\Loggers\Logger;
use Dotenv\Dotenv;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;


class BaseTest extends TestCase
{
    const EXAMPLE_DATE_FORMAT = "2024-11-01T11:02:54.210540+00:00";
    protected Config $config;
    protected Logger $log;
    protected Token $token;

    protected function setUp(): void
    {
        $dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
        $dotenv->load();

        $this->config = new Config();

        $this->log = new Logger("Test");

        $this->token = new Token($this->config->getPaseto());
    }

    #[Group('concreteGroup')]
    public function testConcreteSurpressWarning()
    {
        $this->assertTrue(true);
    }
}
