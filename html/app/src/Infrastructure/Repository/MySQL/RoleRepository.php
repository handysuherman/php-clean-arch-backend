<?php

namespace app\src\Infrastructure\Repository\MySQL;

use app\src\Common\Helpers\Pagination;
use app\src\Domain\Entities\RoleEntity;
use app\src\Domain\Params\ListRoleParams;

interface RoleRepository
{
    public function save(RoleEntity $arg): void;
    public function update(string $cursor, array $params, ?array $query_params = null): void;
    public function findByUid(string $cursor): RoleEntity;
    public function list(ListRoleParams $params): array;
    // public function count(array $query): int;
};
