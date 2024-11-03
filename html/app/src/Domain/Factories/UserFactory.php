<?php

namespace app\src\Domain\Factories;

use app\src\Common\DTOs\Request\UserDTORequest;
use app\src\Common\DTOs\Response\UserDTOResponse;
use app\src\Common\Helpers\Generation;
use app\src\Common\Helpers\Identifier;
use app\src\Common\Helpers\Text;
use app\src\Common\Helpers\Time;
use app\src\Domain\Entities\UserEntity;
use app\src\Infrastructure\Constants\UserConstants;

class UserFactory
{
    public static function fromRow(array $row): UserEntity
    {
        $entity = new UserEntity();
        self::mapArrayToEntity($row, $entity);
        return $entity;
    }

    private static function mapArrayToEntity(array $row, UserEntity $entity): void
    {
        $entity->setUid($row[UserConstants::UID]);
        $entity->setFull_name($row[UserConstants::FULL_NAME]);
        $entity->setFull_name_slug($row[UserConstants::FULL_NAME_SLUG]);
        $entity->setEmail($row[UserConstants::EMAIL]);
        $entity->setIs_email_verified($row[UserConstants::IS_EMAIL_VERIFIED]);
        $entity->setProfile_pict_url($row[UserConstants::PROFILE_PICT_URL]);
        $entity->setDate_of_birth($row[UserConstants::DATE_OF_BIRTH]);
        $entity->setCreated_at($row[UserConstants::CREATED_AT]);
        $entity->setUpdated_at($row[UserConstants::UPDATED_AT]);
        $entity->setUpdated_by($row[UserConstants::UPDATED_BY]);
        $entity->setIs_blocked($row[UserConstants::IS_BLOCKED]);
        $entity->setIs_blocked_updated_at($row[UserConstants::IS_BLOCKED_UPDATED_AT]);
        $entity->setIs_blocked_updated_by($row[UserConstants::IS_BLOCKED_UPDATED_BY]);
    }


    public static function toDTOResponse(UserEntity $entity): UserDTOResponse
    {
        $dto = new UserDTOResponse();
        self::mapArrayToDTOResponse($entity, $dto);
        return $dto;
    }


    public static function dtoRequestToEntity(UserDTORequest $arg): UserEntity
    {
        $entity = new UserEntity();
        $entity->setUid($arg->getUid());
        $entity->setFull_name($arg->getFull_name());
        $entity->setFull_name_slug($arg->getFull_name_slug());
        $entity->setEmail($arg->getEmail());
        $entity->setIs_email_verified($arg->getIs_email_verified());
        $entity->setProfile_pict_url($arg->getProfile_pict_url());
        $entity->setDate_of_birth($arg->getDate_of_birth());
        $entity->setCreated_at($arg->getCreated_at());
        $entity->setUpdated_at($arg->getUpdated_at());
        $entity->setUpdated_by($arg->getUpdated_by());
        $entity->setIs_blocked($arg->getIs_blocked());
        $entity->setIs_blocked_updated_at($arg->getIs_blocked_updated_at());
        $entity->setIs_blocked_updated_by($arg->getIs_blocked_updated_by());

        return $entity;
    }

    public static function createUpdateArray(UserDTORequest $arg, array $exclude = []): array
    {
        $update_array = [];

        if ($arg->getFull_name() && !in_array(UserConstants::FULL_NAME, $exclude)) {
            $update_array[UserConstants::FULL_NAME] = $arg->getFull_name();
        }

        if ($arg->getFull_name_slug() && !in_array(UserConstants::FULL_NAME_SLUG, $exclude)) {
            $update_array[UserConstants::FULL_NAME_SLUG] = $arg->getFull_name_slug();
        }

        if ($arg->getEmail() && !in_array(UserConstants::EMAIL, $exclude)) {
            $update_array[UserConstants::EMAIL] = $arg->getEmail();
        }

        if ($arg->getProfile_pict_url() && !in_array(UserConstants::PROFILE_PICT_URL, $exclude)) {
            $update_array[UserConstants::PROFILE_PICT_URL] = $arg->getProfile_pict_url();
        }

        if ($arg->getIs_email_verified() && !in_array(UserConstants::IS_EMAIL_VERIFIED, $exclude)) {
            $update_array[UserConstants::IS_EMAIL_VERIFIED] = $arg->getIs_email_verified();
        }

        if ($arg->getDate_of_birth() && !in_array(UserConstants::DATE_OF_BIRTH, $exclude)) {
            $update_array[UserConstants::DATE_OF_BIRTH] = $arg->getDate_of_birth();
        }

        if ($arg->getUpdated_at() && !in_array(UserConstants::UPDATED_AT, $exclude)) {
            $update_array[UserConstants::UPDATED_AT] = $arg->getUpdated_at();
        }

        if ($arg->getUpdated_by() && !in_array(UserConstants::UPDATED_BY, $exclude)) {
            $update_array[UserConstants::UPDATED_BY] = $arg->getUpdated_by();
        }

        if ($arg->getIs_blocked() && !in_array(UserConstants::IS_BLOCKED, $exclude)) {
            $update_array[UserConstants::IS_BLOCKED] = $arg->getIs_blocked();
        }

        if ($arg->getIs_blocked_updated_at() && !in_array(UserConstants::IS_BLOCKED_UPDATED_AT, $exclude)) {
            $update_array[UserConstants::IS_BLOCKED_UPDATED_AT] = $arg->getIs_blocked_updated_at();
        }

        if ($arg->getIs_blocked_updated_by() && !in_array(UserConstants::IS_BLOCKED_UPDATED_BY, $exclude)) {
            $update_array[UserConstants::IS_BLOCKED_UPDATED_BY] = $arg->getIs_blocked_updated_by();
        }

        return $update_array;
    }

