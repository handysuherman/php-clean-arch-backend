<?php

namespace app\src\Application\Config;


class Databases
{
    private MySQL $mysql;
    private Redis $redis;

    public function __construct()
    {
        $this->mysql = new MySQL();
        $this->redis = new Redis();
    }

	public function getMysql() : MySQL {
		return $this->mysql;
	}

	public function getRedis() : Redis {
		return $this->redis;
	}
}