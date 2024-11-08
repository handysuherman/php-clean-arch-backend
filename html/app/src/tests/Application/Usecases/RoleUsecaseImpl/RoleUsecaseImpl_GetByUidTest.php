<?php

use app\src\Common\Helpers\Generation;
use app\src\Common\Helpers\Identifier;
use app\src\Domain\Factories\RoleFactory;
use app\src\tests\Application\Usecases\RoleUsecaseImpl\RoleUsecaseImpl_Test;
use Ulid\Exception\InvalidUlidStringException;

// TODO: more edge cases;
class RoleUsecaseImpl_GetByUidTest extends RoleUsecaseImpl_Test
{
    protected string $error_message = "";

    protected function setUp(): void
    {
        parent::setUp();
    }

    public function testGetByUid_Case_OK()
    {
        $uid = Identifier::newULID();
        $ctx = $this->createRandomContext($uid);

        $entity = RoleFactory::createRandomEntity();

        $this->repository->expects($this->once())
            ->method("findByUid")
            ->with($uid)
            ->willReturn($entity);

        $this->usecase->getByUid($ctx, $uid);
    }

    public function testGetByUid_Case_Err_Exception_Invalid_Ulid_String()
    {
        $this->expectException(InvalidUlidStringException::class);

        $mock_uid = Generation::randomString(12);
        $ctx = $this->createRandomContext($mock_uid);

        $this->repository->expects($this->never())
            ->method("findByUid");

        $this->usecase->getByUid($ctx, $mock_uid);
    }
}
