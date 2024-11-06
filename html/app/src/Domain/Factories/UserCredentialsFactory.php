<?php

namespace app\src\Domain\Factories;

use app\src\Common\DTOs\Request\UserCredentialsDTORequest;
use app\src\Common\DTOs\Response\UserCredentialsDTOResponse;
use app\src\Common\Helpers\Generation;
use app\src\Common\Helpers\Identifier;
use app\src\Common\Helpers\Password;
use app\src\Common\Helpers\Time;
use app\src\Domain\Entities\UserCredentialsEntity;
use app\src\Infrastructure\Constants\UserCredentialsConstants;

class UserCredentialsFactory
{
    public static function fromRow(array $row): UserCredentialsEntity
    {
        $entity = new UserCredentialsEntity();
        self::mapArrayToEntity($row, $entity);
        return $entity;
    }

    private static function mapArrayToEntity(array $row, UserCredentialsEntity $entity): void
    {
        $entity->setUid($row[UserCredentialsConstants::UID]);
        $entity->setUser_uid($row[UserCredentialsConstants::USER_UID]);
        $entity->setHas_hashed_password($row[UserCredentialsConstants::HAS_HASHED_PASSWORD]);
        $entity->setHashed_password($row[UserCredentialsConstants::HASHED_PASSWORD]);
        $entity->setPassword_changed_at($row[UserCredentialsConstants::PASSWORD_CHANGED_AT]);
        $entity->setIs_password_removed($row[UserCredentialsConstants::IS_PASSWORD_REMOVED]);
        $entity->setIs_password_removed_updated_at($row[UserCredentialsConstants::IS_PASSWORD_REMOVED_UPDATED_AT]);
        $entity->setIs_password_removed_updated_by($row[UserCredentialsConstants::IS_PASSWORD_REMOVED_UPDATED_BY]);
    }


    public static function toDTOResponse(UserCredentialsEntity $entity): UserCredentialsDTOResponse
    {
        $dto = new UserCredentialsDTOResponse();
        self::mapArrayToDTOResponse($entity, $dto);
        return $dto;
    }


    public static function dtoRequestToEntity(UserCredentialsDTORequest $arg): UserCredentialsEntity
    {
        $entity = new UserCredentialsEntity();
        $entity->setUid($arg->getUid());
        $entity->setUser_uid($arg->getUser_uid());
        $entity->setHas_hashed_password($arg->getHas_hashed_password());
        $entity->setHashed_password($arg->getHashed_password());
        $entity->setPassword_changed_at($arg->getPassword_changed_at());
        $entity->setIs_password_removed($arg->getIs_password_removed());
        $entity->setIs_password_removed_updated_at($arg->getIs_password_removed_updated_at());
        $entity->setIs_password_removed_updated_by($arg->getIs_password_removed_updated_by());

        return $entity;
    }

    public static function createUpdateArray(UserCredentialsDTORequest $arg, array $exclude = []): array
    {
        $update_array = [];

        if ($arg->getHas_hashed_password() && !in_array(UserCredentialsConstants::HAS_HASHED_PASSWORD, $exclude)) {
            $update_array[UserCredentialsConstants::HAS_HASHED_PASSWORD] = $arg->getHas_hashed_password();
        }

        if ($arg->getHashed_password() && !in_array(UserCredentialsConstants::HASHED_PASSWORD, $exclude)) {
            $update_array[UserCredentialsConstants::HASHED_PASSWORD] = $arg->getHashed_password();
        }

        if ($arg->getPassword_changed_at() && !in_array(UserCredentialsConstants::PASSWORD_CHANGED_AT, $exclude)) {
            $update_array[UserCredentialsConstants::PASSWORD_CHANGED_AT] = $arg->getPassword_changed_at();
        }

        if ($arg->getIs_password_removed() && !in_array(UserCredentialsConstants::IS_PASSWORD_REMOVED, $exclude)) {
            $update_array[UserCredentialsConstants::IS_PASSWORD_REMOVED] = $arg->getIs_password_removed();
        }

        if ($arg->getIs_password_removed_updated_at() && !in_array(UserCredentialsConstants::IS_PASSWORD_REMOVED_UPDATED_AT, $exclude)) {
            $update_array[UserCredentialsConstants::IS_PASSWORD_REMOVED_UPDATED_AT] = $arg->getIs_password_removed_updated_at();
        }

        if ($arg->getIs_password_removed_updated_by() && !in_array(UserCredentialsConstants::IS_PASSWORD_REMOVED_UPDATED_BY, $exclude)) {
            $update_array[UserCredentialsConstants::IS_PASSWORD_REMOVED_UPDATED_BY] = $arg->getIs_password_removed_updated_by();
        }

        return $update_array;
    }

