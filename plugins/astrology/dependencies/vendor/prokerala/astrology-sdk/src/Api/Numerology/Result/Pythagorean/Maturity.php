<?php

namespace Prokerala\Api\Numerology\Result\Pythagorean;

use Prokerala\Api\Astrology\Result\ResultInterface;
use Prokerala\Api\Astrology\Traits\Result\RawResponseTrait;
class Maturity implements ResultInterface
{
    use RawResponseTrait;
    /**
     * @var \Prokerala\Api\Numerology\Result\Pythagorean\MaturityNumber
     */
    private $maturityNumber;
    /**
     * @var \Prokerala\Api\Numerology\Result\Pythagorean\NameChart
     */
    private $nameChart;
    public function __construct(\Prokerala\Api\Numerology\Result\Pythagorean\MaturityNumber $maturityNumber, \Prokerala\Api\Numerology\Result\Pythagorean\NameChart $nameChart)
    {
        $this->maturityNumber = $maturityNumber;
        $this->nameChart = $nameChart;
    }
    public function getNameChart() : \Prokerala\Api\Numerology\Result\Pythagorean\NameChart
    {
        return $this->nameChart;
    }
    public function getMaturityNumber() : \Prokerala\Api\Numerology\Result\Pythagorean\MaturityNumber
    {
        return $this->maturityNumber;
    }
}
