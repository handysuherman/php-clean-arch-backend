<?php

namespace app\src\Application\Usecases;

use app\src\Common\DTOs\Request\Role\CreateRoleDTORequest;

interface RoleUsecase
{
    public function save(CreateRoleDTORequest $arg): string;
}