    public static function toKeyValArray(UserCredentialsEntity $arg): array
    {
        return [
            UserCredentialsConstants::UID => $arg->getUid(),
            UserCredentialsConstants::USER_UID => $arg->getUser_uid(),
            UserCredentialsConstants::HAS_HASHED_PASSWORD => $arg->getHas_hashed_password(),
            UserCredentialsConstants::HASHED_PASSWORD => $arg->getHashed_password(),
            UserCredentialsConstants::IS_PASSWORD_REMOVED => $arg->getIs_password_removed(),
            UserCredentialsConstants::IS_PASSWORD_REMOVED_UPDATED_AT => $arg->getIs_password_removed_updated_at(),
            UserCredentialsConstants::IS_PASSWORD_REMOVED_UPDATED_BY => $arg->getIs_password_removed_updated_by()
        ];
    }

    public static function toKeyValDTOResponseArray(UserCredentialsDTOResponse $arg): array
    {
        return [
            UserCredentialsConstants::UID => $arg->getUid(),
            UserCredentialsConstants::USER_UID => $arg->getUser_uid(),
            UserCredentialsConstants::HAS_HASHED_PASSWORD => $arg->getHas_hashed_password(),
            UserCredentialsConstants::IS_PASSWORD_REMOVED => $arg->getIs_password_removed(),
            UserCredentialsConstants::IS_PASSWORD_REMOVED_UPDATED_AT => $arg->getIs_password_removed_updated_at(),
            UserCredentialsConstants::IS_PASSWORD_REMOVED_UPDATED_BY => $arg->getIs_password_removed_updated_by()
        ];
    }

    private static function mapArrayToDTOResponse(UserCredentialsEntity $entity, UserCredentialsDTOResponse $dto): void
    {
        $entity->setUid($dto->getUid());
        $entity->setUser_uid($dto->getUser_uid());
        $entity->setHas_hashed_password($dto->getHas_hashed_password());
        $entity->setPassword_changed_at($dto->getPassword_changed_at());
        $entity->setIs_password_removed($dto->getIs_password_removed());
        $entity->setIs_password_removed_updated_at($dto->getIs_password_removed_updated_at());
        $entity->setIs_password_removed_updated_by($dto->getIs_password_removed_updated_by());
    }

    public static function createRandomEntity(string $user_uid): UserCredentialsEntity
    {
        $password = Generation::randomString(16);
        $hashed_password = Password::hash($password);

        $arg = new UserCredentialsEntity();
        $arg->setUid(Identifier::newULID());
        $arg->setUser_uid($user_uid);
        $arg->setHas_hashed_password(Generation::randomBool());
        $arg->setHashed_password($hashed_password);
        $arg->setPassword_changed_at(Time::atomicMicroFormat(Time::now()));
        $arg->setIs_password_removed(Generation::randomBool());
        $arg->setIs_password_removed_updated_at(Time::atomicMicroFormat(Time::now()));
        $arg->setIs_password_removed_updated_by(Identifier::newULID());

        return $arg;
    }
}
