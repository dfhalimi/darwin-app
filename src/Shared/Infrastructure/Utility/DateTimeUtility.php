<?php

namespace App\Shared\Infrastructure\Utility;

use DateTime;
use DateTimeZone;
use Exception;

class DateTimeUtility
{
    const FORMAT_DATABASE_DATETIME = 'Y-m-d H:i:s';
    const FORMAT_DATABASE_DATE = 'Y-m-d';
    const FORMAT_HUMAN_DE = 'd.m.Y, H:i:s';
    const FORMAT_DIRECTORY_DAY = 'Y-m-d';

    /**
     * @throws Exception
     */
    public static function createDateTimeUtc(string $time = 'now'): DateTime
    {
        return new DateTime($time, new DateTimeZone('UTC'));
    }
}
