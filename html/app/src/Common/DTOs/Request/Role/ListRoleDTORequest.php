<?php

namespace app\src\Common\DTOs\Request\Role;

use app\src\Common\DTOs\Request\SearchDTORequest;

class ListRoleDTORequest extends SearchDTORequest
{
    private ?bool $is_activated = null;
    private ?string $range_property = null;
    private ?string $sort_property = null;

    public function getIs_activated(): ?bool
    {
        return $this->is_activated;
    }

    public function setIs_activated(?bool $value)
    {
        $this->is_activated = $value;
    }

    public function getRange_property(): ?string
    {
        return $this->range_property;
    }

    public function setRange_property(?string $value)
    {
        $this->range_property = $value;
    }

    public function getSort_property(): ?string
    {
        return $this->sort_property;
    }

    public function setSort_property(?string $value)
    {
        $this->sort_property = $value;
    }
}
