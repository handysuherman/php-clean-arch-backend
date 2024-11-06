<?php

namespace app\src\Common\Databases;

use app\src\Application\Config\Config;
use PDO;

class MySQL
{
    protected Config $cfg;

    private ?\PDO $connection;

    public function __construct(Config $cfg)
    {
        $this->cfg = $cfg;

        $host = $this->cfg->getDatabases()->getMysql()->getHost();
        $db_name = $this->cfg->getDatabases()->getMysql()->getDb_name();
        $username = $this->cfg->getDatabases()->getMysql()->getUsername();
        $password = $this->cfg->getDatabases()->getMysql()->getPassword();
        $port = $this->cfg->getDatabases()->getMysql()->getPort();

        $this->connection = new PDO("mysql:host=$host:$port;dbname=$db_name", $username, $password);
    }

    public function setConnection(?\PDO $value)
    {
        $this->connection = $value;
    }

    public function getConnection(): ?\PDO
    {
        return $this->connection;
    }
}
