<?php

namespace Prokerala\Api\Numerology\Result\Pythagorean;

use Prokerala\Api\Astrology\Result\ResultInterface;
use Prokerala\Api\Astrology\Traits\Result\RawResponseTrait;
class Attainment implements ResultInterface
{
    use RawResponseTrait;
    /**
     * @var \Prokerala\Api\Numerology\Result\Pythagorean\AttainmentNumber
     */
    private $attainmentNumber;
    /**
     * @var \Prokerala\Api\Numerology\Result\Pythagorean\NameChart
     */
    private $nameChart;
    public function __construct(\Prokerala\Api\Numerology\Result\Pythagorean\AttainmentNumber $attainmentNumber, \Prokerala\Api\Numerology\Result\Pythagorean\NameChart $nameChart)
    {
        $this->attainmentNumber = $attainmentNumber;
        $this->nameChart = $nameChart;
    }
    public function getNameChart() : \Prokerala\Api\Numerology\Result\Pythagorean\NameChart
    {
        return $this->nameChart;
    }
    public function getAttainmentNumber() : \Prokerala\Api\Numerology\Result\Pythagorean\AttainmentNumber
    {
        return $this->attainmentNumber;
    }
}
