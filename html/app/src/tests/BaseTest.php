<?php

namespace app\src\tests;

use app\src\Application\Config\Config;
use app\src\Application\Contexts\RequestContext;
use app\src\Common\Constants\ClaimerRoleConstants;
use app\src\Common\Databases\MySQL;
use app\src\Common\DTOs\ClaimerDTO;
use app\src\Common\Enums\TokenType;
use app\src\Common\Helpers\Generation;
use app\src\Common\Helpers\Time;
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
    protected MySQL $mysql;

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
        $this->mysql = new MySQL($this->config);
    }

    protected function setRedis(): void {}

    #[Group('concreteGroup')]
    public function testConcreteSurpressWarning()
    {
        $this->assertTrue(true);
    }

    protected function createRandomContext(string $user_uid): RequestContext
    {
        $platform_key = Generation::randomString(32);

        $token_dto = new ClaimerDTO();
        $token_dto->setUser_id($user_uid);
        $token_dto->setExpires_at(Time::atomicFormat(Time::addDuration(24)));
        $token_dto->setType(TokenType::ACCESS);
        $token_dto->setPlatform_key($platform_key);
        $token_dto->setRoles([
            [
                ClaimerRoleConstants::ROLE_NAME => 'role1',
                ClaimerRoleConstants::CREATED_AT => Time::atomicMicroFormat(Time::now()),
                ClaimerRoleConstants::UPDATED_AT => Time::atomicMicroFormat(Time::now()),
                ClaimerRoleConstants::IS_BLOCKED => false,
                ClaimerRoleConstants::IS_BLOCKED_AT => Time::atomicMicroFormat(Time::now()),
                ClaimerRoleConstants::IS_BLOCKED_BY => "system-test",
                ClaimerRoleConstants::IS_ACTIVATED => true,
                ClaimerRoleConstants::IS_ACTIVATED_AT => Time::atomicMicroFormat(Time::now()),
                ClaimerRoleConstants::IS_ACTIVATED_BY => "system-test"
            ]
        ]);

        $arg = new RequestContext();
        $arg->setPlatform_key($platform_key);
        $arg->setUser_ip(Generation::randomStringInt(12));
        $arg->setUser_agent(Generation::randomString(12));
        $arg->setAuth_user($token_dto);

        return $arg;
    }

    protected function tearDown(): void
    {
        $this->mysql->setConnection(null);
    }
}
