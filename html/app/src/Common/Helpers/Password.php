<?php

namespace app\src\Common\Helpers;

class Password
{
    public static function hash(string $password): string
    {
        return password_hash($password, PASSWORD_BCRYPT);
    }

    public static function verify(string $password, string $hashed_password): string
    {
        return password_verify($password, $hashed_password);
    }
}
