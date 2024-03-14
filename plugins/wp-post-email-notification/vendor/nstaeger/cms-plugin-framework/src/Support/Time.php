<?php

namespace Nstaeger\CmsPluginFramework\Support;

use InvalidArgumentException;

class Time
{
    const SQL_FORMAT = 'Y-m-d G:i:s';
    /**
     * @var int
     */
    private $timestamp;

    public static function now()
    {
        return new Time(time());
    }

    public static function of($timestamp)
    {
        return new Time($timestamp);
    }

    private function __construct($timestamp)
    {
        $this->timestamp = $timestamp;
    }

    public function asSqlTimestamp()
    {
        return date(self::SQL_FORMAT, $this->timestamp);
    }

    public function addMinutes($minutes)
    {
        return $this->addSeconds($this->asInt($minutes) * 60);
    }

    public function addSeconds($seconds)
    {
        $this->timestamp += $this->asInt($seconds);

        return $this;
    }

    public function getTimestamp()
    {
        return $this->timestamp;
    }

    private function asInt($v)
    {
        if (is_int($v)) {
            return $v;
        } else if (is_string($v)) {
            return intval($v);
        } else {
            throw new InvalidArgumentException("Could not convert value to integer");
        }
    }
}
