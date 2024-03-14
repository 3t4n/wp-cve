<?php

namespace Prokerala\Api\Numerology\Result\Chaldean;

use Prokerala\Api\Astrology\Result\ResultInterface;
use Prokerala\Api\Astrology\Traits\Result\RawResponseTrait;
class LifePath implements ResultInterface
{
    use RawResponseTrait;
    /**
     * @var \Prokerala\Api\Numerology\Result\Chaldean\LifePathNumber
     */
    private $lifePathNumber;
    public function __construct(\Prokerala\Api\Numerology\Result\Chaldean\LifePathNumber $lifePathNumber)
    {
        $this->lifePathNumber = $lifePathNumber;
    }
    public function getLifePathNumber() : \Prokerala\Api\Numerology\Result\Chaldean\LifePathNumber
    {
        return $this->lifePathNumber;
    }
}
