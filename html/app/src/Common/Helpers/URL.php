<?php

namespace app\src\Common\Helpers;

class URL
{
    /** 
     * @see https://github.com/firebase/php-jwt/blob/feb0e820b8436873675fd3aca04f3728eb2185cb/src/JWT.php#L350
     */
    public static function safe_base64_encode(string $text): string
    {
        return str_replace("=", '', strtr(base64_encode($text), '+/', '-_'));
    }
    /**
     * @see https://github.com/firebase/php-jwt/blob/feb0e820b8436873675fd3aca04f3728eb2185cb/src/JWT.php#L333
     */
    public static function safe_base64_decode(string $text): string
    {
        $remainder = strlen($text) % 4;
        if ($remainder) {
            $padlen = 4 - $remainder;
            $text .= str_repeat('=', $padlen);
        }
        return base64_decode(strtr($text, "-_", '+/'));
    }
}