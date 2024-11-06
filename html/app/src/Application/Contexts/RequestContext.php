<?php

namespace app\src\Application\Contexts;

use app\src\Common\DTOs\ClaimerDTO;

class RequestContext
{
    private string $platform_key;
    private string $user_ip;
    private string $user_agent;
    private ?ClaimerDTO $auth_user = null;

    public function getPlatform_key(): string
    {
        return $this->platform_key;
    }

    public function setPlatform_key(string $value)
    {
        $this->platform_key = $value;
    }

    public function getUser_ip(): string
    {
        return $this->user_ip;
    }

    public function setUser_ip(string $value)
    {
        $this->user_ip = $value;
    }

    public function getUser_agent(): string
    {
        return $this->user_agent;
    }

    public function setUser_agent(string $value)
    {
        $this->user_agent = $value;
    }

    public function getAuth_user(): ?ClaimerDTO
    {
        return $this->auth_user;
    }

    public function setAuth_user(?ClaimerDTO $value)
    {
        $this->auth_user = $value;
    }
}
