<?php

namespace app\src\Common\Helpers;

use app\src\Common\Constants\HttpResponseConstants;

class HttpError
{
    public static function ParseError(string $error_message, bool $debug): array
    {
        $error_message = strtolower($error_message);

        switch (true) {
            case strpos($error_message, "api key header") !== false:
                return self::RestError(HttpResponseConstants::STATUS_UNAUTHORIZED, HttpResponseConstants::ERR_UNAUTHORIZED, $error_message, $debug);
            case strpos($error_message, "required header") !== false:
                return self::RestError(HttpResponseConstants::STATUS_BAD_REQUEST, HttpResponseConstants::ERR_BAD_REQUEST, $error_message, $debug);
            case strpos($error_message, "token") !== false:
                return self::RestError(HttpResponseConstants::STATUS_UNAUTHORIZED, HttpResponseConstants::ERR_UNAUTHORIZED, $error_message, $debug);
            case strpos($error_message, "invalid api key") !== false:
                return self::RestError(HttpResponseConstants::STATUS_BAD_REQUEST, HttpResponseConstants::ERR_BAD_REQUEST, $error_message, $debug);
            case strpos($error_message, "invalid platform key") !== false:
                return self::RestError(HttpResponseConstants::STATUS_BAD_REQUEST, HttpResponseConstants::ERR_BAD_REQUEST, $error_message, $debug);
            default:
                return self::RestError(HttpResponseConstants::STATUS_INTERNAL_SERVER_ERROR, HttpResponseConstants::ERR_INTERNAL_SERVER_ERROR, "unexpected error occured", true);
        }
    }

    private static function RestError(int $status, string $err, mixed $causes, bool $debug): array
    {
        $rest_error = [
            HttpResponseConstants::STATUS => $status,
            HttpResponseConstants::MESSAGE => $err,
            HttpResponseConstants::DATA => $causes,
            HttpResponseConstants::TIMESTAMP => Time::now(),
        ];

        return $rest_error;
    }
}