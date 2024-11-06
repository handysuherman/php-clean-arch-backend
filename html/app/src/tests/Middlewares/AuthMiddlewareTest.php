<?php

use app\src\Application\Middlewares\AuthMiddleware;

use app\src\Common\Constants\ClaimerRoleConstants;
use app\src\Common\DTOs\ClaimerDTO;
use app\src\Common\Enums\DurationType;
use app\src\Common\Enums\TokenType;
use app\src\Common\Helpers\Identifier;
use app\src\Common\Helpers\Time;

use app\src\Common\Exceptions\AuthMiddlewareExceptions\InvalidAuthorizationFormatException;
use app\src\Common\Exceptions\AuthMiddlewareExceptions\InvalidAuthorizationTypeException;

use app\src\Common\Exceptions\TokenExceptions\InsufficientTokenPermissionException;
use app\src\Common\Exceptions\TokenExceptions\TokenExpiredException;
use app\src\Common\Exceptions\TokenExceptions\TokenTypeNotMatchException;
use app\src\tests\BaseTest;

class AuthMiddlewareTest extends BaseTest
{
    private AuthMiddleware $middleware;

    protected function setUp(): void
    {
        parent::setUp();
        $this->middleware = new AuthMiddleware($this->token);
    }

    public function testOk()
    {
        $dto = $this->testCreateRandom("web");

        $tokenString = $this->token->create($dto);
        $tokenWithHeader = "bearer $tokenString";

        $this->middleware->Validate($tokenWithHeader, "web");
        $this->assertTrue(true);
    }

    public function testOkWithRolesCheckWithValidRoles()
    {
        $dto = $this->testCreateRandom("web");

        $tokenString = $this->token->create($dto);
        $tokenWithHeader = "bearer $tokenString";

        $allowedRoles = ['role1'];

        $this->middleware->Validate($tokenWithHeader, "web", true, $allowedRoles);
        $this->assertTrue(true);
    }

    public function testExceptionVerifyTokenWithInsufficientPermissions()
    {
        $this->expectException(InsufficientTokenPermissionException::class);

        $dto = $this->testCreateRandom("web");
        $dto->setRoles([]);

        $tokenString = $this->token->create($dto);
        $tokenWithHeader = "bearer $tokenString";

        $allowedRoles = ['role1'];


        $this->middleware->Validate($tokenWithHeader, "web", true, $allowedRoles);
    }
    public function testExceptionVerifyTokenWithBlockedRoles()
    {
        $this->expectException(InsufficientTokenPermissionException::class);

        $dto = $this->testCreateRandom("web");
        $dto->setRoles([
            [
                ClaimerRoleConstants::ROLE_NAME => 'role1',
                ClaimerRoleConstants::CREATED_AT => Time::atomicMicroFormat(Time::now()),
                ClaimerRoleConstants::UPDATED_AT => Time::atomicMicroFormat(Time::now()),
                ClaimerRoleConstants::IS_BLOCKED => true,
                ClaimerRoleConstants::IS_BLOCKED_AT => Time::atomicMicroFormat(Time::now()),
                ClaimerRoleConstants::IS_BLOCKED_BY => "system-test",
                ClaimerRoleConstants::IS_ACTIVATED => true,
                ClaimerRoleConstants::IS_ACTIVATED_AT => Time::atomicMicroFormat(Time::now()),
                ClaimerRoleConstants::IS_ACTIVATED_BY => "system-test"
            ]
        ]);

        $tokenString = $this->token->create($dto);
        $tokenWithHeader = "bearer $tokenString";

        $allowedRoles = ['role1'];


        $this->middleware->Validate($tokenWithHeader, "web", true, $allowedRoles);
    }

    public function testExceptionVerifyTokenWithRolesCheckWithInvalidRolesOrUnavailableAllowedRoles()
    {
        $this->expectException(InsufficientTokenPermissionException::class);

        $dto = $this->testCreateRandom("web");

        $tokenString = $this->token->create($dto);
        $tokenWithHeader = "bearer $tokenString";

        $allowedRoles = ['role122'];

        $this->middleware->Validate($tokenWithHeader, "web", true, $allowedRoles);
    }

    public function testExceptionInvalidAuthorizationFormat()
    {
        $this->expectException(InvalidAuthorizationFormatException::class);

        $dto = $this->testCreateRandom("web");

        $tokenString = $this->token->create($dto);
        $tokenWithHeader = "$tokenString";

        $this->middleware->Validate($tokenWithHeader, "web");
    }

    public function testExceptionInvalidAuthorizationType()
    {
        $this->expectException(InvalidAuthorizationTypeException::class);

        $dto = $this->testCreateRandom("web");

        $tokenString = $this->token->create($dto);
        $tokenWithHeader = "asdadsasd $tokenString";

        $this->middleware->Validate($tokenWithHeader, "web");
    }

    public function testExceptionVerifyTokenWithExpiredTokenDuration()
    {
        $this->expectException(TokenExpiredException::class);
        $this->expectExceptionMessage("This token has expired");

        $dto = $this->testCreateRandom("web");
        $dto->setExpires_at(Time::atomicFormat(Time::addDuration(24, DurationType::MINUTE, true)));

        $tokenString = $this->token->create($dto);
        $tokenWithHeader = "bearer $tokenString";

        $this->middleware->Validate($tokenWithHeader, "web");
    }

    public function testExceptionVerifyTokenWithInvalidType()
    {
        $this->expectException(TokenTypeNotMatchException::class);

        $dto = $this->testCreateRandom("web");
        $dto->settype(TokenType::REFRESH);

        $tokenString = $this->token->create($dto);
        $tokenWithHeader = "bearer $tokenString";

        $this->middleware->Validate($tokenWithHeader, "web");
    }


    private function testCreateRandom(string $platform_key): ClaimerDTO
    {
        $dto = new ClaimerDTO();
        $dto->setUser_id(Identifier::newULID());
        $dto->setExpires_at(Time::atomicFormat(Time::addDuration(24)));
        $dto->setType(TokenType::ACCESS);
        $dto->setPlatform_key($platform_key);
        $dto->setRoles([
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

        return $dto;
    }
}
