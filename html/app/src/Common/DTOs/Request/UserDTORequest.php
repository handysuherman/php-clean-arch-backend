<?php

namespace app\src\Common\DTOs\Request;

use app\src\Common\Exceptions\ValidationExceptions\DTOLengthLimitExceeded;

class UserDTORequest
{
    private ?string $uid = null;
    private ?string $full_name = null;
    private ?string $full_name_slug = null;
    private ?string $email = null;
    private ?string $profile_pict_url = null;
    private ?string $is_email_verified = null;
    private ?string $date_of_birth = null;
    private ?string $created_at = null;
    private ?string $updated_at = null;
    private ?string $updated_by = null;
    private ?string $is_blocked = null;
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
                throw new DTOLengthLimitExceeded("user.uid length limit exceeded");
            }
        }
        $this->uid = $value;
    }

    public function getFull_name(): ?string
    {
        return $this->full_name;
    }

    public function setFull_name(?string $value)
    {
        if ($value) {
            if (strlen($value) > 255) {
                throw new DTOLengthLimitExceeded("user.full_name length limit exceeded");
            }
        }
        $this->full_name = $value;
    }

    public function getFull_name_slug(): ?string
    {
        return $this->full_name_slug;
    }

    public function setFull_name_slug(?string $value)
    {
        $this->full_name_slug = $value;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $value)
    {
        $this->email = $value;
    }

    public function getProfile_pict_url(): ?string
    {
        return $this->profile_pict_url;
    }

    public function setProfile_pict_url(?string $value)
    {
        $this->profile_pict_url = $value;
    }

    public function getIs_email_verified(): ?string
    {
        return $this->is_email_verified;
    }

    public function setIs_email_verified(?string $value)
    {
        $this->is_email_verified = $value;
    }

    public function getDate_of_birth(): ?string
    {
        return $this->date_of_birth;
    }

    public function setDate_of_birth(?string $value)
    {
        $this->date_of_birth = $value;
    }

    public function getCreated_at(): ?string
    {
        return $this->created_at;
    }

    public function setCreated_at(?string $value)
    {
        if ($value) {
            if (strlen($value) > 255) {
                throw new DTOLengthLimitExceeded("user.created_at length limit exceeded");
            }
        }
        $this->created_at = $value;
    }

    public function getUpdated_at(): ?string
    {
        return $this->updated_at;
    }

    public function setUpdated_at(?string $value)
    {
        if ($value) {
            if (strlen($value) > 255) {
                throw new DTOLengthLimitExceeded("user.updated_at length limit exceeded");
            }
        }
        $this->updated_at = $value;
    }

    public function getUpdated_by(): ?string
    {
        return $this->updated_by;
    }

    public function setUpdated_by(?string $value)
    {
        if ($value) {
            if (strlen($value) > 255) {
                throw new DTOLengthLimitExceeded("user.updated_by length limit exceeded");
            }
        }
        $this->updated_by = $value;
    }

    public function getIs_blocked(): ?string
    {
        return $this->is_blocked;
    }

    public function setIs_blocked(?string $value)
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
                throw new DTOLengthLimitExceeded("user.is_blocked_updated_at length limit exceeded");
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
                throw new DTOLengthLimitExceeded("user.is_blocked_updated_by length limit exceeded");
            }
        }
        $this->is_blocked_updated_by = $value;
    }
}
