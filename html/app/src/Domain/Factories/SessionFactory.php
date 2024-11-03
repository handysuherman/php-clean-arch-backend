<?php

namespace app\src\Domain\Factories;

use app\src\Common\DTOs\Request\SessionDTORequest;
use app\src\Common\DTOs\Response\SessionDTOResponse;
use app\src\Common\Helpers\Generation;
use app\src\Common\Helpers\Identifier;
use app\src\Common\Helpers\Time;
use app\src\Domain\Entities\SessionEntity;
use app\src\Infrastructure\Constants\SessionsConstants;

class SessionFactory
{
    public static function fromRow(array $row): SessionEntity
    {
        $entity = new SessionEntity();
        self::mapArrayToEntity($row, $entity);
        return $entity;
    }

    private static function mapArrayToEntity(array $row, SessionEntity $entity): void
    {
        $entity->setUid($row[SessionsConstants::UID]);
        $entity->setUser_uid($row[SessionsConstants::USER_UID]);
        $entity->setToken($row[SessionsConstants::TOKEN]);
        $entity->setUser_agent($row[SessionsConstants::USER_AGENT]);
        $entity->setUser_platform($row[SessionsConstants::USER_PLATFORM]);
        $entity->setUser_ip($row[SessionsConstants::USER_IP]);
        $entity->setIs_blocked($row[SessionsConstants::IS_BLOCKED]);
        $entity->setIs_blocked_updated_at($row[SessionsConstants::IS_BLOCKED_UPDATED_AT]);
        $entity->setIs_blocked_updated_by($row[SessionsConstants::IS_BLOCKED_UPDATED_BY]);
        $entity->setExpires_at($row[SessionsConstants::EXPIRES_AT]);
        $entity->setCreated_at($row[SessionsConstants::CREATED_AT]);
    }


    public static function toDTOResponse(SessionEntity $entity): SessionDTOResponse
    {
        $dto = new SessionDTOResponse();
        self::mapArrayToDTOResponse($entity, $dto);
        return $dto;
    }


    public static function dtoRequestToEntity(SessionDTORequest $arg): SessionEntity
    {
        $entity = new SessionEntity();
        $entity->setUid($arg->getUid());
        $entity->setUser_uid($arg->getUser_uid());
        $entity->setToken($arg->getToken());
        $entity->setUser_agent($arg->getUser_agent());
        $entity->setUser_platform($arg->getUser_platform());
        $entity->setUser_ip($arg->getUser_ip());
        $entity->setIs_blocked($arg->getIs_blocked());
        $entity->setIs_blocked_updated_at($arg->getIs_blocked_updated_at());
        $entity->setIs_blocked_updated_by($arg->getIs_blocked_updated_by());
        $entity->setExpires_at($arg->getExpires_at());
        $entity->setCreated_at($arg->getCreated_at());

        return $entity;
    }

    public static function createUpdateArray(SessionDTORequest $arg, array $exclude = []): array
    {
        $update_array = [];

        if ($arg->getIs_blocked() && !in_array(SessionsConstants::IS_BLOCKED, $exclude)) {
            $update_array[SessionsConstants::IS_BLOCKED] = $arg->getIs_blocked();
        }

        if ($arg->getIs_blocked_updated_at() && !in_array(SessionsConstants::IS_BLOCKED_UPDATED_AT, $exclude)) {
            $update_array[SessionsConstants::IS_BLOCKED_UPDATED_AT] = $arg->getIs_blocked_updated_at();
        }

        if ($arg->getIs_blocked_updated_by() && !in_array(SessionsConstants::IS_BLOCKED_UPDATED_BY, $exclude)) {
            $update_array[SessionsConstants::IS_BLOCKED_UPDATED_BY] = $arg->getIs_blocked_updated_by();
        }

        return $update_array;
    }

    public static function toKeyValArray(SessionEntity $arg): array
    {
        return [
            SessionsConstants::UID => $arg->getUid(),
            SessionsConstants::USER_UID => $arg->getUser_uid(),
            SessionsConstants::TOKEN => $arg->getToken(),
            SessionsConstants::USER_AGENT => $arg->getUser_agent(),
            SessionsConstants::USER_PLATFORM => $arg->getUser_platform(),
            SessionsConstants::USER_IP => $arg->getUser_ip(),
            SessionsConstants::IS_BLOCKED => $arg->getIs_blocked(),
            SessionsConstants::IS_BLOCKED_UPDATED_AT => $arg->getIs_blocked_updated_at(),
            SessionsConstants::IS_BLOCKED_UPDATED_BY => $arg->getIs_blocked_updated_by(),
            SessionsConstants::EXPIRES_AT => $arg->getExpires_at(),
            SessionsConstants::CREATED_AT => $arg->getCreated_at(),
        ];
    }

    public static function toKeyValDTOResponseArray(SessionDTOResponse $arg): array
    {
        return [
            SessionsConstants::UID => $arg->getUid(),
            SessionsConstants::USER_UID => $arg->getUser_uid(),
            SessionsConstants::USER_AGENT => $arg->getUser_agent(),
            SessionsConstants::USER_PLATFORM => $arg->getUser_platform(),
            SessionsConstants::USER_IP => $arg->getUser_ip(),
            SessionsConstants::IS_BLOCKED => $arg->getIs_blocked(),
            SessionsConstants::IS_BLOCKED_UPDATED_AT => $arg->getIs_blocked_updated_at(),
            SessionsConstants::IS_BLOCKED_UPDATED_BY => $arg->getIs_blocked_updated_by(),
            SessionsConstants::EXPIRES_AT => $arg->getExpires_at(),
            SessionsConstants::CREATED_AT => $arg->getCreated_at(),
        ];
    }

    private static function mapArrayToDTOResponse(SessionEntity $entity, SessionDTOResponse $dto): void
    {
        $entity->setUid($dto->getUid());
        $entity->setUser_uid($dto->getUser_uid());
        $entity->setUser_agent($dto->getUser_agent());
        $entity->setUser_platform($dto->getUser_platform());
        $entity->setUser_ip($dto->getUser_ip());
        $entity->setIs_blocked($dto->getIs_blocked());
        $entity->setIs_blocked_updated_at($dto->getIs_blocked_updated_at());
        $entity->setIs_blocked_updated_by($dto->getIs_blocked_updated_by());
        $entity->setExpires_at($dto->getExpires_at());
        $entity->setCreated_at($dto->getCreated_at());
    }

    public static function createRandomEntity(string $user_uid, string $token, string $expires_at): SessionEntity
    {

        $arg = new SessionEntity();
        $arg->setUid(Identifier::newULID());
        $arg->setUser_uid($user_uid);
        $arg->setToken($token);
        $arg->setUser_agent(Generation::randomString(40));
        $arg->setUser_platform(Generation::randomString(40));
        $arg->setUser_ip(Generation::randomStringInt(12));
        $arg->setIs_blocked(Generation::randomBool());
        $arg->setIs_blocked_updated_at(Time::atomicMicroFormat(Time::now()));
        $arg->setIs_blocked_updated_by(Identifier::newULID());
        $arg->setExpires_at($expires_at);
        $arg->setCreated_at($arg->getCreated_at());

        return $arg;
    }
}
