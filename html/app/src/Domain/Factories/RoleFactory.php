<?php

namespace app\src\Domain\Factories;

use app\src\Common\DTOs\Request\Role\UpdateRoleDTORequest;
use app\src\Common\DTOs\Response\RoleDTOResponse;
use app\src\Common\Helpers\Generation;
use app\src\Common\Helpers\Identifier;
use app\src\Common\Helpers\Text;
use app\src\Common\Helpers\Time;
use app\src\Domain\Entities\QueryParameterEntity;
use app\src\Domain\Entities\RoleEntity;
use app\src\Infrastructure\Constants\RoleConstants;

class RoleFactory
{
    public static function fromRow(array $row): RoleEntity
    {
        $entity = new RoleEntity();
        self::mapArrayToEntity($row, $entity);
        return $entity;
    }

    private static function mapArrayToEntity(array $row, RoleEntity $entity): void
    {
        $entity->setUid($row[RoleConstants::UID]);
        $entity->setRole_name($row[RoleConstants::ROLE_NAME]);
        $entity->setDescription($row[RoleConstants::DESCRIPTION]);
        $entity->setRole_name_slug($row[RoleConstants::ROLE_NAME_SLUG]);
        $entity->setCreated_at($row[RoleConstants::CREATED_AT]);
        $entity->setCreated_by($row[RoleConstants::CREATED_BY]);
        $entity->setIs_activated($row[RoleConstants::IS_ACTIVATED]);
        $entity->setIs_activated_updated_at($row[RoleConstants::IS_ACTIVATED_UPDATED_AT]);
        $entity->setIs_activated_updated_by($row[RoleConstants::IS_ACTIVATED_UPDATED_BY]);
    }


    public static function toDTOResponse(RoleEntity $entity): RoleDTOResponse
    {
        $dto = new RoleDTOResponse();
        self::mapArrayToDTOResponse($entity, $dto);
        return $dto;
    }


    public static function dtoRequestToEntity(UpdateRoleDTORequest $arg): RoleEntity
    {
        $entity = new RoleEntity();
        $entity->setUid($arg->getUid());
        $entity->setRole_name($arg->getRole_name());
        $entity->setDescription($arg->getDescription());
        $entity->setRole_name_slug($arg->getRole_name_slug());
        $entity->setCreated_at($arg->getCreated_at());
        $entity->setCreated_by($arg->getCreated_by());
        $entity->setIs_activated($arg->getIs_activated());
        $entity->setIs_activated_updated_at($arg->getIs_activated_updated_at());
        $entity->setIs_activated_updated_by($arg->getIs_activated_updated_by());

        return $entity;
    }

