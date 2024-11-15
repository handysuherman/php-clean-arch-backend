<?php

namespace app\src\Common\DTOs\Request;

use app\src\Common\Exceptions\ValidationExceptions\DTOLengthLimitExceeded;

class UserRoleDTORequest
{
    private ?string $uid = null;
    private ?string $user_uid = null;
    private ?string $role_uid = null;
    private ?string $created_at = null;
    private ?string $created_by = null;
    private ?bool $is_blocked = null;
    private ?string $is_blocked_updated_at = null;
    private ?string $is_blocked_updated_by = null;

    public function getUid(): ?string
    {
        return $this->uid;
    }

    public function setUid(?string $value)
    {
        if ($value) {
            if (strlen($value) > 255) {
                throw new DTOLengthLimitExceeded("user_role.uid length limit exceeded");
            }
        }
        $this->uid = $value;
    }

    public function getUser_uid(): ?string
    {
        return $this->user_uid;
    }

    public function setUser_uid(?string $value)
    {
        if ($value) {
            if (strlen($value) > 255) {
                throw new DTOLengthLimitExceeded("user_role.user_uid length limit exceeded");
            }
        }
        $this->user_uid = $value;
    }

    public function getRole_uid(): ?string
    {
        return $this->role_uid;
    }

    public function setRole_uid(?string $value)
    {
        if ($value) {
            if (strlen($value) > 255) {
                throw new DTOLengthLimitExceeded("user_role.role_uid length limit exceeded");
            }
        }
        $this->role_uid = $value;
    }

    public function getCreated_at(): ?string
    {
        return $this->created_at;
    }

    public function setCreated_at(?string $value)
    {
        if ($value) {
            if (strlen($value) > 255) {
                throw new DTOLengthLimitExceeded("user_role.created_at length limit exceeded");
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
                throw new DTOLengthLimitExceeded("user_role.created_by length limit exceeded");
            }
        }
        $this->created_by = $value;
    }

    public function getIs_blocked(): ?bool
    {
        return $this->is_blocked;
    }

    public function setIs_blocked(?bool $value)
    {
        $this->is_blocked = $value;
    }

    public function getIs_blocked_updated_at(): ?string
    {
        return $this->is_blocked_updated_at;
    }

    public function setIs_blocked_updated_at(?string $value)
    {
        if ($value) {
            if (strlen($value) > 255) {
                throw new DTOLengthLimitExceeded("user_role.is_blocked_updated_at length limit exceeded");
            }
        }
        $this->is_blocked_updated_at = $value;
    }

    public function getIs_blocked_updated_by(): ?string
    {
        return $this->is_blocked_updated_by;
    }

    public function setIs_blocked_updated_by(?string $value)
    {
        if ($value) {
            if (strlen($value) > 255) {
                throw new DTOLengthLimitExceeded("user_role.is_blocked_updated_by length limit exceeded");
            }
        }
        $this->is_blocked_updated_by = $value;
    }
}
