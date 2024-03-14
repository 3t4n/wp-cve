<?php

namespace Prokerala\Api\Numerology\Result\Pythagorean;

use Prokerala\Api\Astrology\Result\ResultInterface;
use Prokerala\Api\Astrology\Traits\Result\RawResponseTrait;
class UniversalDay implements ResultInterface
{
    use RawResponseTrait;
    /**
     * @var \Prokerala\Api\Numerology\Result\Pythagorean\UniversalDayNumber
     */
    private $universalDayNumber;
    public function __construct(\Prokerala\Api\Numerology\Result\Pythagorean\UniversalDayNumber $universalDayNumber)
    {
        $this->universalDayNumber = $universalDayNumber;
    }
    public function getUniversalDayNumber() : \Prokerala\Api\Numerology\Result\Pythagorean\UniversalDayNumber
    {
        return $this->universalDayNumber;
    }
}
