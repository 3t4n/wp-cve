<?php

/**
 * This file is part of the ramsey/uuid library
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @copyright Copyright (c) Ben Ramsey <ben@benramsey.com>
 * @license http://opensource.org/licenses/MIT MIT
 */
declare (strict_types=1);
namespace DhlVendor\Ramsey\Uuid\Converter\Time;

use DhlVendor\Ramsey\Uuid\Converter\TimeConverterInterface;
use DhlVendor\Ramsey\Uuid\Math\CalculatorInterface;
use DhlVendor\Ramsey\Uuid\Math\RoundingMode;
use DhlVendor\Ramsey\Uuid\Type\Hexadecimal;
use DhlVendor\Ramsey\Uuid\Type\Integer as IntegerObject;
use DhlVendor\Ramsey\Uuid\Type\Time;
use function explode;
use function str_pad;
use const STR_PAD_LEFT;
/**
 * GenericTimeConverter uses the provided calculator to calculate and convert
 * time values
 *
 * @psalm-immutable
 */
class GenericTimeConverter implements \DhlVendor\Ramsey\Uuid\Converter\TimeConverterInterface
{
    /**
     * The number of 100-nanosecond intervals from the Gregorian calendar epoch
     * to the Unix epoch.
     */
    private const GREGORIAN_TO_UNIX_INTERVALS = '122192928000000000';
    /**
     * The number of 100-nanosecond intervals in one second.
     */
    private const SECOND_INTERVALS = '10000000';
    /**
     * The number of 100-nanosecond intervals in one microsecond.
     */
    private const MICROSECOND_INTERVALS = '10';
    /**
     * @var CalculatorInterface
     */
    private $calculator;
    public function __construct(\DhlVendor\Ramsey\Uuid\Math\CalculatorInterface $calculator)
    {
        $this->calculator = $calculator;
    }
    public function calculateTime(string $seconds, string $microseconds) : \DhlVendor\Ramsey\Uuid\Type\Hexadecimal
    {
        $timestamp = new \DhlVendor\Ramsey\Uuid\Type\Time($seconds, $microseconds);
        // Convert the seconds into a count of 100-nanosecond intervals.
        $sec = $this->calculator->multiply($timestamp->getSeconds(), new \DhlVendor\Ramsey\Uuid\Type\Integer(self::SECOND_INTERVALS));
        // Convert the microseconds into a count of 100-nanosecond intervals.
        $usec = $this->calculator->multiply($timestamp->getMicroseconds(), new \DhlVendor\Ramsey\Uuid\Type\Integer(self::MICROSECOND_INTERVALS));
        // Combine the seconds and microseconds intervals and add the count of
        // 100-nanosecond intervals from the Gregorian calendar epoch to the
        // Unix epoch. This gives us the correct count of 100-nanosecond
        // intervals since the Gregorian calendar epoch for the given seconds
        // and microseconds.
        /** @var IntegerObject $uuidTime */
        $uuidTime = $this->calculator->add($sec, $usec, new \DhlVendor\Ramsey\Uuid\Type\Integer(self::GREGORIAN_TO_UNIX_INTERVALS));
        $uuidTimeHex = \str_pad($this->calculator->toHexadecimal($uuidTime)->toString(), 16, '0', \STR_PAD_LEFT);
        return new \DhlVendor\Ramsey\Uuid\Type\Hexadecimal($uuidTimeHex);
    }
    public function convertTime(\DhlVendor\Ramsey\Uuid\Type\Hexadecimal $uuidTimestamp) : \DhlVendor\Ramsey\Uuid\Type\Time
    {
        // From the total, subtract the number of 100-nanosecond intervals from
        // the Gregorian calendar epoch to the Unix epoch. This gives us the
        // number of 100-nanosecond intervals from the Unix epoch, which also
        // includes the microtime.
        $epochNanoseconds = $this->calculator->subtract($this->calculator->toInteger($uuidTimestamp), new \DhlVendor\Ramsey\Uuid\Type\Integer(self::GREGORIAN_TO_UNIX_INTERVALS));
        // Convert the 100-nanosecond intervals into seconds and microseconds.
        $unixTimestamp = $this->calculator->divide(\DhlVendor\Ramsey\Uuid\Math\RoundingMode::HALF_UP, 6, $epochNanoseconds, new \DhlVendor\Ramsey\Uuid\Type\Integer(self::SECOND_INTERVALS));
        $split = \explode('.', (string) $unixTimestamp, 2);
        return new \DhlVendor\Ramsey\Uuid\Type\Time($split[0], $split[1] ?? 0);
    }
}
