<?php

use app\src\Application\Contexts\RequestContext;
use app\src\Common\DTOs\Request\Role\CreateRoleDTORequest;
use app\src\Common\Helpers\Identifier;
use app\src\Common\Helpers\Text;
use app\src\Common\Helpers\Time;
use app\src\Domain\Entities\RoleEntity;
use app\src\Domain\Factories\RoleFactory;
use app\src\tests\Application\Usecases\RoleUsecaseImpl\RoleUsecaseImpl_Test;
use Ulid\Ulid;

// TODO: more edge cases;
class RoleUsecaseImpl_SaveTest extends RoleUsecaseImpl_Test
{
    protected string $error_message = "";

    protected function setUp(): void
    {
        parent::setUp();
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
            Ulid::fromString($entity->getUid());
        } catch (InvalidArgumentException $e) {
            $this->error_message .= "uid: " . $e->getMessage();
        }

        try {
            Time::revertAtomicMicroFormatToDateTime($entity->getCreated_at());
        } catch (\Exception $e) {
            $this->error_message .= sprintf("expected created_at should be %s, got %s", parent::EXAMPLE_DATE_FORMAT, $entity->getCreated_at());
        }

        if (empty($entity->getCreated_by())) {
            $this->error_message .= "created_by should not be empty";
        }

        if ($ctx->getAuth_user()) {
            if ($entity->getCreated_by() !== $ctx->getAuth_user()->getUser_id()) {
                $this->error_message .= sprintf("expected created_by should be %s, got %s", $ctx->getAuth_user()->getUser_id(), $entity->getCreated_by());
            }
        } else {
            if ($entity->getCreated_by() !== "system") {
                $this->error_message .= sprintf("expected created_by should be system, got %s", $entity->getCreated_by());
            }
        }

        if (!empty($this->error_message)) {
            $this->fail($this->error_message);
        }

        return empty($this->error_message);
    }
}
