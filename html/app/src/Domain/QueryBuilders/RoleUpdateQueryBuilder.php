<?php

namespace app\Src\Domain\QueryBuilders;

use app\src\Common\Constants\QueryConstants;
use app\src\Common\Exceptions\SQLExceptions\UnprocessableEntity;

class RoleUpdateQueryBuilder
{
    private string $table_name = "roles";

    private array $truthy = ["AND", "OR"];
    private array $truthy_operator = ["=", "!=", "LIKE"];

    private array $updated_columns = [];
    private array $query_filters = [];


    private array $sql_params = [];
    private ?string $sql = null;

    public function __construct() {}

    public function addUpdateColumn(string $column, mixed $value, mixed $sql_data_type)
    {
        $this->updated_columns[] = [
            QueryConstants::COLUMN => $column,
            QueryConstants::VALUE => $value,
            QueryConstants::SQL_DATA_TYPE => $sql_data_type,
        ];
    }

    public function addQueryFilter(string $column, string $truthy_operator = "=", mixed $value, mixed $sql_data_type, string $truthy = "AND")
    {
        if (!in_array($truthy, $this->truthy)) {
            $truthy = "AND";
        }

        if (!in_array($truthy_operator, $this->truthy_operator)) {
            $truthy_operator = "=";
        }

        $this->query_filters[] = [
            QueryConstants::COLUMN => $column,
            QueryConstants::TRUTHY => $truthy,
            QueryConstants::TRUTHY_OPERATOR => $truthy_operator,
            QueryConstants::VALUE => $value,
            QueryConstants::SQL_DATA_TYPE => $sql_data_type,
        ];
    }

    public function build()
    {
        if (empty($this->updated_columns)) {
            throw new UnprocessableEntity("no updated columns speficied for the operation");
        }

        if (empty($this->query_filters)) {
            throw new UnprocessableEntity("no provided parameters for the operation");
        }

        $this->sql = "UPDATE $this->table_name";

        $set_clauses = [];
        $where_clauses = [];

        foreach ($this->query_filters as $filter) {
            $where_clauses[] = "{$filter[QueryConstants::COLUMN]} {$filter[QueryConstants::TRUTHY_OPERATOR]} ?";
            $this->sql_params[] = $filter[QueryConstants::VALUE];
        }

        foreach ($this->updated_columns as $updated_column) {
            $set_clauses[] = "{$updated_column[QueryConstants::COLUMN]} = ?";
            $this->sql_params[] = $updated_column[QueryConstants::VALUE];
        }

        $this->sql .= " SET " . implode(", ", $set_clauses);

        $this->sql .= " WHERE " . implode(" " . $filter[QueryConstants::TRUTHY] . " ", $where_clauses);
    }


    public function getSQL(): string
    {
        return $this->sql;
    }

    public function getSQLParams(): array
    {
        return $this->sql_params;
    }
}
