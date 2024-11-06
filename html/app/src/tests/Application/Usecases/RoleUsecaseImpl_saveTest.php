<?php

use app\src\Application\Config\Config;
use app\src\Application\Contexts\RequestContext;
use app\src\Application\Usecases\RoleUsecase;
use app\src\Application\Usecases\RoleUsecaseImpl;
use app\src\Common\Constants\ClaimerRoleConstants;
use app\src\Common\DTOs\ClaimerDTO;
use app\src\Common\DTOs\Request\Role\CreateRoleDTORequest;
use app\src\Common\Enums\TokenType;
use app\src\Common\Helpers\Generation;
use app\src\Common\Helpers\Identifier;
use app\src\Common\Helpers\Text;
use app\src\Common\Helpers\Time;
use app\src\Domain\Entities\RoleEntity;
use app\src\Domain\Factories\RoleFactory;
use app\src\Infrastructure\Repository\MySQL\RoleRepository;
use app\src\tests\BaseTest;
use PHPUnit\Framework\MockObject\MockObject;

class RoleUsecaseImpl_saveTest extends BaseTest
{

    /** @var MockObject&RoleRepository */
    private RoleRepository $repository;
    private RoleUsecase $usecase;

    protected string $error_message = "";

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = $this->createMock(RoleRepository::class);

        $this->usecase = new RoleUsecaseImpl($this->repository, $this->config, $this->log);
    }

    public function testSave_Case_OK()
    {
        $uid = Identifier::newULID();
        $entity = RoleFactory::createRandomEntity();
        $ctx = $this->createRandomContext($uid);

        $arg = new CreateRoleDTORequest();
        $arg->setRole_name($entity->getRole_name());
        $arg->setDescription($entity->getDescription());

        $this->repository->expects($this->once())
            ->method("save")
            ->with($this->callback(function (RoleEntity $entity) use ($arg, $ctx) {
                return $this->customMatcher($ctx, $entity, $arg);
            }));

        $this->usecase->save($ctx, $arg);
    }

    private function customMatcher(RequestContext $ctx, RoleEntity $entity, CreateRoleDTORequest $arg): bool
    {
        $this->error_message = "";

        if (empty($arg->getRole_name())) {
            $this->error_message .= "role_name should not be empty\n";
        }

        if (empty($arg->getDescription())) {
            $this->error_message .= "description should not be empty\n";
        }

        $expected_slug = Text::toSlugify($arg->getRole_name(), true);
        if ($entity->getRole_name_slug() !== $expected_slug) {
            $this->error_message .= "Expected role_name slug to be $expected_slug, got {$entity->getRole_name_slug()}\n";
        }

        if ($entity->getRole_name() !== $arg->getRole_name()) {
            $this->error_message .= sprintf("Expected role_name to be %s, got %s\n", $arg->getRole_name(), $entity->getRole_name());
        }

        if ($entity->getDescription() !== $arg->getDescription()) {
            $this->error_message .= sprintf("Expected description to be %s, got %s\n", $arg->getDescription(), $entity->getDescription());
        }

        if (empty($entity->getCreated_at())) {
            $this->error_message .= "created_at should not be empty";
        }

        try {
            Time::revertAtomicMicroFormatToDateTime($entity->getCreated_at());
        } catch (\Exception $e) {
            $this->error_message .= sprintf("expected created_at should be %s, got %s", parent::EXAMPLE_DATE_FORMAT, $entity->getCreated_at());
        }


        if (empty($entity->getCreated_by())) {
            $this->error_message .= "created_by should not be empty";
        }

        if (!empty($this->error_message)) {
            $this->fail($this->error_message);
        }

        return empty($this->error_message);
    }

    private function createRandomContext(string $user_uid): RequestContext
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
}
