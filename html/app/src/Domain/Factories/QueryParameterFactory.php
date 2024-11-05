<?php

namespace app\src\Domain\Factories;

use app\src\Common\Constants\QueryConstants;
use app\src\Domain\Entities\QueryParameterEntity;

class QueryParameterFactory
{
    public static function fromArr(array $arr): QueryParameterEntity
    {
        $entity = new QueryParameterEntity();
        self::mapArrayToEntity($arr, $entity);
        return $entity;
    }

    private static function mapArrayToEntity(array $arr, QueryParameterEntity $entity): void
    {
        $entity->setColumn(isset($arr[QueryConstants::COLUMN]) ? $arr[QueryConstants::COLUMN] : null);
        $entity->setValue($arr[QueryConstants::VALUE]);
        $entity->setSql_data_type($arr[QueryConstants::SQL_DATA_TYPE]);
        $entity->setTruthy(isset($arr[QueryConstants::TRUTHY]) ? $arr[QueryConstants::TRUTHY] : null);
        $entity->setTruthy_operator(isset($arr[QueryConstants::TRUTHY_OPERATOR]) ? $arr[QueryConstants::TRUTHY_OPERATOR] : null);
    }


    public static function toKeyValArray(QueryParameterEntity $arg): array
    {
        return [
            QueryConstants::COLUMN => $arg->getColumn(),
            QueryConstants::VALUE => $arg->getValue(),
            QueryConstants::SQL_DATA_TYPE => $arg->getSql_data_type(),
            QueryConstants::TRUTHY => $arg->getTruthy(),
            QueryConstants::TRUTHY_OPERATOR => $arg->getTruthy_operator(),
        ];
    }
}
