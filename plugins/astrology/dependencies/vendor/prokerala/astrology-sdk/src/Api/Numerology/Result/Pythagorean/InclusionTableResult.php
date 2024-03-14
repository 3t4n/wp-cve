<?php

namespace Prokerala\Api\Numerology\Result\Pythagorean;

use Prokerala\Api\Astrology\Result\ResultInterface;
use Prokerala\Api\Astrology\Traits\Result\RawResponseTrait;
class InclusionTableResult implements ResultInterface
{
    use RawResponseTrait;
    /**
     * @var InclusionNumber[]
     */
    private $inclusionNumber;
    /**
     * @var \Prokerala\Api\Numerology\Result\Pythagorean\NameChart
     */
    private $nameChart;
    /**
     * @param InclusionNumber[] $inclusionNumber
     */
    public function __construct(array $inclusionNumber, \Prokerala\Api\Numerology\Result\Pythagorean\NameChart $nameChart)
    {
        $this->inclusionNumber = $inclusionNumber;
        $this->nameChart = $nameChart;
    }
    /**
     * @return InclusionNumber[]
     */
    public function getInclusionNumber() : array
    {
        return $this->inclusionNumber;
    }
    public function getNameChart() : \Prokerala\Api\Numerology\Result\Pythagorean\NameChart
    {
        return $this->nameChart;
    }
}
