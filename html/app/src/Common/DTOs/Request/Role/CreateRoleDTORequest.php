<?php

namespace app\src\Common\DTOs\Request\Role;

use app\src\Common\Exceptions\ValidationExceptions\LengthLimitExceededException;
use app\src\Common\Exceptions\ValidationExceptions\RequiredMinLengthException;
use app\src\Common\Exceptions\ValidationExceptions\RequiredPropertyException;
use app\src\Common\Validations\SchemaValidation;

class CreateRoleDTORequest implements SchemaValidation
{
    private ?string $role_name = null;
    private ?string $description = null;

    public function getRole_name(): ?string
    {
        return $this->role_name;
    }

    public function setRole_name(?string $value)
    {
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

    public function validateRequestData(): bool
    {
        if ($this->role_name) {
            if (strlen($this->role_name) < 5) {
                throw new RequiredMinLengthException("role.role_name min length was 5");
            }

            if (strlen($this->role_name) > 100) {
                throw new LengthLimitExceededException("role.role_name length limit exceeded");
            }
        } else {
            throw new RequiredPropertyException(sprintf("role_name should not be empty: %s", $this->role_name));
        }

        if ($this->description) {
            if (strlen($this->description) < 1) {
                throw new RequiredMinLengthException("role.description min length was 1");
            }

            if (strlen($this->description) > 300) {
                throw new LengthLimitExceededException("role.description length limit exceeded");
            }
        }

        return true;
    }
}
