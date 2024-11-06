<?php

namespace app\src\Common\Loggers;

use Monolog\Handler\RotatingFileHandler;
use Monolog\Level;
use Monolog\Logger as MonologLogger;

class Logger
{
    private MonologLogger $log;

    public function __construct(string $general_log_name)
    {
        $logger = new MonologLogger($general_log_name);

        $logFile = __DIR__ . '/logs/app.log';

        $fileHandler = new RotatingFileHandler($logFile, 3, Level::Debug);

        $logger->pushHandler($fileHandler);

        $this->log = $logger;
    }

    public function info(string $context, string $message)
    {
        $this->log->info("[$context]: $message");
    }

    public function warning(string $context, string $message)
    {
        $this->log->warning("[$context]: $message");
    }

    public function error(string $context, string $message)
    {
        $this->log->error("[$context]: $message");
    }
}
