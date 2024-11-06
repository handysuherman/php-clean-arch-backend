<?php

namespace app\src\Common\Constants;


class HttpResponseConstants
{
    const ID = "id";
    const UID = "uid";
    const STATUS = "status";
    const DATA = "data";
    const MESSAGE = "message";
    const TIMESTAMP = "timestamp";

    const STATUS_OK = 200;
    const OK = "OK";

    // 4xx
    const STATUS_NOT_FOUND = 404;
    const ERR_NOT_FOUND = "not found";

    const STATUS_FORBIDDEN = 403;
    const ERR_FORBIDDEN = "forbidden";

    const STATUS_UNAUTHORIZED = 401;
    const ERR_UNAUTHORIZED = "unauthorized";

    const STATUS_BAD_REQUEST = 400;
    const ERR_BAD_REQUEST = "bad request";

    const STATUS_TOO_MANY_REQUEST = 429;
    const ERR_TOO_MANY_REQUEST = "too many request";

    // 5xx
    const STATUS_INTERNAL_SERVER_ERROR = 500;
    const ERR_INTERNAL_SERVER_ERROR = "internal server error";
}
