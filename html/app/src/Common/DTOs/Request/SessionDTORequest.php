<?php

namespace app\src\Common\DTOs\Request;

use app\src\Common\Exceptions\ValidationExceptions\DTOLengthLimitExceeded;

class SessionDTORequest
{
    private ?string $uid = null;
    private ?string $user_uid = null;
    private ?string $token = null;
    private ?string $user_agent = null;
    private ?string $user_platform = null;
    private ?string $user_ip = null;
    private ?string $is_blocked = null;
    private ?string $is_blocked_updated_at = null;
    private ?string $is_blocked_updated_by = null;
    private ?string $expires_at = null;
    private ?string $created_at = null;

    public function getUid(): ?string
    {
        return $this->uid;
    }

    public function setUid(?string $value)
    {
        if ($value) {
            if (strlen($value) > 255) {
                throw new DTOLengthLimitExceeded("sessions.uid length limit exceeded");
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
                throw new DTOLengthLimitExceeded("sessions.user_uid length limit exceeded");
            }
        }
        $this->user_uid = $value;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(?string $value)
    {
        $this->token = $value;
    }

    public function getUser_agent(): ?string
    {
        return $this->user_agent;
    }

    public function setUser_agent(?string $value)
    {
        $this->user_agent = $value;
    }

    public function getUser_platform(): ?string
    {
        return $this->user_platform;
    }

    public function setUser_platform(?string $value)
    {
        $this->user_platform = $value;
    }

    public function getUser_ip(): ?string
    {
        return $this->user_ip;
    }

    public function setUser_ip(?string $value)
    {
        if ($value) {
            if (strlen($value) > 255) {
                throw new DTOLengthLimitExceeded("sessions.user_ip length limit exceeded");
            }
        }
        $this->user_ip = $value;
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
                throw new DTOLengthLimitExceeded("sessions.is_blocked_updated_at length limit exceeded");
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
                throw new DTOLengthLimitExceeded("sessions.is_blocked_updated_by length limit exceeded");
            }
        }
        $this->is_blocked_updated_by = $value;
    }

    public function getExpires_at(): ?string
    {
        return $this->expires_at;
    }

    public function setExpires_at(?string $value)
    {
        if ($value) {
            if (strlen($value) > 255) {
                throw new DTOLengthLimitExceeded("sessions.expires_at length limit exceeded");
            }
        }
        $this->expires_at = $value;
    }

    public function getCreated_at(): ?string
    {
        return $this->created_at;
    }

    public function setCreated_at(?string $value)
    {
        if ($value) {
            if (strlen($value) > 255) {
                throw new DTOLengthLimitExceeded("sessions.created_at length limit exceeded");
            }
        }
        $this->created_at = $value;
    }
}
