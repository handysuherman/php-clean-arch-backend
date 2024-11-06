<?php

namespace app\src\Common\DTOs\Request\Role;

use app\src\Common\Exceptions\ValidationExceptions\DTOLengthLimitExceeded;
use app\src\Common\Exceptions\ValidationExceptions\RequiredPropertyException;

class CreateRoleDTORequest
{
    private ?string $role_name = null;
    private ?string $description = null;

    public function getRole_name(): ?string
    {
        return $this->role_name;
    }

    public function setRole_name(?string $value)
    {
        if ($value) {
            if (strlen($value) > 255) {
                throw new DTOLengthLimitExceeded("role.role_name length limit exceeded");
            }
        } else {
            throw new RequiredPropertyException(sprintf("role_name should not be empty: %s", $value));
        }

        $this->role_name = $value;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $value)
    {
        $this->description = $value;
    }
}