    public static function toKeyValArray(UserEntity | UserDTOResponse $arg): array
    {
        return [
            UserConstants::UID => $arg->getUid(),
            UserConstants::FULL_NAME => $arg->getFull_name(),
            UserConstants::FULL_NAME_SLUG => $arg->getFull_name_slug(),
            UserConstants::EMAIL => $arg->getEmail(),
            UserConstants::PROFILE_PICT_URL => $arg->getProfile_pict_url(),
            UserConstants::IS_EMAIL_VERIFIED => $arg->getIs_email_verified(),
            UserConstants::DATE_OF_BIRTH => $arg->getDate_of_birth(),
            UserConstants::CREATED_AT => $arg->getCreated_at(),
            UserConstants::UPDATED_AT => $arg->getUpdated_at(),
            UserConstants::UPDATED_BY => $arg->getUpdated_by(),
            UserConstants::IS_BLOCKED => $arg->getIs_blocked(),
            UserConstants::IS_BLOCKED_UPDATED_AT => $arg->getIs_blocked_updated_at(),
            UserConstants::IS_BLOCKED_UPDATED_BY => $arg->getIs_blocked_updated_by(),
        ];
    }

    private static function mapArrayToDTOResponse(UserEntity $entity, UserDTOResponse $dto): void
    {
        $entity->setUid($dto->getUid());
        $entity->setFull_name($dto->getFull_name());
        $entity->setFull_name_slug($dto->getFull_name_slug());
        $entity->setEmail($dto->getEmail());
        $entity->setIs_email_verified($dto->getIs_email_verified());
        $entity->setProfile_pict_url($dto->getProfile_pict_url());
        $entity->setDate_of_birth($dto->getDate_of_birth());
        $entity->setCreated_at($dto->getCreated_at());
        $entity->setUpdated_at($dto->getUpdated_at());
        $entity->setUpdated_by($dto->getUpdated_by());
        $entity->setIs_blocked($dto->getIs_blocked());
        $entity->setIs_blocked_updated_at($dto->getIs_blocked_updated_at());
        $entity->setIs_blocked_updated_by($dto->getIs_blocked_updated_by());
    }

    public static function createRandomEntity(): UserEntity
    {
        $full_name = Generation::randomString(32);

        $arg = new UserEntity();
        $arg->setUid(Identifier::newULID());
        $arg->setFull_name($full_name);
        $arg->setFull_name_slug(Text::toSlugify($full_name));
        $arg->setEmail(Generation::randomEmail());
        $arg->setIs_email_verified(Generation::randomBool());
        $arg->setProfile_pict_url(Generation::randomUrl());
        $arg->setDate_of_birth(sprintf("%s-%s-%s", Generation::randomStringInt(4), Generation::randomString(2), Generation::randomString(2)));
        $arg->setCreated_at(Time::atomicMicroFormat(Time::now()));
        $arg->setUpdated_at(Time::atomicMicroFormat(Time::now()));
        $arg->setUpdated_by(Identifier::newULID());
        $arg->setIs_blocked(Generation::randomBool());
        $arg->setIs_blocked_updated_at(Time::atomicMicroFormat(Time::now()));
        $arg->setIs_blocked_updated_by(Time::atomicMicroFormat(Time::now()));

        return $arg;
    }
}
