<?php

namespace app\src\Common\Helpers;

class Text
{
    public static function toSlugify(string $text, bool $to_lowercase = false): string
    {
        $text = preg_replace('/\s+/', '-', $text);

        $text = preg_replace('/[^a-zA-Z0-9-]+/', '-', $text);

        $text = preg_replace('/-{2,}/', '-', $text);

        $text = trim($text, '-');

        $final_text = $to_lowercase ? strtolower($text) : $text;

        return $final_text;
    }
}
