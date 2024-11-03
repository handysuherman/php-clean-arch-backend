<?php

namespace app\src\Common\Enums;

enum DurationType: string
{
    case DAY = "day";
    case HOUR = 'hour';
    case MINUTE = 'minute';
    case SECOND = "second";
}
