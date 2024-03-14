<?php

namespace Prokerala\Api\Numerology\Result\Pythagorean;

use Prokerala\Api\Astrology\Result\ResultInterface;
use Prokerala\Api\Astrology\Traits\Result\RawResponseTrait;
class Challenge implements ResultInterface
{
    use RawResponseTrait;
    /**
     * @var \Prokerala\Api\Numerology\Result\Pythagorean\ChallengeNumber
     */
    private $challengeNumber;
    public function __construct(\Prokerala\Api\Numerology\Result\Pythagorean\ChallengeNumber $challengeNumber)
    {
        $this->challengeNumber = $challengeNumber;
    }
    public function getChallengeNumber() : \Prokerala\Api\Numerology\Result\Pythagorean\ChallengeNumber
    {
        return $this->challengeNumber;
    }
}
