<?php

namespace app\src\Infrastructure\Repository\MySQL;

use app\src\Common\Helpers\Pagination;
use app\src\Domain\Entities\RoleEntity;
use app\src\Domain\Params\RoleQueryParams;

interface RoleRepository
{
    public function save(RoleEntity $arg): void;
    public function update(string $cursor, array $params, ?array $query_params = null): void;
    public function findByUid(string $cursor): RoleEntity;
    public function list(RoleQueryParams $params, bool $with_search_text = true): array;
    public function count(RoleQueryParams $params, bool $with_search_text = true): int;
};
