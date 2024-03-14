<?php

namespace Prokerala\Api\Numerology\Result\Pythagorean;

use Prokerala\Api\Astrology\Result\ResultInterface;
use Prokerala\Api\Astrology\Traits\Result\RawResponseTrait;
class BirthMonth implements ResultInterface
{
    use RawResponseTrait;
    /**
     * @var \Prokerala\Api\Numerology\Result\Pythagorean\BirthMonthNumber
     */
    private $birthMonthNumber;
    public function __construct(\Prokerala\Api\Numerology\Result\Pythagorean\BirthMonthNumber $birthMonthNumber)
    {
        $this->birthMonthNumber = $birthMonthNumber;
    }
    public function getBirthMonthNumber() : \Prokerala\Api\Numerology\Result\Pythagorean\BirthMonthNumber
    {
        return $this->birthMonthNumber;
    }
}
