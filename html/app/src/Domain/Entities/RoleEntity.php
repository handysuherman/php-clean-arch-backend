<?php

namespace app\src\Domain\Entities;

use app\src\Common\Exceptions\ValidationExceptions\EntityLengthLimitExceeded;
use app\src\Common\Exceptions\ValidationExceptions\LengthLimitExceededException;
use app\src\Common\Exceptions\ValidationExceptions\RequiredMinLengthException;
use app\src\Common\Exceptions\ValidationExceptions\RequiredPropertyException;
use app\src\Common\Validations\SchemaValidation;

class RoleEntity implements SchemaValidation
{
    private string $uid;
    private string $role_name;
    private string $description;
    private string $role_name_slug;
    private string $created_at;
    private string $created_by;
    private bool $is_activated;
    private string $is_activated_updated_at;
    private string $is_activated_updated_by;

    public function getUid(): string
    {
        return $this->uid;
    }

    public function setUid(string $value)
    {
        $this->uid = $value;
    }

    public function getRole_name(): string
    {
        return $this->role_name;
    }

    public function setRole_name(string $value)
    {
        $this->role_name = $value;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $value)
    {
        $this->description = $value;
    }

    public function getRole_name_slug(): string
    {
        return $this->role_name_slug;
    }

    public function setRole_name_slug(string $value)
    {
        $this->role_name_slug = $value;
    }

    public function getCreated_at(): string
    {

        return $this->created_at;
    }

    public function setCreated_at(string $value)
    {
        $this->created_at = $value;
    }

    public function getCreated_by(): string
    {
        return $this->created_by;
    }

    public function setCreated_by(string $value)
    {
        $this->created_by = $value;
    }

    public function getIs_activated(): bool
    {
        return $this->is_activated;
    }

    public function setIs_activated(bool $value)
    {
        $this->is_activated = $value;
    }

    public function getIs_activated_updated_at(): string
    {
        return $this->is_activated_updated_at;
    }

    public function setIs_activated_updated_at(string $value)
    {
        $this->is_activated_updated_at = $value;
    }

    public function getIs_activated_updated_by(): string
    {
        return $this->is_activated_updated_by;
    }

    public function setIs_activated_updated_by(string $value)
    {
        $this->is_activated_updated_by = $value;
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
