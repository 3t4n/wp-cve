<?php

namespace Prokerala\Api\Horoscope\Result;

use Prokerala\Api\Astrology\Result\ResultInterface;
use Prokerala\Api\Astrology\Traits\Result\RawResponseTrait;
class DailyHoroscope implements ResultInterface
{
    use RawResponseTrait;
    /**
     * @var \Prokerala\Api\Horoscope\Result\DailyHoroscopePrediction
     */
    private $dailyPrediction;
    public function __construct(\Prokerala\Api\Horoscope\Result\DailyHoroscopePrediction $dailyPrediction)
    {
        $this->dailyPrediction = $dailyPrediction;
    }
    public function getDailyHoroscopePrediction() : \Prokerala\Api\Horoscope\Result\DailyHoroscopePrediction
    {
        return $this->dailyPrediction;
    }
}
