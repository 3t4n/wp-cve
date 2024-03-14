<?php

namespace Prokerala\Api\Numerology\Result\Pythagorean;

use Prokerala\Api\Astrology\Result\ResultInterface;
use Prokerala\Api\Astrology\Traits\Result\RawResponseTrait;
class PersonalDay implements ResultInterface
{
    use RawResponseTrait;
    /**
     * @var \Prokerala\Api\Numerology\Result\Pythagorean\PersonalDayNumber
     */
    private $personalDayNumber;
    public function __construct(\Prokerala\Api\Numerology\Result\Pythagorean\PersonalDayNumber $personalDayNumber)
    {
        $this->personalDayNumber = $personalDayNumber;
    }
    public function getPersonalDayNumber() : \Prokerala\Api\Numerology\Result\Pythagorean\PersonalDayNumber
    {
        return $this->personalDayNumber;
    }
}
