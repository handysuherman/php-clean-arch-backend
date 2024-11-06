<?php

namespace app\src\Common\DTOs;

class ClaimerRoleDTO
{
    private string $role_name;
    private string $created_at;
    private string $updated_at;
    private bool $is_blocked;
    private bool $is_blocked_at;
    private bool $is_blocked_by;
    private bool $is_activated;
    private bool $is_activated_at;
    private bool $is_activated_by;

    public function getRole_name(): string
    {
        return $this->role_name;
    }

    public function setRole_name(string $value)
    {
        $this->role_name = $value;
    }

    public function getCreated_at(): string
    {
        return $this->created_at;
    }

    public function setCreated_at(string $value)
    {
        $this->created_at = $value;
    }

    public function getUpdated_at(): string
    {
        return $this->updated_at;
    }

    public function setUpdated_at(string $value)
    {
        $this->updated_at = $value;
    }

    public function getIs_blocked(): bool
    {
        return $this->is_blocked;
    }

    public function setIs_blocked(bool $value)
    {
        $this->is_blocked = $value;
    }

    public function getIs_blocked_at(): bool
    {
        return $this->is_blocked_at;
    }

    public function setIs_blocked_at(bool $value)
    {
        $this->is_blocked_at = $value;
    }

    public function getIs_blocked_by(): bool
    {
        return $this->is_blocked_by;
    }

    public function setIs_blocked_by(bool $value)
    {
        $this->is_blocked_by = $value;
    }

    public function getIs_activated(): bool
    {
        return $this->is_activated;
    }

    public function setIs_activated(bool $value)
    {
        $this->is_activated = $value;
    }

    public function getIs_activated_at(): bool
    {
        return $this->is_activated_at;
    }

    public function setIs_activated_at(bool $value)
    {
        $this->is_activated_at = $value;
    }

    public function getIs_activated_by(): bool
    {
        return $this->is_activated_by;
    }

    public function setIs_activated_by(bool $value)
    {
        $this->is_activated_by = $value;
    }
}
