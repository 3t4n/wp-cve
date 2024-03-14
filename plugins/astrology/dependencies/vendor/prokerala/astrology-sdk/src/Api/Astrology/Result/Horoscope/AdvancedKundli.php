<?php

/*
 * This file is part of Prokerala Astrology API PHP SDK
 *
 * © Ennexa Technologies <info@ennexa.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */
namespace Prokerala\Api\Astrology\Result\Horoscope;

use Prokerala\Api\Astrology\Result\ResultInterface;
use Prokerala\Api\Astrology\Traits\Result\RawResponseTrait;
final class AdvancedKundli implements ResultInterface
{
    use RawResponseTrait;
    /**
     * @var \Prokerala\Api\Astrology\Result\Horoscope\BirthDetails
     */
    private $nakshatraDetails;
    /**
     * @var \Prokerala\Api\Astrology\Result\Horoscope\AdvancedMangalDosha
     */
    private $mangalDosha;
    /**
     * @var Yoga\AdvancedYogaDetails[]
     */
    private $yogaDetails;
    /**
     * @var Dasha\DashaPeriod[]
     */
    private $dashaPeriods;
    /**
     * AdvancedKundli constructor.
     *
     * @param Yoga\AdvancedYogaDetails[] $yogaDetails
     * @param Dasha\DashaPeriod[]        $dashaPeriods
     */
    public function __construct(\Prokerala\Api\Astrology\Result\Horoscope\BirthDetails $nakshatraDetails, \Prokerala\Api\Astrology\Result\Horoscope\AdvancedMangalDosha $mangalDosha, array $yogaDetails, array $dashaPeriods)
    {
        $this->nakshatraDetails = $nakshatraDetails;
        $this->mangalDosha = $mangalDosha;
        $this->yogaDetails = $yogaDetails;
        $this->dashaPeriods = $dashaPeriods;
    }
    public function getNakshatraDetails() : \Prokerala\Api\Astrology\Result\Horoscope\BirthDetails
    {
        return $this->nakshatraDetails;
    }
    public function getMangalDosha() : \Prokerala\Api\Astrology\Result\Horoscope\AdvancedMangalDosha
    {
        return $this->mangalDosha;
    }
    /**
     * @return Yoga\AdvancedYogaDetails[]
     */
    public function getYogaDetails() : array
    {
        return $this->yogaDetails;
    }
    /**
     * @return Dasha\DashaPeriod[]
     */
    public function getDashaPeriods() : array
    {
        return $this->dashaPeriods;
    }
}
