<?php

namespace Prokerala\Api\Numerology\Result\Pythagorean;

use Prokerala\Api\Astrology\Result\ResultInterface;
use Prokerala\Api\Astrology\Traits\Result\RawResponseTrait;
class PersonalYear implements ResultInterface
{
    use RawResponseTrait;
    /**
     * @var \Prokerala\Api\Numerology\Result\Pythagorean\PersonalYearNumber
     */
    private $personalYearNumber;
    public function __construct(\Prokerala\Api\Numerology\Result\Pythagorean\PersonalYearNumber $personalYearNumber)
    {
        $this->personalYearNumber = $personalYearNumber;
    }
    public function getPersonalYearNumber() : \Prokerala\Api\Numerology\Result\Pythagorean\PersonalYearNumber
    {
        return $this->personalYearNumber;
    }
}
