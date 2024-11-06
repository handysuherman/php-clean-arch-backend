<?php

namespace app\src\Application\Config;

class MySQL
{
    const MYSQL_HOST = "MYSQL_HOST";
    const MYSQL_PORT = "MYSQL_PORT";
    const MYSQL_DB_NAME = "MYSQL_DB_NAME";
    const MYSQL_USERNAME = "MYSQL_USERNAME";
    const MYSQL_PASSWORD = "MYSQL_PASSWORD";

    private string $host;
    private string $port;
    private string $db_name;
    private string $username;
    private string $password;

    public function __construct()
    {
        $this->host = $_SERVER[self::MYSQL_HOST];

        $this->port = $_SERVER[self::MYSQL_PORT];

        $this->db_name = $_SERVER[self::MYSQL_DB_NAME];

        $this->username = $_SERVER[self::MYSQL_USERNAME];

        $this->password = $_SERVER[self::MYSQL_PASSWORD];
    }

	public function getHost() : string {
		return $this->host;
	}

	public function getPort() : string {
		return $this->port;
	}

	public function getDb_name() : string {
		return $this->db_name;
	}

	public function getUsername() : string {
		return $this->username;
	}

	public function getPassword() : string {
		return $this->password;
	}
}