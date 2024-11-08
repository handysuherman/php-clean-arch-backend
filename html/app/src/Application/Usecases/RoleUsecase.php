<?php

namespace app\src\Application\Usecases;

use app\src\Application\Contexts\RequestContext;
use app\src\Common\DTOs\Request\Role\CreateRoleDTORequest;
use app\src\Common\DTOs\Request\Role\ListRoleDTORequest;
use app\src\Common\DTOs\Request\Role\RoleDTORequest;
use app\src\Common\DTOs\Request\Role\UpdateRoleDTORequest;
use app\src\Common\DTOs\Response\RoleDTOResponse;
use app\src\Common\Helpers\Pagination;
use app\src\Domain\Params\RoleQueryParams;

interface RoleUsecase
{
    public function save(RequestContext $ctx, CreateRoleDTORequest $arg): string;
    public function getByUid(RequestContext $ctx, string $uid): RoleDTOResponse;
    public function update(RequestContext $ctx, UpdateRoleDTORequest $arg): string;
    public function list(RequestContext $ctx, ListRoleDTORequest $arg): array;
    public function metadata(): array;
}
