<?php

namespace app\src\Common\DTOs\Response;


class UserCredentialsDTOResponse
{
    private string $uid;
    private string $user_uid;
    private bool $has_hashed_password;
    private string $password_changed_at;
    private bool $is_password_removed;
    private string $is_password_removed_updated_at;
    private string $is_password_removed_updated_by;

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

    public function getHas_hashed_password(): bool
    {
        return $this->has_hashed_password;
    }

    public function setHas_hashed_password(bool $value)
    {
        $this->has_hashed_password = $value;
    }

    public function getPassword_changed_at(): string
    {
        return $this->password_changed_at;
    }

    public function setPassword_changed_at(string $value)
    {
        $this->password_changed_at = $value;
    }

    public function getIs_password_removed(): bool
    {
        return $this->is_password_removed;
    }

    public function setIs_password_removed(bool $value)
    {
        $this->is_password_removed = $value;
    }

    public function getIs_password_removed_updated_at(): string
    {
        return $this->is_password_removed_updated_at;
    }

    public function setIs_password_removed_updated_at(string $value)
    {
        $this->is_password_removed_updated_at = $value;
    }

    public function getIs_password_removed_updated_by(): string
    {
        return $this->is_password_removed_updated_by;
    }

    public function setIs_password_removed_updated_by(string $value)
    {
        $this->is_password_removed_updated_by = $value;
    }
}
