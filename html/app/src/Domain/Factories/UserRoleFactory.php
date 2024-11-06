<?php

namespace app\src\Domain\Factories;

use app\src\Common\DTOs\Request\UserRoleDTORequest;
use app\src\Common\DTOs\Response\UserRoleDTOResponse;
use app\src\Common\Helpers\Generation;
use app\src\Common\Helpers\Identifier;
use app\src\Common\Helpers\Time;
use app\src\Domain\Entities\UserRoleEntity;
use app\src\Infrastructure\Constants\UserRoleConstants;

class UserRoleFactory
{
    public static function fromRow(array $row): UserRoleEntity
    {
        $entity = new UserRoleEntity();
        self::mapArrayToEntity($row, $entity);
        return $entity;
    }

    private static function mapArrayToEntity(array $row, UserRoleEntity $entity): void
    {
        $entity->setUid($row[UserRoleConstants::UID]);
        $entity->setUser_uid($row[UserRoleConstants::USER_UID]);
        $entity->setRole_uid($row[UserRoleConstants::ROLE_UID]);
        $entity->setCreated_at($row[UserRoleConstants::CREATED_AT]);
        $entity->setCreated_by($row[UserRoleConstants::CREATED_BY]);
        $entity->setIs_blocked($row[UserRoleConstants::IS_BLOCKED]);
        $entity->setIs_blocked_updated_at($row[UserRoleConstants::IS_BLOCKED_UPDATED_AT]);
        $entity->setIs_blocked_updated_by($row[UserRoleConstants::IS_BLOCKED_UPDATED_BY]);
    }


    public static function toDTOResponse(UserRoleEntity $entity): UserRoleDTOResponse
    {
        $dto = new UserRoleDTOResponse();
        self::mapArrayToDTOResponse($entity, $dto);
        return $dto;
    }


    public static function dtoRequestToEntity(UserRoleDTORequest $arg): UserRoleEntity
    {
        $entity = new UserRoleEntity();
        $entity->setUid($arg->getUid());
        $entity->setUser_uid($arg->getUser_uid());
        $entity->setRole_uid($arg->getRole_uid());
        $entity->setCreated_at($arg->getCreated_at());
        $entity->setCreated_by($arg->getCreated_by());
        $entity->setIs_blocked($arg->getIs_blocked());
        $entity->setIs_blocked_updated_at($arg->getIs_blocked_updated_at());
        $entity->setIs_blocked_updated_by($arg->getIs_blocked_updated_by());

        return $entity;
    }

    public static function createUpdateArray(UserRoleDTORequest $arg, array $exclude = []): array
    {
        $update_array = [];

        if ($arg->getIs_blocked() && !in_array(UserRoleConstants::IS_BLOCKED, $exclude)) {
            $update_array[UserRoleConstants::IS_BLOCKED] = $arg->getIs_blocked();
        }

        if ($arg->getIs_blocked_updated_at() && !in_array(UserRoleConstants::IS_BLOCKED_UPDATED_AT, $exclude)) {
            $update_array[UserRoleConstants::IS_BLOCKED_UPDATED_AT] = $arg->getIs_blocked_updated_at();
        }

        if ($arg->getIs_blocked_updated_by() && !in_array(UserRoleConstants::IS_BLOCKED_UPDATED_BY, $exclude)) {
            $update_array[UserRoleConstants::IS_BLOCKED_UPDATED_BY] = $arg->getIs_blocked_updated_by();
        }

        return $update_array;
    }

    public static function toKeyValArray(UserRoleEntity | UserRoleDTOResponse $arg): array
    {
        return [
            UserRoleConstants::UID => $arg->getUid(),
            UserRoleConstants::USER_UID => $arg->getUser_uid(),
            UserRoleConstants::ROLE_UID => $arg->getRole_uid(),
            UserRoleConstants::CREATED_AT => $arg->getCreated_at(),
            UserRoleConstants::CREATED_BY => $arg->getCreated_by(),
            UserRoleConstants::IS_BLOCKED => $arg->getIs_blocked(),
            UserRoleConstants::IS_BLOCKED_UPDATED_AT => $arg->getIs_blocked_updated_at(),
            UserRoleConstants::IS_BLOCKED_UPDATED_BY => $arg->getIs_blocked_updated_by(),
        ];
    }

    private static function mapArrayToDTOResponse(UserRoleEntity $entity, UserRoleDTOResponse $dto): void
    {
        $entity->setUid($dto->getUid());
        $entity->setUser_uid($dto->getUser_uid());
        $entity->setRole_uid($dto->getRole_uid());
        $entity->setCreated_at($dto->getCreated_at());
        $entity->setCreated_by($dto->getCreated_by());
        $entity->setIs_blocked($dto->getIs_blocked());
        $entity->setIs_blocked_updated_at($dto->getIs_blocked_updated_at());
        $entity->setIs_blocked_updated_by($dto->getIs_blocked_updated_by());
    }

    public static function createRandomEntity(string $user_uid, string $role_uid): UserRoleEntity
    {
        $arg = new UserRoleEntity();
        $arg->setUid(Identifier::newULID());
        $arg->setUser_uid($user_uid);
        $arg->setRole_uid($role_uid);
        $arg->setCreated_at(Time::atomicMicroFormat(Time::now()));
        $arg->setCreated_by(Identifier::newULID());
        $arg->setIs_blocked(Generation::randomBool());
        $arg->setIs_blocked_updated_at(Time::atomicMicroFormat(Time::now()));
        $arg->setIs_blocked_updated_by(Identifier::newULID());

        return $arg;
    }
}
