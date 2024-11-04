<?php

namespace app\src\Common\Helpers;

use app\src\Common\Enums\DurationType;
use DateTime;
use DateTimeZone;

class Time
{
    public static function now(): \DateTime
    {
        return new DateTime('now', new DateTimeZone('UTC'));
    }

    public static function atomicFormat(\DateTime $date): string
    {
        return $date->format(DateTime::ATOM);
    }

    public static function atomicMicroFormat(\DateTime $date): string
    {
        return $date->format("Y-m-d\TH:i:s.uP");
    }

    public static function stringToDateTime(string $date): \DateTime
    {
        return new DateTime($date);
    }

    public static function revertAtomicMicroFormatToDateTime(string $atomic_string): \DateTime
    {
        $dateTime = \DateTime::createFromFormat("Y-m-d\TH:i:s.uP", $atomic_string);

        if ($dateTime === false) {
            throw new \Exception("Invalid date format: " . implode(", ", \DateTime::getLastErrors()));
        }

        return $dateTime;
    }

    public static function addDuration(int $duration = 24, DurationType $duration_type = DurationType::DAY, bool $is_past = false, ?DateTime $date = null): DateTime
    {
        $expiration_date =  $date ? $date : new DateTime('now', new DateTimeZone('UTC'));

        if ($is_past) {
            switch ($duration_type) {
                case DurationType::DAY:
                    $expiration_date = self::addPastDuration("-$duration day", $expiration_date);
                    break;
                case DurationType::HOUR:
                    $expiration_date = self::addPastDuration("-$duration hour", $expiration_date);
                    break;
                case DurationType::MINUTE:
                    $expiration_date = self::addPastDuration("-$duration minute", $expiration_date);
                    break;
                case DurationType::SECOND:
                    $expiration_date = self::addPastDuration("-$duration second", $expiration_date);
                default:
                    $expiration_date = self::addPastDuration("-$duration day", $expiration_date);
                    break;
            }

            return $expiration_date;
        }

        switch ($duration_type) {
            case DurationType::DAY:
                $expiration_date = self::addDays($duration, $expiration_date);
                break;
            case DurationType::HOUR:
                $expiration_date = self::addHours($duration, $expiration_date);
                break;
            case DurationType::MINUTE:
                $expiration_date = self::addMinutes($duration, $expiration_date);
                break;
            case DurationType::SECOND:
                $expiration_date = self::addSeconds($duration, $expiration_date);
            default:
                $expiration_date = self::addDays($duration, $expiration_date);
                break;
        }

        return $expiration_date;
    }

    private static function addPastDuration(string $modifier, \DateTime $date = null): \DateTime
    {
        $dateTime = $date ? $date : new \DateTime('now', new \DateTimeZone('UTC'));
        $dateTime->modify($modifier);
        return $dateTime;
    }

    private static function addDays(int $days, \DateTime $date = null): \DateTime
    {
        $date = $date ? $date : new DateTime();
        $date->add(new \DateInterval('P' . str_pad($days, 2, '0', STR_PAD_LEFT) . 'D'));
        return $date;
    }

    private static function addHours(int $hours, \DateTime $date = null): \DateTime
    {
        $date =  $date ? $date : new DateTime();
        $date->add(new \DateInterval('PT' . $hours . 'H'));
        return $date;
    }

    private static function addMinutes(int $minutes, \DateTime $date = null): \DateTime
    {
        $date =  $date ? $date : new DateTime();
        $date->add(new \DateInterval('PT' . $minutes . 'M'));
        return $date;
    }

    private static function addSeconds(int $seconds, \DateTime $date = null): \DateTime
    {
        $date =  $date ? $date : new DateTime();
        $date->add(new \DateInterval('PT' . $seconds . 'S'));
        return $date;
    }
}
