<?php

use app\src\Application\Usecases\RoleUsecase;
use app\src\Application\Usecases\RoleUsecaseImpl;
use app\src\Common\DTOs\Request\Role\UpdateRoleDTORequest;
use app\src\Common\Helpers\Identifier;
use app\src\Domain\Factories\RoleFactory;
use app\src\Infrastructure\Repository\MySQL\RoleRepository;
use app\src\tests\BaseTest;
use PHPUnit\Framework\MockObject\MockObject;
use app\src\Application\Contexts\RequestContext;
use app\src\Infrastructure\Constants\RoleConstants;
use Ulid\Ulid;

class RoleUsecaseImpl_UpdateTest extends BaseTest
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

    public function testUpdate_Case_OK()
    {
        $uid = Identifier::newULID();
        $entity = RoleFactory::createRandomEntity();
        $ctx = $this->createRandomContext($uid);

        $arg = new UpdateRoleDTORequest();
        $arg->setUid($entity->getUid());
        $arg->setRole_name($entity->getRole_name());
        $arg->setDescription($entity->getDescription());
        $arg->setIs_activated(true);

        $this->repository->expects($this->once())
            ->method("update")
            ->with($this->callback(function (string $cursor) use ($arg) {
                return $this->customCursorMatcher($cursor, $arg);
            }), $this->callback(function (array $params) use ($arg, $ctx) {
                return $this->customParamsMatcher($ctx, $params, $arg);
            }));

        $this->usecase->update($ctx, $arg);
    }

    private function customCursorMatcher(string $cursor, UpdateRoleDTORequest $actual_arg): bool
    {
        $this->error_message = "";

        try {
            Ulid::fromString($actual_arg->getUid());
        } catch (InvalidArgumentException $e) {
            $this->error_message .= sprintf("actual arg: %s\n", $e->getMessage());
        }


        try {
            Ulid::fromString($cursor);
        } catch (InvalidArgumentException $e) {
            $this->error_message .= sprintf("cursor: %s\n", $e->getMessage());
        }

        if ($actual_arg->getUid() !== $cursor) {
            $this->error_message .=  sprintf("expected cursor %s, got %s\n", $actual_arg->getUid(), $cursor);
        }

        if (!empty($this->error_message)) {
            $this->fail($this->error_message);
        }

        return empty($this->error_message);
    }

    private function customParamsMatcher(RequestContext $ctx, array $params, UpdateRoleDTORequest $actual_arg): bool
    {
        $this->error_message = "";

        if ($actual_arg->getRole_name()) {
            if (!$actual_arg->getRole_name_slug()) {
                $this->error_message .= sprintf("role_name_slug should be updated if role name was not null\n");
            }

            if (!isset($params[RoleConstants::ROLE_NAME])) {
                $this->error_message .= sprintf("role_name not being set in array params\n");
            }

            if (!isset($params[RoleConstants::ROLE_NAME_SLUG])) {
                $this->error_message .= sprintf("role_name_slug not being set in array params\n");
            }
        }

        if ($actual_arg->getDescription()) {
            if (!isset($params[RoleConstants::DESCRIPTION])) {
                $this->error_message .= sprintf("description not being set in array params\n");
            }
        }

        if (!is_null($actual_arg->getIs_activated())) {
            if (!isset($params[RoleConstants::IS_ACTIVATED])) {
                $this->error_message .= sprintf("is_activated not being set in array params\n");
            }

            if (!$actual_arg->getIs_activated_updated_at()) {
                $this->error_message .= sprintf("is_activated_updated_at should be set when is_activated param was provided\n");
            }

            if (!$actual_arg->getIs_activated_updated_by()) {
                $this->error_message .= sprintf("is_activated_updated_by should be set when is_activated params was provided\n");
            }

            if (!isset($params[RoleConstants::IS_ACTIVATED_UPDATED_AT])) {
                $this->error_message .= sprintf("is_activated_updated_at not being set in array params\n");
            }

            if (!isset($params[RoleConstants::IS_ACTIVATED_UPDATED_BY])) {
                $this->error_message .= sprintf("is_activated_updated_by not being set in array params\n");
            }


            if ($ctx->getAuth_user()) {
                if ($actual_arg->getIs_activated_updated_by() !== $ctx->getAuth_user()->getUser_id()) {
                    $this->error_message .= sprintf("expected is_activated_updated_by %s, got %s\n", $ctx->getAuth_user()->getUser_id(), $actual_arg->getIs_activated_updated_by());
                }
            }
        }

        if (!empty($this->error_message)) {
            $this->fail($this->error_message);
        }

        return empty($this->error_message);
    }
}
