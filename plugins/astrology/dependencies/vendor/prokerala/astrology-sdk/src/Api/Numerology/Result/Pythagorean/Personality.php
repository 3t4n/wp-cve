<?php

namespace Prokerala\Api\Numerology\Result\Pythagorean;

use Prokerala\Api\Astrology\Result\ResultInterface;
use Prokerala\Api\Astrology\Traits\Result\RawResponseTrait;
class Personality implements ResultInterface
{
    use RawResponseTrait;
    /**
     * @var \Prokerala\Api\Numerology\Result\Pythagorean\PersonalityNumber
     */
    private $personalityNumber;
    public function __construct(\Prokerala\Api\Numerology\Result\Pythagorean\PersonalityNumber $personalityNumber)
    {
        $this->personalityNumber = $personalityNumber;
    }
    public function getPersonalityNumber() : \Prokerala\Api\Numerology\Result\Pythagorean\PersonalityNumber
    {
        return $this->personalityNumber;
    }
}
