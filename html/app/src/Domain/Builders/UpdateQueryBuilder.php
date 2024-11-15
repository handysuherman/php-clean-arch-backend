<?php

namespace app\src\Domain\Builders;

use app\src\Common\Constants\Exceptions\SQLExceptionMessageConstants;
use app\src\Common\Constants\QueryConstants;
use app\src\Common\Exceptions\SQLExceptions\UnprocessableEntity;
use app\src\Domain\Entities\QueryParameterEntity;
use app\src\Domain\Factories\QueryParameterFactory;

class UpdateQueryBuilder
{
    private string $table_name;

    private array $truthy = ["AND", "OR"];
    private array $truthy_operator = ["=", "!=", "LIKE"];

    private array $updated_columns = [];
    private array $query_filters = [];


    private array $sql_params = [];
    private ?string $sql = null;

    public function __construct(string $table_name)
    {
        $this->table_name = $table_name;
    }

    public function addUpdateColumn(string $column, mixed $value, mixed $sql_data_type)
    {
        $arg = new QueryParameterEntity();
        $arg->setColumn($column);
        $arg->setValue($value);
        $arg->setSql_data_type($sql_data_type);

        $this->updated_columns[] = QueryParameterFactory::toKeyValArray($arg);
    }

    public function addQueryFilter(string $column, mixed $value, mixed $sql_data_type, ?string $truthy_operator = "=", ?string $truthy = "AND")
    {
        if (!in_array($truthy, $this->truthy)) {
            $truthy = "AND";
        }

        if (!in_array($truthy_operator, $this->truthy_operator)) {
            $truthy_operator = "=";
        }

        $arg = new QueryParameterEntity();
        $arg->setColumn($column);
        $arg->setValue($value);
        $arg->setSql_data_type($sql_data_type);
        $arg->setTruthy($truthy);
        $arg->setTruthy_operator($truthy_operator);

        $this->query_filters[] = QueryParameterFactory::toKeyValArray($arg);
    }

    public function build()
    {
        if (empty($this->updated_columns)) {
            throw new UnprocessableEntity(SQLExceptionMessageConstants::NO_UPDATED_COLUMNS);
        }

        if (empty($this->query_filters)) {
            throw new UnprocessableEntity(SQLExceptionMessageConstants::NO_PARAMETERS_BUILDER_PROVIDED);
        }

        $this->sql = "UPDATE $this->table_name";

        $set_clauses = [];
        $where_clauses = [];

        foreach ($this->updated_columns as $updated_column) {
            $set_clauses[] = "{$updated_column[QueryConstants::COLUMN]} = ?";
            $this->sql_params[] = [
                QueryConstants::VALUE => $updated_column[QueryConstants::VALUE],
                QueryConstants::SQL_DATA_TYPE => $updated_column[QueryConstants::SQL_DATA_TYPE]
            ];
        }

        foreach ($this->query_filters as $filter) {
            $where_clauses[] = "{$filter[QueryConstants::COLUMN]} {$filter[QueryConstants::TRUTHY_OPERATOR]} ?";
            $this->sql_params[] = [
                QueryConstants::VALUE => $filter[QueryConstants::VALUE],
                QueryConstants::SQL_DATA_TYPE => $filter[QueryConstants::SQL_DATA_TYPE],
            ];
        }


        $this->sql .= " SET " . implode(", ", $set_clauses);

        foreach ($where_clauses as $index => $clause) {
            if ($index == 0) {
                $this->sql .= " WHERE " . $clause;
            } else {
                $this->sql .= " " . $filter[QueryConstants::TRUTHY] . " " . $clause;
            }
        }
    }


    public function getSQL(): ?string
    {
        return $this->sql;
    }

    public function getSQLParams(): array
    {
        return $this->sql_params;
    }
}
