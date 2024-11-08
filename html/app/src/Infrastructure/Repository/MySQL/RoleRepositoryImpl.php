<?php

namespace app\src\Infrastructure\Repository\MySQL;

use app\src\Application\Config\Config;
use app\src\Common\Constants\Exceptions\SQLExceptionMessageConstants;
use app\src\Common\Constants\QueryConstants;
use app\src\Common\Databases\MySQL;
use app\src\Common\Exceptions\SQLExceptions\NoRowsException;
use app\src\Common\Exceptions\SQLExceptions\SQLException;
use app\src\Common\Exceptions\SQLExceptions\UnprocessableEntity;
use app\src\Common\Helpers\DatabaseExceptionHandler;
use app\src\Common\Helpers\Identifier;
use app\src\Common\Loggers\Logger;
use app\src\Domain\Builders\RoleQueryBuilder;
use app\src\Domain\Builders\UpdateQueryBuilder;
use app\src\Domain\Entities\RoleEntity;
use app\src\Domain\Factories\QueryParameterFactory;
use app\src\Domain\Factories\RoleFactory;
use app\src\Domain\Params\RoleQueryParams;
use app\src\Infrastructure\Constants\RoleConstants;
use PDO;

class RoleRepositoryImpl implements RoleRepository
{
    protected PDO $connection;
    protected Config $config;
    protected Logger $log;

    private string $scope = "RoleRepositoryImpl";
    private string $table_name = "role";

    public function __construct(Config $cfg, Logger $log, MySQL $mysql)
    {
        $this->connection = $mysql->getConnection();
        $this->log = $log;
        $this->config = $cfg;
    }

    public function save(RoleEntity $arg): void
    {
        try {
            $context = "$this->scope.save";

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
            $this->handleLog($context, $e->getMessage(), null, []);

            DatabaseExceptionHandler::handle($e, $context);
        }
    }

