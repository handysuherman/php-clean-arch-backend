<?php

namespace app\src\Domain\Builders;

use app\src\Common\Constants\QueryConstants;
use app\src\Common\Enums\DurationType;
use app\src\Common\Helpers\Pagination;
use app\src\Common\Helpers\Time;
use app\src\Domain\Entities\QueryParameterEntity;
use app\src\Domain\Factories\QueryParameterFactory;
use app\src\Infrastructure\Constants\RoleConstants;

class RoleQueryBuilder
{
    private string $table_name = "role";

    private ?Pagination $pagination = null;

    private bool $is_count_query = false;
    private array $truthy = ["AND", "OR"];
    private array $truthy_operator = ["=", "!=", "LIKE"];

    private array $query_filters = [];

    // sort
    private bool $with_sort = true;
    private array $sortable_columns = [RoleConstants::CREATED_AT, RoleConstants::ROLE_NAME];
    private string $sort_by = RoleConstants::CREATED_AT;
    private array $sort_orders = ["ASC", "DESC"];
    private string $sort_order = "DESC";

    // between;
    private bool $with_range_between = false;
    private array $range_betweenable_columns = [RoleConstants::CREATED_AT, RoleConstants::IS_ACTIVATED_UPDATED_AT];
    private string $range_between_column = RoleConstants::CREATED_AT;
    private ?string $from = null;
    private ?string $to = null;

    private array $sql_params = [];
    private ?string $sql = null;

    public function __construct() {}

    public function isCount()
    {
        $this->is_count_query = true;
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

    public function withRangeBetween(string $range_between_column = RoleConstants::CREATED_AT, ?string $from = null, ?string $to = null)
    {
        $this->with_range_between = true;

        $this->range_between_column = $range_between_column;

        if (!in_array($range_between_column, $this->range_betweenable_columns)) {
            $this->range_between_column = RoleConstants::CREATED_AT;
        }

        $this->from = $from;

        if ($this->from == null) {
            $this->from = Time::atomicMicroFormat(Time::now());
        }

        $this->to = $to;

        if ($this->to == null) {
            $this->to = Time::atomicMicroFormat(Time::addDuration(30, DurationType::DAY));
        }
    }

    public function withSort(string $sort_by = RoleConstants::CREATED_AT, string $sort_order = "DESC")
    {
        $this->with_sort = true;

        $this->sort_by = $sort_by;
        if (!in_array($sort_by, $this->sortable_columns)) {
            $this->sort_by = RoleConstants::CREATED_AT;
        }

        $this->sort_order = $sort_order;
        if (!in_array($sort_order, $this->sort_orders)) {
            $this->sort_order = "DESC";
        }
    }

    public function withSearchableTextFilters(string $search_text)
    {
        $search_role_name = new QueryParameterEntity();
        $search_role_name->setColumn(RoleConstants::ROLE_NAME);
        $search_role_name->setValue($search_text);
        $search_role_name->setSql_data_type(\PDO::PARAM_STR);
        $search_role_name->setTruthy("OR");
        $search_role_name->setTruthy_operator("LIKE");

        $search_role_name_2 = new QueryParameterEntity();
        $search_role_name_2->setColumn(RoleConstants::ROLE_NAME);
        $search_role_name_2->setValue($search_text);
        $search_role_name_2->setSql_data_type(\PDO::PARAM_STR);
        $search_role_name_2->setTruthy("AND");
        $search_role_name_2->setTruthy_operator("LIKE");

        $this->query_filters[] = QueryParameterFactory::toKeyValArray($search_role_name);
        $this->query_filters[] = QueryParameterFactory::toKeyValArray($search_role_name_2);
    }

    public function withPagination(Pagination $pagination)
    {
        $this->pagination = $pagination;
    }

    public function build()
    {
        $this->sql = "SELECT * FROM $this->table_name";

        if ($this->is_count_query) {
            $this->sql = "SELECT COUNT(*) FROM $this->table_name";
        }

        if (!empty($this->query_filters)) {
            $where_clauses = [];
            foreach ($this->query_filters as $filter) {
                $where_clauses[] = "{$filter[QueryConstants::COLUMN]} {$filter[QueryConstants::TRUTHY_OPERATOR]} ?";
                $this->sql_params[] = [
                    QueryConstants::VALUE => $filter[QueryConstants::VALUE],
                    QueryConstants::SQL_DATA_TYPE => $filter[QueryConstants::SQL_DATA_TYPE],
                ];
            }

            foreach ($where_clauses as $index => $clause) {
                if ($index == 0) {
                    $this->sql .= " WHERE " . $clause;
                } else {
                    $this->sql .= " " . $filter[QueryConstants::TRUTHY] . " " . $clause;
                }
            }
        };

        if (!$this->is_count_query) {
            if ($this->pagination && $this->with_range_between) {
                $this->sql .= " AND {$this->range_between_column} BETWEEN ? AND ?";
                $this->sql_params[] = [
                    QueryConstants::VALUE => $this->from,
                    QueryConstants::SQL_DATA_TYPE => \PDO::PARAM_STR,
                ];
                $this->sql_params[] = [
                    QueryConstants::VALUE => $this->to,
                    QueryConstants::SQL_DATA_TYPE => \PDO::PARAM_STR,
                ];
            }

            if ($this->pagination && $this->with_sort) {
                $this->sql .= " ORDER BY {$this->sort_by} {$this->sort_order}";
            }

            if ($this->pagination) {
                $this->sql .= " LIMIT ? OFFSET ?";
                $this->sql_params[] = [
                    QueryConstants::VALUE => $this->pagination->getLimit(),
                    QueryConstants::SQL_DATA_TYPE => \PDO::PARAM_INT,
                ];
                $this->sql_params[] = [
                    QueryConstants::VALUE => $this->pagination->getOffset(),
                    QueryConstants::SQL_DATA_TYPE => \PDO::PARAM_INT,
                ];
            } else {
                $this->sql .= " LIMIT 1";
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
