<?php

namespace app\src\Infrastructure\Repository\MySQL;

use app\src\Common\Constants\QueryConstants;
use app\src\Common\Exceptions\SQLExceptions\NoRowsException;
use app\src\Common\Exceptions\SQLExceptions\UnprocessableEntity;
use app\src\Common\Helpers\DatabaseExceptionHandler;
use app\src\Domain\Builders\RoleQueryBuilder;
use app\src\Domain\Builders\UpdateQueryBuilder;
use app\src\Domain\Entities\RoleEntity;
use app\src\Domain\Factories\QueryParameterFactory;
use app\src\Domain\Factories\RoleFactory;
use OpenApi\Annotations\QueryParameter;
use PDO;

class RoleRepositoryImpl implements RoleRepository
{
    private PDO $connection;

    private string $table_name = "role";

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function save(RoleEntity $arg): void
    {
        try {
            $sql = "INSERT INTO $this->table_name (
                uid,
                role_name,
                description,
                role_name_slug,
                created_at,
                created_by
            ) VALUES (
                ?, ?, ?, ?, ?, ?
            )";

            $statement = $this->connection->prepare($sql);

            $statement->execute([
                $arg->getUid(),
                $arg->getRole_name(),
                $arg->getDescription(),
                $arg->getRole_name_slug(),
                $arg->getCreated_at(),
                $arg->getCreated_by(),
            ]);

            return;
        } catch (\PDOException $e) {
            DatabaseExceptionHandler::handle($e, "RoleRepository.save");
        }
    }

    public function find(string $cursor, ?array $query_params = null): RoleEntity
    {
        try {
            $query_builder = new RoleQueryBuilder();

            if ($query_params) {
                foreach ($query_params as $query_param) {
                    $param = QueryParameterFactory::fromArr($query_param);

                    $query_builder->addQueryFilter($param->getColumn(), $param->getValue(), $param->getSql_data_type(), $param->getTruthy_operator(), $param->getTruthy());
                }
            } else {
                $query_builder->addQueryFilter("uid", $cursor, \PDO::PARAM_STR, "=");
            }

            $query_builder->build();

            $sql_params = $query_builder->getSQLParams();

            $statement = $this->connection->prepare($query_builder->getSQL());
            foreach ($sql_params as $index => $param) {
                $param = QueryParameterFactory::fromArr($param);

                $statement->bindValue($index + 1, $param->getValue(), $param->getSql_data_type());
            }

            $statement->execute();

            if ($row = $statement->fetch()) {
                $data = RoleFactory::fromRow($row);

                return $data;
            }

            throw new NoRowsException("RoleRepository.find.no_rows_exception");
        } catch (\PDOException $e) {
            DatabaseExceptionHandler::handle($e, "RoleRepository.find");
        }
    }

    public function update(string $cursor, array $params, ?array $query_params = null): void
    {
        try {
            $query_builder = new UpdateQueryBuilder($this->table_name);

            if ($query_params) {
                foreach ($query_params as $query_param) {
                    $param = QueryParameterFactory::fromArr($query_param);

                    $query_builder->addQueryFilter($param->getColumn(), $param->getValue(), $param->getSql_data_type(), $param->getTruthy_operator(), $param->getTruthy());
                }
            } else {
                $query_builder->addQueryFilter("uid", $cursor, \PDO::PARAM_STR, "=");
            }

            if (count($params) < 1) {
                throw new UnprocessableEntity("no updated columns speficied");
            }

            foreach ($params as $updated_param) {
                $param = QueryParameterFactory::fromArr($updated_param);

                $query_builder->addUpdateColumn($param->getColumn(), $param->getValue(), $param->getSql_data_type());
            }

            $query_builder->build();

            $sql_params = $query_builder->getSQLParams();;

            $statement = $this->connection->prepare($query_builder->getSQL());
            foreach ($sql_params as $index => $param) {
                $statement->bindValue($index + 1, $param[QueryConstants::VALUE], $param[QueryConstants::SQL_DATA_TYPE]);
            }

            $statement->execute();

            if ($statement->rowCount() === 0) {
                throw new NoRowsException("RoleRepository.update.no_rows_updated");
            }
        } catch (\PDOException $e) {
            DatabaseExceptionHandler::handle($e, "RoleRepository.update");
        }
    }
}
