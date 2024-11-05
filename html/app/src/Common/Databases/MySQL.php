<?php

namespace app\src\Common\Databases;

use PDO;

class MySQL
{
    public static function New(string $host, string $db_name, string $username, string $password, int $port = 3306,): PDO
    {
        return new PDO("mysql:host=$host:$port;dbname=$db_name", $username, $password);
    }
}
