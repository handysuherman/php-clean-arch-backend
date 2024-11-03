<?php

namespace app\src\Common\DTOs\Response;

class SessionDTOResponse
{
    private string $uid;
    private string $user_uid;
    private string $user_agent;
    private string $user_platform;
    private string $user_ip;
    private string $is_blocked;
    private string $is_blocked_updated_at;
    private string $is_blocked_updated_by;
    private string $expires_at;
    private string $created_at;

    public function getUid(): string
    {
        return $this->uid;
    }

    public function setUid(string $value)
    {
        $this->uid = $value;
    }

    public function getUser_uid(): string
    {
        return $this->user_uid;
    }

    public function setUser_uid(string $value)
    {
        $this->user_uid = $value;
    }

    public function getUser_agent(): string
    {
        return $this->user_agent;
    }

    public function setUser_agent(string $value)
    {
        $this->user_agent = $value;
    }

    public function getUser_platform(): string
    {
        return $this->user_platform;
    }

    public function setUser_platform(string $value)
    {
        $this->user_platform = $value;
    }

    public function getUser_ip(): string
    {
        return $this->user_ip;
    }

    public function setUser_ip(string $value)
    {
        $this->user_ip = $value;
    }

    public function getIs_blocked(): string
    {
        return $this->is_blocked;
    }

    public function setIs_blocked(string $value)
    {
        $this->is_blocked = $value;
    }

    public function getIs_blocked_updated_at(): string
    {
        return $this->is_blocked_updated_at;
    }

    public function setIs_blocked_updated_at(string $value)
    {
        $this->is_blocked_updated_at = $value;
    }

    public function getIs_blocked_updated_by(): string
    {
        return $this->is_blocked_updated_by;
    }

    public function setIs_blocked_updated_by(string $value)
    {
        $this->is_blocked_updated_by = $value;
    }

    public function getExpires_at(): string
    {
        return $this->expires_at;
    }

    public function setExpires_at(string $value)
    {
        $this->expires_at = $value;
    }

    public function getCreated_at(): string
    {
        return $this->created_at;
    }

    public function setCreated_at(string $value)
    {
        $this->created_at = $value;
    }
}