    public static function createUpdateArray(UpdateRoleDTORequest $arg, array $exclude = []): array
    {
        $update_array = [];

        if ($arg->getRole_name() && !in_array(RoleConstants::ROLE_NAME, $exclude)) {
            $arr = new QueryParameterEntity();
            $arr->setColumn(RoleConstants::ROLE_NAME);
            $arr->setValue($arg->getRole_name());
            $arr->setSql_data_type(\PDO::PARAM_STR);

            $update_array[RoleConstants::ROLE_NAME] = QueryParameterFactory::toKeyValArray($arr);
        }

        if ($arg->getDescription() && !in_array(RoleConstants::DESCRIPTION, $exclude)) {
            $arr = new QueryParameterEntity();
            $arr->setColumn(RoleConstants::DESCRIPTION);
            $arr->setValue($arg->getDescription());
            $arr->setSql_data_type(\PDO::PARAM_STR);

            $update_array[RoleConstants::DESCRIPTION] = QueryParameterFactory::toKeyValArray($arr);
        }

        if ($arg->getRole_name_slug() && !in_array(RoleConstants::ROLE_NAME_SLUG, $exclude)) {
            $arr = new QueryParameterEntity();
            $arr->setColumn(RoleConstants::ROLE_NAME_SLUG);
            $arr->setValue($arg->getRole_name_slug());
            $arr->setSql_data_type(\PDO::PARAM_STR);

            $update_array[RoleConstants::ROLE_NAME_SLUG] = QueryParameterFactory::toKeyValArray($arr);
        }

        if ($arg->getIs_activated() && !in_array(RoleConstants::IS_ACTIVATED, $exclude)) {
            $arr = new QueryParameterEntity();
            $arr->setColumn(RoleConstants::IS_ACTIVATED);
            $arr->setValue($arg->getIs_activated());
            $arr->setSql_data_type(\PDO::PARAM_BOOL);

            $update_array[RoleConstants::IS_ACTIVATED] = QueryParameterFactory::toKeyValArray($arr);
        }

        if ($arg->getIs_activated_updated_at() && !in_array(RoleConstants::IS_ACTIVATED_UPDATED_AT, $exclude)) {
            $arr = new QueryParameterEntity();
            $arr->setColumn(RoleConstants::IS_ACTIVATED_UPDATED_AT);
            $arr->setValue($arg->getIs_activated_updated_at());
            $arr->setSql_data_type(\PDO::PARAM_STR);

            $update_array[RoleConstants::IS_ACTIVATED_UPDATED_AT] = QueryParameterFactory::toKeyValArray($arr);
        }

        if ($arg->getIs_activated_updated_by() && !in_array(RoleConstants::IS_ACTIVATED_UPDATED_BY, $exclude)) {
            $arr = new QueryParameterEntity();
            $arr->setColumn(RoleConstants::IS_ACTIVATED_UPDATED_BY);
            $arr->setValue($arg->getIs_activated_updated_by());
            $arr->setSql_data_type(\PDO::PARAM_STR);

            $update_array[RoleConstants::IS_ACTIVATED_UPDATED_BY] = QueryParameterFactory::toKeyValArray($arr);
        }


        return $update_array;
    }

    public static function toKeyValArray(RoleEntity | RoleDTOResponse $arg): array
    {
        return [
            RoleConstants::UID => $arg->getUid(),
            RoleConstants::ROLE_NAME => $arg->getRole_name(),
            RoleConstants::DESCRIPTION => $arg->getDescription(),
            RoleConstants::ROLE_NAME_SLUG => $arg->getRole_name_slug(),
            RoleConstants::CREATED_AT => $arg->getCreated_at(),
            RoleConstants::CREATED_BY => $arg->getCreated_by(),
            RoleConstants::IS_ACTIVATED => $arg->getIs_activated(),
            RoleConstants::IS_ACTIVATED_UPDATED_AT => $arg->getIs_activated_updated_at(),
            RoleConstants::IS_ACTIVATED_UPDATED_BY => $arg->getIs_activated_updated_by(),
        ];
    }

    private static function mapArrayToDTOResponse(RoleEntity $entity, RoleDTOResponse $dto): void
    {
        $dto->setUid($entity->getUid());
        $dto->setRole_name($entity->getRole_name());
        $dto->setDescription($entity->getDescription());
        $dto->setRole_name_slug($entity->getRole_name_slug());
        $dto->setCreated_at($entity->getCreated_at());
        $dto->setCreated_by($entity->getCreated_by());
        $dto->setIs_activated($entity->getIs_activated());
        $dto->setIs_activated_updated_at($entity->getIs_activated_updated_at());
        $dto->setIs_activated_updated_by($entity->getIs_activated_updated_by());
    }

    public static function createRandomEntity(): RoleEntity
    {
        $role_name = Generation::randomString(32);
        $arg = new RoleEntity();
        $arg->setUid(Identifier::newULID());
        $arg->setRole_name($role_name);
        $arg->setRole_name_slug(Text::toSlugify($role_name, true));
        $arg->setDescription(Generation::randomString(300));
        $arg->setCreated_at(Time::atomicMicroFormat(Time::now()));
        $arg->setCreated_by(Identifier::newULID());
        $arg->setIs_activated(Generation::randomBool());
        $arg->setIs_activated_updated_at(Time::atomicMicroFormat(Time::now()));
        $arg->setIs_activated_updated_by(Identifier::newULID());

        return $arg;
    }
}
