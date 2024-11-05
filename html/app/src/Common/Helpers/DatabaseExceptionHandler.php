<?php

namespace app\src\Common\Helpers;

use app\src\Common\Exceptions\SQLExceptions\ConflictException;
use app\src\Common\Exceptions\SQLExceptions\ConnectionDoneException;
use app\src\Common\Exceptions\SQLExceptions\SQLException;
use Exception;

class DatabaseExceptionHandler
{
    public static function handle(\PDOException $e, string $context = ''): void
    {
        switch ($e->getCode()) {
            case "23000":
                throw new ConflictException("$context" . "_conflict_exception: ", $e->getMessage(), $e->getCode(), $e);
            case "08003":
                throw new ConnectionDoneException("$context" . "_connection_done_exception: ", $e->getMessage());
            default:
                throw new SQLException("$context" . "_exception: " . $e->getMessage());
        }
    }
}