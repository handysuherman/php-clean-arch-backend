<?php

namespace app\src\Domain\Factories;

use app\src\Common\Constants\ClaimerRoleConstants;
use app\src\Common\DTOs\ClaimerRoleDTO;

class ClaimerRoleDTOFactory
{
    public static function fromArray(array $data): ClaimerRoleDTO
    {
        $dto = new ClaimerRoleDTO;
        $dto->setRole_name($data[ClaimerRoleConstants::ROLE_NAME]);
        $dto->setCreated_at($data[ClaimerRoleConstants::CREATED_AT]);
        $dto->setUpdated_at($data[ClaimerRoleConstants::UPDATED_AT]);
        $dto->setIs_blocked($data[ClaimerRoleConstants::IS_BLOCKED]);
        $dto->setIs_blocked_at($data[ClaimerRoleConstants::IS_BLOCKED_AT]);
        $dto->setIs_blocked_by($data[ClaimerRoleConstants::IS_BLOCKED_BY]);
        $dto->setIs_activated($data[ClaimerRoleConstants::IS_ACTIVATED]);
        $dto->setIs_activated_at($data[ClaimerRoleConstants::IS_ACTIVATED_AT]);
        $dto->setIs_activated_by($data[ClaimerRoleConstants::IS_ACTIVATED_BY]);

        return $dto;
    }

    public static function toArray(ClaimerRoleDTO $dto): array
    {
        return [
            ClaimerRoleConstants::ROLE_NAME => $dto->getRole_name(),
            ClaimerRoleConstants::CREATED_AT => $dto->getCreated_at(),
            ClaimerRoleConstants::UPDATED_AT => $dto->getUpdated_at(),
            ClaimerRoleConstants::IS_BLOCKED => $dto->getIs_blocked(),
            ClaimerRoleConstants::IS_BLOCKED_AT => $dto->getIs_blocked_at(),
            ClaimerRoleConstants::IS_BLOCKED_BY => $dto->getIs_blocked_by(),
            ClaimerRoleConstants::IS_ACTIVATED => $dto->getIs_activated(),
            ClaimerRoleConstants::IS_ACTIVATED_AT => $dto->getIs_activated_at(),
            ClaimerRoleConstants::IS_ACTIVATED_BY => $dto->getIs_activated_by(),
        ];
    }
}
