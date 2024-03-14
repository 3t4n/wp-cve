<?php

namespace Prokerala\Api\Numerology\Result\Chaldean;

use Prokerala\Api\Astrology\Result\ResultInterface;
use Prokerala\Api\Astrology\Traits\Result\RawResponseTrait;
use Prokerala\Api\Numerology\Result\Pythagorean\NameChart;
class DailyName implements ResultInterface
{
    use RawResponseTrait;
    /**
     * @var \Prokerala\Api\Numerology\Result\Chaldean\DailyNameNumber
     */
    private $dailyNameNumber;
    /**
     * @var \Prokerala\Api\Numerology\Result\Pythagorean\NameChart
     */
    private $nameChart;
    public function __construct(\Prokerala\Api\Numerology\Result\Chaldean\DailyNameNumber $dailyNameNumber, NameChart $nameChart)
    {
        $this->dailyNameNumber = $dailyNameNumber;
        $this->nameChart = $nameChart;
    }
    public function getNameChart() : NameChart
    {
        return $this->nameChart;
    }
    public function getDailyNameNumber() : \Prokerala\Api\Numerology\Result\Chaldean\DailyNameNumber
    {
        return $this->dailyNameNumber;
    }
}
