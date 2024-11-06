<?php

use PHPUnit\Framework\TestCase;
use app\src\Common\Helpers\Token;
use app\src\Application\Config\PasetoKeyManager;
use app\src\Common\Constants\ClaimerRoleConstants;
use app\src\Common\DTOs\ClaimerDTO;
use app\src\Common\Enums\DurationType;
use app\src\Common\Enums\TokenType;
use app\src\Common\Exceptions\TokenExceptions\InsufficientTokenPermissionException;
use app\src\Common\Exceptions\TokenExceptions\TokenExpiredException;
use app\src\Common\Exceptions\TokenExceptions\TokenTypeNotMatchException;
use app\src\Common\Helpers\Identifier;
use app\src\Common\Helpers\Time;
use app\src\tests\BaseTest;

class TokenTest extends BaseTest
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    public function testCreateToken(): void
    {
        $dto = $this->testCreateRandom();
        $tokenString = $this->token->create($dto, 1, DurationType::MINUTE);

        $this->assertIsString($tokenString);
        $this->assertNotEmpty($tokenString);
    }

    public function testVerifyToken(): void
    {
        $dto = $this->testCreateRandom();

        $tokenString = $this->token->create($dto, 1, DurationType::MINUTE);

        $verifiedDTO = $this->token->verify($tokenString, TokenType::ACCESS, 'test_platform');

        $this->assertInstanceOf(ClaimerDTO::class, $verifiedDTO);
        $this->assertEquals($dto->getType(), $verifiedDTO->getType());
    }

    public function testErrExceptionVerifyTokenWithExpiredTokenDuration(): void
    {
        $this->expectException(TokenExpiredException::class);
        $this->expectExceptionMessage("This token has expired");

        $dto = $this->testCreateRandom();
        $dto->setExpires_at(Time::atomicFormat(Time::addDuration(24, DurationType::MINUTE, true)));

        $tokenString = $this->token->create($dto);

        $this->token->verify($tokenString, TokenType::ACCESS, 'test_platform');
    }

    public function testErrExceptionVerifyTokenWithInvalidType(): void
    {
        $this->expectException(TokenTypeNotMatchException::class);

        $dto = $this->testCreateRandom();

        $tokenString = $this->token->create($dto, 1, DurationType::MINUTE);

        $this->token->verify($tokenString, TokenType::REFRESH, 'test_platform');
    }


    public function testVerifyTokenWithRolesCheckWithValidRoles(): void
    {

        $dto = $this->testCreateRandom();
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

        $allowedRoles = ['role1'];

        $tokenString = $this->token->create($dto);

        $verifiedDTO = $this->token->verify($tokenString, TokenType::ACCESS, 'test_platform', true, $allowedRoles);

        $this->assertInstanceOf(ClaimerDTO::class, $verifiedDTO);
        $this->assertEquals($dto->getType(), $verifiedDTO->getType());
    }

    public function testErrExceptionVerifyTokenWithBlockedRoles(): void
    {

        $dto = $this->testCreateRandom();
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

        $allowedRoles = ['role1'];

        $tokenString = $this->token->create($dto);

        $this->expectException(InsufficientTokenPermissionException::class);

        $this->token->verify($tokenString, TokenType::ACCESS, 'test_platform', true, $allowedRoles);
    }

    public function testErrExceptionVerifyTokenWithRolesCheckWithInvalidRolesOrUnavailableAllowedRoles(): void
    {
        $this->expectException(InsufficientTokenPermissionException::class);

        $dto = $this->testCreateRandom();
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

        $allowedRoles = ['role2'];

        $tokenString = $this->token->create($dto);

        $verifiedDTO = $this->token->verify($tokenString, TokenType::ACCESS, 'test_platform', true, $allowedRoles);

        $this->assertInstanceOf(ClaimerDTO::class, $verifiedDTO);
        $this->assertEquals($dto->getType(), $verifiedDTO->getType());
    }

    public function testErrExceptionVerifyTokenWithInsufficientPermissions(): void
    {
        $this->expectException(InsufficientTokenPermissionException::class);

        $dto = $this->testCreateRandom();

        $tokenString = $this->token->create($dto);

        $this->token->verify($tokenString, TokenType::ACCESS, 'test_platform', true, ['role1']);
    }

    private function testCreateRandom(): ClaimerDTO
    {
        $dto = new ClaimerDTO();
        $dto->setUser_id(Identifier::newULID());
        $dto->setExpires_at(Time::atomicFormat(Time::addDuration(24)));
        $dto->setType(TokenType::ACCESS);
        $dto->setPlatform_key('test_platform');
        $dto->setRoles([]);

        return $dto;
    }
}
