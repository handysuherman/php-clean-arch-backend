<?php

namespace app\src\Common\Databases;

use PDO;

class MySQL
{
    public static function New(string $host, int $port = 3306, string $db_name, string $username, string $password): PDO
    {
        return new PDO("mysql:host=$host:$port;dbname=$db_name", $username, $password);
    }
}
