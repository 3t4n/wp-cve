<?php

namespace Prokerala\Api\Numerology\Result\Pythagorean;

use Prokerala\Api\Astrology\Result\ResultInterface;
use Prokerala\Api\Astrology\Traits\Result\RawResponseTrait;
class RationalThought implements ResultInterface
{
    use RawResponseTrait;
    /**
     * @var \Prokerala\Api\Numerology\Result\Pythagorean\RationalThoughtNumber
     */
    private $rationalThoughtNumber;
    /**
     * @var \Prokerala\Api\Numerology\Result\Pythagorean\NameChart
     */
    private $nameChart;
    public function __construct(\Prokerala\Api\Numerology\Result\Pythagorean\RationalThoughtNumber $rationalThoughtNumber, \Prokerala\Api\Numerology\Result\Pythagorean\NameChart $nameChart)
    {
        $this->rationalThoughtNumber = $rationalThoughtNumber;
        $this->nameChart = $nameChart;
    }
    public function getNameChart() : \Prokerala\Api\Numerology\Result\Pythagorean\NameChart
    {
        return $this->nameChart;
    }
    public function getRationalThoughtNumber() : \Prokerala\Api\Numerology\Result\Pythagorean\RationalThoughtNumber
    {
        return $this->rationalThoughtNumber;
    }
}
