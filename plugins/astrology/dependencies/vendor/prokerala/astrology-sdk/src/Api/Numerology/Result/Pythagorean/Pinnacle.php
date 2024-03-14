<?php

namespace Prokerala\Api\Numerology\Result\Pythagorean;

use Prokerala\Api\Astrology\Result\ResultInterface;
use Prokerala\Api\Astrology\Traits\Result\RawResponseTrait;
class Pinnacle implements ResultInterface
{
    use RawResponseTrait;
    /**
     * @var \Prokerala\Api\Numerology\Result\Pythagorean\PinnacleNumber
     */
    private $pinnacleNumber;
    public function __construct(\Prokerala\Api\Numerology\Result\Pythagorean\PinnacleNumber $pinnacleNumber)
    {
        $this->pinnacleNumber = $pinnacleNumber;
    }
    public function getPinnacleNumber() : \Prokerala\Api\Numerology\Result\Pythagorean\PinnacleNumber
    {
        return $this->pinnacleNumber;
    }
}
