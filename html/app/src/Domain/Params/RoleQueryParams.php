<?php

namespace app\src\Domain\Params;


class RoleQueryParams extends SearchParams
{
    private string $sort_property = "created_at";
    private string $range_property = "created_at";

    public function getSort_property(): string
    {
        return $this->sort_property;
    }

    public function setSort_property(string $value)
    {
        $this->sort_property = $value;
    }

    public function getRange_property(): string
    {
        return $this->range_property;
    }

    public function setRange_property(string $value)
    {
        $this->range_property = $value;
    }
}
