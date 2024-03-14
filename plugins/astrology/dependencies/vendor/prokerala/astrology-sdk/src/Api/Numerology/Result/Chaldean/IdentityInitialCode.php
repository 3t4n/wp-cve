<?php

namespace Prokerala\Api\Numerology\Result\Chaldean;

use Prokerala\Api\Astrology\Result\ResultInterface;
use Prokerala\Api\Astrology\Traits\Result\RawResponseTrait;
use Prokerala\Api\Numerology\Result\Pythagorean\NameChart;
class IdentityInitialCode implements ResultInterface
{
    use RawResponseTrait;
    /**
     * @var \Prokerala\Api\Numerology\Result\Chaldean\IdentityInitialCodeNumber
     */
    private $identityInitialCodeNumber;
    /**
     * @var \Prokerala\Api\Numerology\Result\Pythagorean\NameChart
     */
    private $nameChart;
    public function __construct(\Prokerala\Api\Numerology\Result\Chaldean\IdentityInitialCodeNumber $identityInitialCodeNumber, NameChart $nameChart)
    {
        $this->identityInitialCodeNumber = $identityInitialCodeNumber;
        $this->nameChart = $nameChart;
    }
    public function getNameChart() : NameChart
    {
        return $this->nameChart;
    }
    public function getIdentityInitialCodeNumber() : \Prokerala\Api\Numerology\Result\Chaldean\IdentityInitialCodeNumber
    {
        return $this->identityInitialCodeNumber;
    }
}
