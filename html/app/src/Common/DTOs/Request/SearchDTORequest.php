<?php

namespace app\src\Common\DTOs\Request;

use app\src\Common\Helpers\Pagination;

class SearchDTORequest
{
    protected Pagination $pagination;
    protected ?string $from = null;
    protected ?string $to = null;
    protected string $sort_order = "DESC";
    protected string $q = "";

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

    public function getSort_order(): string
    {
        return $this->sort_order;
    }

    public function setSort_order(string $value)
    {
        $this->sort_order = $value;
    }

    public function getQ(): string
    {
        return $this->q;
    }

    public function setQ(string $value)
    {
        $this->q = $value;
    }

    public function getPagination(): Pagination
    {
        return $this->pagination;
    }

    public function setPagination(Pagination $value)
    {
        $this->pagination = $value;
    }
}
