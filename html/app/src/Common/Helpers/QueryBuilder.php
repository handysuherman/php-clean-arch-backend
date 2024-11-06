<?php

namespace app\src\Common\Helpers;

class QueryBuilder
{
    public static function buildUpdateQuery(string $table_name, array $updates, string $where_clause, array $params = []): string
    {
        $setParts = [];
        foreach ($updates as $column => $value) {
            if ($value !== null) {
                $setParts[] = "$column = ?";
                $params[] = $value;
            }
        }
        $setClause = implode(", ", $setParts);
        return "UPDATE $table_name SET $setClause WHERE $where_clause";
    }

    public static function buildSearchClause(array $query, array $searchable_column): string
    {
        $parts = [];
        foreach ($query as $column => $value) {
            if ($value !== null && in_array($column, $searchable_column)) {
                $parts[] = "$column $value";
            }
        }
        $clause = implode(" ", $parts);

        return $clause;
    }

    public static function likeClause(string $query, string $wildcard = '%'): string
    {
        return $wildcard . $query . $wildcard;
    }
}
