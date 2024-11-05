<?php

namespace app\src\Infrastructure\Repository\MySQL;

use app\src\Common\Helpers\Pagination;
use app\src\Domain\Entities\QueryParameterEntity;
use app\src\Domain\Entities\RoleEntity;

interface RoleRepository
{
    public function save(RoleEntity $arg): void;
    public function update(string $cursor, array $params, ?array $query_params = null): void;
    public function findByUid(string $cursor): RoleEntity;
    // public function list(Pagination $pagination, array $query, string $sort_by = "created_at", string $sort_order = 'ASC'): array;
    // public function count(array $query): int;
};