    public function findByUid(string $cursor): RoleEntity
    {
        try {
            $context = "$this->scope.findByUid";
            $query_builder = new RoleQueryBuilder();
            $query_builder->addQueryFilter(RoleConstants::UID, $cursor, \PDO::PARAM_STR, "=");
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
                $data->setUid(Identifier::encrypt($data->getUid(), $this->config->getKeys()->getApp_identifier_key()));
                var_dump(sprintf("actual key: %s", $this->config->getKeys()->getApp_identifier_key()));
                var_dump($data->getUid());

                return $data;
            }

            $this->handleLog($context, SQLExceptionMessageConstants::NO_ROWS, $query_builder->getSQL(), $query_builder->getSQLParams());

            throw new NoRowsException(sprintf("%s: %s", $context, SQLExceptionMessageConstants::NO_ROWS));
        } catch (SQLException $e) {
            $this->handleLog($context, $e->getMessage(), $query_builder->getSQL(), $query_builder->getSQLParams());

            throw $e;
        } catch (\PDOException $e) {
            $this->handleLog($context, $e->getMessage(), $query_builder->getSQL(), $query_builder->getSQLParams());

            DatabaseExceptionHandler::handle($e, "$context");
        }
    }

    public function update(string $cursor, array $params, ?array $query_params = null): void
    {
        try {
            $context = "$this->scope.update";

            $query_builder = new UpdateQueryBuilder($this->table_name);

            if (count($params) < 1) {
                throw new NoRowsException(sprintf("%s: %s", $context, SQLExceptionMessageConstants::NO_ROWS_AFFECTED));
            }

            if ($query_params) {
                foreach ($query_params as $query_param) {
                    $param = QueryParameterFactory::fromArr($query_param);

                    $query_builder->addQueryFilter($param->getColumn(), $param->getValue(), $param->getSql_data_type(), $param->getTruthy_operator(), $param->getTruthy());
                }
            } else {
                $query_builder->addQueryFilter(RoleConstants::UID, $cursor, \PDO::PARAM_STR, "=");
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
                $this->handleLog($context, SQLExceptionMessageConstants::NO_ROWS_AFFECTED, $query_builder->getSQL(), $query_builder->getSQLParams());

                throw new NoRowsException(sprintf("%s: %s", $context, SQLExceptionMessageConstants::NO_ROWS_AFFECTED));
            }
        } catch (SQLException $e) {
            $this->handleLog($context, $e->getMessage(), $query_builder->getSQL(), $query_builder->getSQLParams());

            throw $e;
        } catch (\PDOException $e) {
            $this->handleLog($context, $e->getMessage(), $query_builder->getSQL(), $query_builder->getSQLParams());

            DatabaseExceptionHandler::handle($e, "$context");
        }
    }

    public function list(RoleQueryParams $params, bool $with_search_text = true): array
    {
        try {
            $context = "$this->scope.list";
            $query_builder = new RoleQueryBuilder();

            if ($with_search_text) {
                $query_builder->withSearchableTextFilters($params->getSearch_text());
            }

            $query_builder->withPagination($params->getPagination());


            if ($params->getQuery_params()) {
                foreach ($params->getQuery_params() as $query_param) {
                    $param = QueryParameterFactory::fromArr($query_param);

                    $query_builder->addQueryFilter($param->getColumn(), $param->getValue(), $param->getSql_data_type(), $param->getTruthy_operator(), $param->getTruthy());
                }
            }

            if ($params->getWith_sort()) {
                $query_builder->withSort($params->getSort_property(), $params->getSort_order());
            }

            if ($params->getWith_range()) {
                $query_builder->withRangeBetween($params->getRange_property(), $params->getFrom(), $params->getTo());
            }

            $query_builder->build();

            $sql_params = $query_builder->getSQLParams();


            $statement = $this->connection->prepare($query_builder->getSQL());
            foreach ($sql_params as $index => $param) {
                $param = QueryParameterFactory::fromArr($param);

                $statement->bindValue($index + 1, $param->getValue(), $param->getSql_data_type());
            }

            $statement->execute();

            $result = [];

            foreach ($statement as $row) {
                $data = RoleFactory::fromRow($row);

                $result[] = RoleFactory::toKeyValArray($data);
            }

            return $result;
        } catch (SQLException $e) {
            $this->handleLog($context, $e->getMessage(), $query_builder->getSQL(), $query_builder->getSQLParams());

            throw $e;
        } catch (\PDOException $e) {
            $this->handleLog($context, $e->getMessage(), $query_builder->getSQL(), $query_builder->getSQLParams());

            DatabaseExceptionHandler::handle($e, "$context");
        }
    }

    public function count(RoleQueryParams $params, bool $with_search_text = true): int
    {
        try {
            $context = "$this->scope.count";
            $query_builder = new RoleQueryBuilder();
            $query_builder->isCount();

            if ($with_search_text) {
                $query_builder->withSearchableTextFilters($params->getSearch_text());
            }

            if ($params->getQuery_params()) {
                foreach ($params->getQuery_params() as $query_param) {
                    $param = QueryParameterFactory::fromArr($query_param);

                    $query_builder->addQueryFilter($param->getColumn(), $param->getValue(), $param->getSql_data_type(), $param->getTruthy_operator(), $param->getTruthy());
                }
            }

            if ($params->getWith_range()) {
                $query_builder->withRangeBetween($params->getRange_property(), $params->getFrom(), $params->getTo());
            }

            $query_builder->build();

            $sql_params = $query_builder->getSQLParams();

            $statement = $this->connection->prepare($query_builder->getSQL());
            foreach ($sql_params as $index => $param) {
                $param = QueryParameterFactory::fromArr($param);

                $statement->bindValue($index + 1, $param->getValue(), $param->getSql_data_type());
            }

            $statement->execute();

            $count = $statement->fetchColumn();

            return $count !== false ? (int)$count : 0;
        } catch (SQLException $e) {
            $this->handleLog($context, $e->getMessage(), $query_builder->getSQL(), $query_builder->getSQLParams());

            throw $e;
        } catch (\PDOException $e) {
            $this->handleLog($context, $e->getMessage(), $query_builder->getSQL(), $query_builder->getSQLParams());

            DatabaseExceptionHandler::handle($e, "$context");
        }
    }

    private function handleLog(string $context, string $error_message, ?string $sql, array $sql_params)
    {
        $this->log->warning("$context", $error_message);

        if ($sql) {
            $this->log->warning("$context", sprintf("query: %s", $sql));
        }

        if (!empty($sql_params)) {
            $this->log->warning("$context", sprintf("query-params: %s", $sql_params));
        }
    }
}
