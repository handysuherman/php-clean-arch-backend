<?php

namespace app\src\Domain\Params;

use app\src\Common\Helpers\Pagination;

class SearchParams
{
    protected Pagination $pagination;
    protected string $wildcard = "%";
    protected string $search_text = "";
    protected string $sort_order = "DESC";
    protected ?array $query_params = null;
    protected ?string $from = null;
    protected ?string $to = null;
    protected bool $with_sort = true;
    protected bool $with_range = false;

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

    public function getSort_order(): string
    {
        return $this->sort_order;
    }

    public function setSort_order(string $value)
    {
        $this->sort_order = $value;
    }

    public function getQuery_params(): ?array
    {
        return $this->query_params;
    }

    public function setQuery_params(?array $value)
    {
        $this->query_params = $value;
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

    public function getWith_sort()
    {
        return $this->with_sort;
    }

    public function setWith_sort($value)
    {
        $this->with_sort  = $value;
    }

    public function getWith_range()
    {
        return $this->with_range;
    }

    public function setWith_range($value)
    {
        $this->with_range  = $value;
    }
}
