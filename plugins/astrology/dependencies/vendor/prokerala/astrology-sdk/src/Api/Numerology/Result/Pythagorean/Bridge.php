<?php

namespace Prokerala\Api\Numerology\Result\Pythagorean;

use Prokerala\Api\Astrology\Result\ResultInterface;
use Prokerala\Api\Astrology\Traits\Result\RawResponseTrait;
class Bridge implements ResultInterface
{
    use RawResponseTrait;
    /**
     * @var \Prokerala\Api\Numerology\Result\Pythagorean\BridgeNumber
     */
    private $bridgeNumber;
    public function __construct(\Prokerala\Api\Numerology\Result\Pythagorean\BridgeNumber $bridgeNumber)
    {
        $this->bridgeNumber = $bridgeNumber;
    }
    public function getBridgeNumber() : \Prokerala\Api\Numerology\Result\Pythagorean\BridgeNumber
    {
        return $this->bridgeNumber;
    }
}
