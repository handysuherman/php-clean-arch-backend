<?php

namespace app\src\tests;

use app\src\Application\Config\Config;
use app\src\Common\Databases\MySQL;
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
    protected ?\PDO $connection;

    protected function setUp(): void
    {
        $dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
        $dotenv->load();

        $this->config = new Config();

        $this->log = new Logger("Test");

        $this->token = new Token($this->config->getPaseto());
    }

    protected function setMySQL(): void
    {
        $this->connection = MySQL::New($this->config->getDatabases()->getMysql()->getHost(), $this->config->getDatabases()->getMysql()->getDb_name(), $this->config->getDatabases()->getMysql()->getUsername(), $this->config->getDatabases()->getMysql()->getPassword(), $this->config->getDatabases()->getMysql()->getPort());
    }

    protected function setRedis(): void {}

    #[Group('concreteGroup')]
    public function testConcreteSurpressWarning()
    {
        $this->assertTrue(true);
    }
}
