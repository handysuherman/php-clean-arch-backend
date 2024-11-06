<?php

namespace app\src\Domain\Params;

use app\src\Common\Helpers\Pagination;

class RoleQueryParams
{
    private Pagination $pagination;
    private string $wildcard = "%";
    private string $search_text = "";
    private ?array $query_params = null;
    private bool $with_sort = true;
    private string $sort_property = "created_at";
    private string $sort_order = "DESC";
    private bool $with_range = false;
    private ?string $from = null;
    private ?string $to = null;
    private string $range_property = "created_at";

    public function getPagination(): Pagination
    {
        return $this->pagination;
    }

    public function setPagination(Pagination $value)
    {
        $this->pagination = $value;
    }

    public function getSearch_text(): string
    {
        return $this->wildcard . $this->search_text . $this->wildcard;
    }

    public function setSearch_text(string $value)
    {
        $this->search_text = $value;
    }

    public function getQuery_params(): ?array
    {
        return $this->query_params;
    }

    public function setQuery_params(?array $value)
    {
        $this->query_params = $value;
    }

    public function getWith_sort(): bool
    {
        return $this->with_sort;
    }

    public function setWith_sort(bool $value)
    {
        $this->with_sort = $value;
    }

    public function getSort_property(): string
    {
        return $this->sort_property;
    }

    public function setSort_property(string $value)
    {
        $this->sort_property = $value;
    }

    public function getSort_order(): string
    {
        return $this->sort_order;
    }

    public function setSort_order(string $value)
    {
        $this->sort_order = $value;
    }

    public function getWith_range(): bool
    {
        return $this->with_range;
    }

    public function setWith_range(bool $value)
    {
        $this->with_range = $value;
    }

    public function getFrom(): ?string
    {
        return $this->from;
    }

    public function setFrom(?string $value)
    {
        $this->from = $value;
    }

    public function getTo(): ?string
    {
        return $this->to;
    }

    public function setTo(?string $value)
    {
        $this->to = $value;
    }

    public function getRange_property(): string
    {
        return $this->range_property;
    }

    public function setRange_property(string $value)
    {
        $this->range_property = $value;
    }

    public function getWildcard(): string
    {
        return $this->wildcard;
    }

    public function setWildcard(string $value)
    {
        $this->wildcard = $value;
    }
}
