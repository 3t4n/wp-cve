<?php

namespace Prokerala\Api\Numerology\Result\Pythagorean;

use Prokerala\Api\Astrology\Result\ResultInterface;
use Prokerala\Api\Astrology\Traits\Result\RawResponseTrait;
class PersonalMonth implements ResultInterface
{
    use RawResponseTrait;
    /**
     * @var \Prokerala\Api\Numerology\Result\Pythagorean\PersonalMonthNumber
     */
    private $personalMonthNumber;
    public function __construct(\Prokerala\Api\Numerology\Result\Pythagorean\PersonalMonthNumber $personalMonthNumber)
    {
        $this->personalMonthNumber = $personalMonthNumber;
    }
    public function getPersonalMonthNumber() : \Prokerala\Api\Numerology\Result\Pythagorean\PersonalMonthNumber
    {
        return $this->personalMonthNumber;
    }
}
