<?php

namespace app\src\Application\Config;

class Redis
{
    const REDIS_HOST = "REDIS_HOST";
    const REDIS_PORT = "REDIS_PORT";
    const REDIS_PASSWORD = "REDIS_PASSWORD";
    const REDIS_DB_INDEX = "REDIS_DB_INDEX";

    private string $host;
    private string $port;
    private string $password;
    private string $db_index;

    public function __construct()
    {
        $this->host = $_SERVER[self::REDIS_HOST];

        $this->port = $_SERVER[self::REDIS_PORT];

        $this->password = $_SERVER[self::REDIS_PASSWORD];

        $this->db_index = $_SERVER[self::REDIS_DB_INDEX];
    }

	public function getHost() : string {
		return $this->host;
	}

	public function getPort() : string {
		return $this->port;
	}

	public function getPassword() : string {
		return $this->password;
	}

	public function getDb_index() : string {
		return $this->db_index;
	}
}