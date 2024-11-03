<?php

namespace app\src\Common\Helpers;

use Ramsey\Uuid\Uuid;
use Ulid\Ulid;

class Identifier
{
    public static function newUUIDV4(): string
    {
        return Uuid::uuid4()->toString();
    }

    public static function newULID(): string
    {
        return Ulid::generate()->__toString();
    }
}
