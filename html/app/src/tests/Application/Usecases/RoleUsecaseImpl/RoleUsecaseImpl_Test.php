<?php

namespace app\src\tests\Application\Usecases\RoleUsecaseImpl;

use app\src\Application\Usecases\RoleUsecase;
use app\src\Application\Usecases\RoleUsecaseImpl;
use app\src\Infrastructure\Repository\MySQL\RoleRepository;
use app\src\tests\BaseTest;
use PHPUnit\Framework\MockObject\MockObject;

class RoleUsecaseImpl_Test extends BaseTest
{
    /** @var MockObject&RoleRepository */
    protected RoleRepository $repository;
    protected RoleUsecase $usecase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = $this->createMock(RoleRepository::class);
        $this->usecase = new RoleUsecaseImpl($this->repository, $this->config, $this->log);
    }
}
