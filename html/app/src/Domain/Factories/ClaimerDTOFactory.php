<?php

namespace app\src\Domain\Factories;

use app\src\Common\Constants\ClaimerConstants;
use app\src\Common\DTOs\ClaimerDTO;
use app\src\Common\Enums\TokenType;

class ClaimerDTOFactory
{
    public static function fromArray(array $data): ClaimerDTO
    {
        if (!isset($data[ClaimerConstants::USER_ID], $data[ClaimerConstants::EXPIRES_AT])) {
            throw new \InvalidArgumentException('Invalid data provided for ClaimerDTO');
        }

        $dto = new ClaimerDTO();

        $dto->setUser_id($data[ClaimerConstants::USER_ID]);
        $dto->setPlatform_key($data[ClaimerConstants::PLATFORM_KEY]);
        $dto->setRoles($data[ClaimerConstants::ROLES]);
        $dto->setExpires_at($data[ClaimerConstants::EXPIRES_AT]);
        $dto->setType(TokenType::from($data[ClaimerConstants::TYPE]));

        return $dto;
    }

    public static function toArray(ClaimerDTO $dto): array
    {
        return [
            ClaimerConstants::USER_ID => $dto->getUser_id(),
            ClaimerConstants::ROLES => $dto->getRoles(),
            ClaimerConstants::PLATFORM_KEY => $dto->getPlatform_key(),
            ClaimerConstants::EXPIRES_AT => $dto->getExpires_at(),
            ClaimerConstants::TYPE => $dto->getType()->value
        ];
    }
}
