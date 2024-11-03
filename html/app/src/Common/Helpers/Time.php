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

    public static function addDuration(int $duration = 24, DurationType $duration_type = DurationType::DAY, bool $is_past = false): DateTime
    {
        $expiration_date = new DateTime();

        if ($is_past) {
            switch ($duration_type) {
                case DurationType::DAY:
                    $expiration_date = self::addPastDuration("-$duration day");
                    break;
                case DurationType::HOUR:
                    $expiration_date = self::addPastDuration("-$duration hour");
                    break;
                case DurationType::MINUTE:
                    $expiration_date = self::addPastDuration("-$duration minute");
                    break;
                case DurationType::SECOND:
                    $expiration_date = self::addPastDuration("-$duration second");
                default:
                    $expiration_date = self::addPastDuration("-$duration day");
                    break;
            }

            return $expiration_date;
        }

        switch ($duration_type) {
            case DurationType::DAY:
                $expiration_date = self::addDays($duration);
                break;
            case DurationType::HOUR:
                $expiration_date = self::addHours($duration);
                break;
            case DurationType::MINUTE:
                $expiration_date = self::addMinutes($duration);
                break;
            case DurationType::SECOND:
                $expiration_date = self::addSeconds($duration);
            default:
                $expiration_date = self::addDays($duration);
                break;
        }

        return $expiration_date;
    }

    private static function addPastDuration(string $modifier): \DateTime
    {
        $dateTime = new \DateTime('now', new \DateTimeZone('UTC'));
        $dateTime->modify($modifier);
        return $dateTime;
    }

    private static function addDays(int $days, \DateTime $date = null): \DateTime
    {
        $date = $date ?? new DateTime();
        $date->add(new \DateInterval('P' . str_pad($days, 2, '0', STR_PAD_LEFT) . 'D'));
        return $date;
    }

    private static function addHours(int $hours, \DateTime $date = null): \DateTime
    {
        $date = $date ?? new DateTime();
        $date->add(new \DateInterval('PT' . $hours . 'H'));
        return $date;
    }

    private static function addMinutes(int $minutes, \DateTime $date = null): \DateTime
    {
        $date = $date ?? new DateTime();
        $date->add(new \DateInterval('PT' . $minutes . 'M'));
        return $date;
    }

    private static function addSeconds(int $seconds, \DateTime $date = null): \DateTime
    {
        $date = $date ?? new DateTime();
        $date->add(new \DateInterval('PT' . $seconds . 'S'));
        return $date;
    }
}
