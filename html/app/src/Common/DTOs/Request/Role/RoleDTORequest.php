<?php

namespace app\src\Common\DTOs\Request\Role;

use app\src\Common\Exceptions\ValidationExceptions\DTOLengthLimitExceeded;

class RoleDTORequest
{
    private ?string $uid = null;
    private ?string $role_name = null;
    private ?string $description = null;
    private ?string $role_name_slug = null;
    private ?string $created_at = null;
    private ?string $created_by = null;
    private ?bool $is_activated = null;
    private ?string $is_activated_updated_at = null;
    private ?string $is_activated_updated_by = null;

    public function getUid(): ?string
    {
        return $this->uid;
    }

    public function setUid(?string $value)
    {
        if ($value) {
            if (strlen($value) > 255) {
                throw new DTOLengthLimitExceeded("role.uid length limit exceeded");
            }
        }
        $this->uid = $value;
    }

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

    public function getRole_name_slug(): ?string
    {
        return $this->role_name_slug;
    }

    public function setRole_name_slug(?string $value)
    {
        $this->role_name_slug = $value;
    }

    public function getCreated_at(): ?string
    {
        return $this->created_at;
    }

    public function setCreated_at(?string $value)
    {
        if ($value) {
            if (strlen($value) > 255) {
                throw new DTOLengthLimitExceeded("role.created_at length limit exceeded");
            }
        }
        $this->created_at = $value;
    }

    public function getCreated_by(): ?string
    {
        return $this->created_by;
    }

    public function setCreated_by(?string $value)
    {
        if ($value) {
            if (strlen($value) > 255) {
                throw new DTOLengthLimitExceeded("role.created_by length limit exceeded");
            }
        }
        $this->created_by = $value;
    }

    public function getIs_activated(): ?bool
    {
        return $this->is_activated;
    }

    public function setIs_activated(?bool $value)
    {
        $this->is_activated = $value;
    }

    public function getIs_activated_updated_at(): ?string
    {
        return $this->is_activated_updated_at;
    }

    public function setIs_activated_updated_at(?string $value)
    {
        if ($value) {
            if (strlen($value) > 255) {
                throw new DTOLengthLimitExceeded("role.is_activated_updated_at length limit exceeded");
            }
        }
        $this->is_activated_updated_at = $value;
    }

    public function getIs_activated_updated_by(): ?string
    {
        return $this->is_activated_updated_by;
    }

    public function setIs_activated_updated_by(?string $value)
    {
        if ($value) {
            if (strlen($value) > 255) {
                throw new DTOLengthLimitExceeded("role.is_activated_updated_by length limit exceeded");
            }
        }
        $this->is_activated_updated_by = $value;
    }
}
