<?php

namespace app\src\Common\DTOs\Request;

use app\src\Common\Exceptions\ValidationExceptions\DTOLengthLimitExceeded;

class UserCredentialsDTORequest
{
    private ?string $uid = null;
    private ?string $user_uid = null;
    private ?bool $has_hashed_password = null;
    private ?string $hashed_password = null;
    private ?string $password_changed_at = null;
    private ?bool $is_password_removed = null;
    private ?string $is_password_removed_updated_at = null;
    private ?string $is_password_removed_updated_by = null;

    public function getUid(): ?string
    {
        return $this->uid;
    }

    public function setUid(?string $value)
    {
        if ($value) {
            if (strlen($value) > 255) {
                throw new DTOLengthLimitExceeded("user_credentials.uid length limit exceeded");
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
                throw new DTOLengthLimitExceeded("user_credentials.user_uid length limit exceeded");
            }
        }
        $this->user_uid = $value;
    }

    public function getHas_hashed_password(): ?bool
    {
        return $this->has_hashed_password;
    }

    public function setHas_hashed_password(?bool $value)
    {
        $this->has_hashed_password = $value;
    }

    public function getHashed_password(): ?string
    {
        return $this->hashed_password;
    }

    public function setHashed_password(?string $value)
    {
        $this->hashed_password = $value;
    }

    public function getPassword_changed_at(): ?string
    {
        return $this->password_changed_at;
    }

    public function setPassword_changed_at(?string $value)
    {
        if ($value) {
            if (strlen($value) > 255) {
                throw new DTOLengthLimitExceeded("user_credentials.password_changed_at length limit exceeded");
            }
        }
        $this->password_changed_at = $value;
    }

    public function getIs_password_removed(): ?bool
    {
        return $this->is_password_removed;
    }

    public function setIs_password_removed(?bool $value)
    {
        $this->is_password_removed = $value;
    }

    public function getIs_password_removed_updated_at(): ?string
    {
        return $this->is_password_removed_updated_at;
    }

    public function setIs_password_removed_updated_at(?string $value)
    {
        if ($value) {
            if (strlen($value) > 255) {
                throw new DTOLengthLimitExceeded("user_credentials.is_password_removed_updated_at length limit exceeded");
            }
        }
        $this->is_password_removed_updated_at = $value;
    }

    public function getIs_password_removed_updated_by(): ?string
    {
        return $this->is_password_removed_updated_by;
    }

    public function setIs_password_removed_updated_by(?string $value)
    {
        if ($value) {
            if (strlen($value) > 255) {
                throw new DTOLengthLimitExceeded("user_credentials.is_password_removed_updated_by length limit exceeded");
            }
        }
        $this->is_password_removed_updated_by = $value;
    }
}
