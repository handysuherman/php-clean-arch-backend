<?php

namespace app\src\Common\Databases;

use Exception;
use Redis as PHPNativeRedis;
use RedisException;

class Redis
{
    public static function New(string $host, int $port = 6379, string $password = '', int $db_index = 0): ?PHPNativeRedis
    {
        try {
            $redis = new PHPNativeRedis();

            $redis->connect($host, $port);

            $redis->auth($password);

            $redis->select($db_index);

            return $redis;
        } catch (RedisException $e) {
            return null;
        } catch (Exception $e) {
            return null;
        }
    }
}
