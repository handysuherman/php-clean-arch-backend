<?php

namespace app\src\Common\Helpers;

class Generation
{
    private const ALPHABET = 'abcdefghijklmnopqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ';
    private const NUMERIC = '123456789';

    public static function secureRandomInt64(): int
    {
        return unpack('P', random_bytes(8))[1];
    }

    public static function randomInt(int $min, int $max): int
    {
        return random_int($min, $max);
    }

    public static function randomString(int $length): string
    {
        $charactersLength = strlen(self::ALPHABET);
        $result = '';

        for ($i = 0; $i < $length; $i++) {
            $result .= self::ALPHABET[random_int(0, $charactersLength - 1)];
        }

        return $result;
    }

    public static function randomStringSelection(string ...$items): string
    {
        return $items[array_rand($items)];
    }

    public static function randomStringInt(int $length): string
    {
        $charactersLength = strlen(self::NUMERIC);
        $result = '';

        for ($i = 0; $i < $length; $i++) {
            $result .= self::NUMERIC[random_int(0, $charactersLength - 1)];
        }

        return $result;
    }

    public static function randomPhoneNumber(): string
    {
        return '628' . self::randomStringInt(10);
    }

    public static function randomName(): string
    {
        return self::randomString(12);
    }

    public static function randomNumber(): int
    {
        return self::randomInt(100000000000, 999999999999);
    }

    public static function randomCurrencies(): string
    {
        $currencies = ['IDR', 'USD'];
        return self::randomStringSelection(...$currencies);
    }

    public static function randomBool(): bool
    {
        return (bool)random_int(0, 1);
    }

    public static function randomUrl(): string
    {
        return sprintf('https://%s.com', self::randomString(6));
    }

    public static function randomJenisBukti(): string
    {
        $jenis = ['KTP', 'Ijazah', 'KK', 'CV'];
        return self::randomStringSelection(...$jenis);
    }


    public static function randomEmail(): string
    {
        return sprintf('%s@email.com', self::randomString(6));
    }
}
