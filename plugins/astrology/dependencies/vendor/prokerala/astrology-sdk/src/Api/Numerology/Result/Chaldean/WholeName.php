<?php

namespace Prokerala\Api\Numerology\Result\Chaldean;

use Prokerala\Api\Astrology\Result\ResultInterface;
use Prokerala\Api\Astrology\Traits\Result\RawResponseTrait;
use Prokerala\Api\Numerology\Result\Pythagorean\NameChart;
class WholeName implements ResultInterface
{
    use RawResponseTrait;
    /**
     * @var \Prokerala\Api\Numerology\Result\Chaldean\WholeNameNumber
     */
    private $wholeNameNumber;
    /**
     * @var \Prokerala\Api\Numerology\Result\Pythagorean\NameChart
     */
    private $nameChart;
    public function __construct(\Prokerala\Api\Numerology\Result\Chaldean\WholeNameNumber $wholeNameNumber, NameChart $nameChart)
    {
        $this->wholeNameNumber = $wholeNameNumber;
        $this->nameChart = $nameChart;
    }
    public function getNameChart() : NameChart
    {
        return $this->nameChart;
    }
    public function getWholeNameNumber() : \Prokerala\Api\Numerology\Result\Chaldean\WholeNameNumber
    {
        return $this->wholeNameNumber;
    }
}
