<?php

namespace Prokerala\Api\Numerology\Result\Pythagorean;

use Prokerala\Api\Astrology\Result\ResultInterface;
use Prokerala\Api\Astrology\Traits\Result\RawResponseTrait;
class UniversalMonth implements ResultInterface
{
    use RawResponseTrait;
    /**
     * @var \Prokerala\Api\Numerology\Result\Pythagorean\UniversalMonthNumber
     */
    private $universalMonthNumber;
    public function __construct(\Prokerala\Api\Numerology\Result\Pythagorean\UniversalMonthNumber $universalMonthNumber)
    {
        $this->universalMonthNumber = $universalMonthNumber;
    }
    public function getUniversalMonthNumber() : \Prokerala\Api\Numerology\Result\Pythagorean\UniversalMonthNumber
    {
        return $this->universalMonthNumber;
    }
}
