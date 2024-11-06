<?php

namespace app\src\Common\DTOs;

use app\src\Common\Enums\TokenType;

class ClaimerDTO
{
    private string $user_id;
    private string $platform_key;
    private TokenType $type;
    private array $roles;
    private string $expires_at;

    public function getUser_id(): string
    {
        return $this->user_id;
    }

    public function setUser_id(string $value)
    {
        $this->user_id = $value;
    }

    public function getType(): TokenType
    {
        return $this->type;
    }

    public function setType(TokenType $value)
    {
        $this->type = $value;
    }

    public function getExpires_at(): string
    {
        return $this->expires_at;
    }

    public function setExpires_at(string $value)
    {
        $this->expires_at = $value;
    }

    public function getPlatform_key(): string
    {
        return $this->platform_key;
    }

    public function setPlatform_key(string $value)
    {
        $this->platform_key = $value;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function setRoles(array $value)
    {
        $this->roles = $value;
    }
}
