<?php

use app\src\Common\DTOs\Request\Role\UpdateRoleDTORequest;
use app\src\Common\Helpers\Identifier;
use app\src\Domain\Factories\RoleFactory;
use app\src\Application\Contexts\RequestContext;
use app\src\Common\Helpers\Text;
use app\src\Domain\Factories\QueryParameterFactory;
use app\src\Infrastructure\Constants\RoleConstants;
use app\src\tests\Application\Usecases\RoleUsecaseImpl\RoleUsecaseImpl_Test;
use Ulid\Ulid;

class RoleUsecaseImpl_UpdateTest extends RoleUsecaseImpl_Test
{
    protected string $error_message = "";

    protected function setUp(): void
    {
        parent::setUp();
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

            if ($actual_arg->getRole_name_slug() !== Text::toSlugify($actual_arg->getRole_name(), true)) {
                $this->error_message .= sprintf("expected role_name_slug %s, got %s", Text::toSlugify($actual_arg->getRole_name(), true), $actual_arg->getRole_name_slug());
            }

            if (!isset($params[RoleConstants::ROLE_NAME])) {
                $this->error_message .= sprintf("role_name not being set in array params\n");
            }

            if (QueryParameterFactory::fromArr($params[RoleConstants::ROLE_NAME])->getValue() !== $actual_arg->getRole_name()) {
                $this->error_message .= sprintf("expected role_name %s, got %s", $actual_arg->getRole_name(), QueryParameterFactory::fromArr($params[RoleConstants::ROLE_NAME])->getValue());
            }

            if (!isset($params[RoleConstants::ROLE_NAME_SLUG])) {
                $this->error_message .= sprintf("role_name_slug not being set in array params\n");
            }

            if (QueryParameterFactory::fromArr($params[RoleConstants::ROLE_NAME_SLUG])->getValue() !== $actual_arg->getRole_name_slug()) {
                $this->error_message .= sprintf("expected role_name_slug %s, got %s", $actual_arg->getRole_name_slug(), QueryParameterFactory::fromArr($params[RoleConstants::ROLE_NAME_SLUG])->getValue());
            }
        }

        if ($actual_arg->getDescription()) {
            if (!isset($params[RoleConstants::DESCRIPTION])) {
                $this->error_message .= sprintf("description not being set in array params\n");
            }

            if (QueryParameterFactory::fromArr($params[RoleConstants::DESCRIPTION])->getValue() !== $actual_arg->getDescription()) {
                $this->error_message .= sprintf("expected description %s, got %s", $actual_arg->getDescription(), QueryParameterFactory::fromArr($params[RoleConstants::DESCRIPTION])->getValue());
            }
        }

        if (!is_null($actual_arg->getIs_activated())) {
            if (!isset($params[RoleConstants::IS_ACTIVATED])) {
                $this->error_message .= sprintf("is_activated not being set in array params\n");
            }

            if (QueryParameterFactory::fromArr($params[RoleConstants::IS_ACTIVATED])->getValue() !== $actual_arg->getIs_activated()) {
                $this->error_message .= sprintf("expected is_activated %s, got %s", $actual_arg->getIs_activated(), QueryParameterFactory::fromArr($params[RoleConstants::IS_ACTIVATED])->getValue());
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

            if (QueryParameterFactory::fromArr($params[RoleConstants::IS_ACTIVATED_UPDATED_AT])->getValue() !== $actual_arg->getIs_activated_updated_at()) {
                $this->error_message .= sprintf("expected is_activated_updated_at %s, got %s", $actual_arg->getIs_activated_updated_at(), QueryParameterFactory::fromArr($params[RoleConstants::IS_ACTIVATED_UPDATED_AT])->getValue());
            }

            if (!isset($params[RoleConstants::IS_ACTIVATED_UPDATED_BY])) {
                $this->error_message .= sprintf("is_activated_updated_by not being set in array params\n");
            }

            if (QueryParameterFactory::fromArr($params[RoleConstants::IS_ACTIVATED_UPDATED_BY])->getValue() !== $actual_arg->getIs_activated_updated_by()) {
                $this->error_message .= sprintf("expected is_activated_updated_by %s, got %s", $actual_arg->getIs_activated_updated_by(), QueryParameterFactory::fromArr($params[RoleConstants::IS_ACTIVATED_UPDATED_BY])->getValue());
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
