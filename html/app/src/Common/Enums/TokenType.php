<?php

namespace app\src\Common\Enums;

enum TokenType: string
{
    case ACCESS = 'access';
    case REFRESH = 'refresh';
}
