<?php

namespace app\src\Common\Helpers;

use app\src\Common\Constants\HttpResponseConstants;

class Http
{
    public static function SuccessResponse(int $status, mixed $data = null, ?string $message = null)
    {
        $response = [
            HttpResponseConstants::STATUS => $status,
            HttpResponseConstants::MESSAGE => $message ?: 'OK',
            HttpResponseConstants::TIMESTAMP => Time::atomicMicroFormat(Time::now()),
            HttpResponseConstants::DATA => $data,
        ];

        return $response;
    }
}
