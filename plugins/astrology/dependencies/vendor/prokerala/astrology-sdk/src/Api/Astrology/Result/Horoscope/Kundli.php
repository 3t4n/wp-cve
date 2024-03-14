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
final class Kundli implements ResultInterface
{
    use RawResponseTrait;
    /**
     * @var \Prokerala\Api\Astrology\Result\Horoscope\BirthDetails
     */
    private $nakshatraDetails;
    /**
     * @var \Prokerala\Api\Astrology\Result\Horoscope\MangalDosha
     */
    private $mangalDosha;
    /**
     * @var Yoga\YogaDetails[]
     */
    private $yogaDetails;
    /**
     * Kundli constructor.
     *
     * @param Yoga\YogaDetails[] $yogaDetails
     */
    public function __construct(\Prokerala\Api\Astrology\Result\Horoscope\BirthDetails $nakshatraDetails, \Prokerala\Api\Astrology\Result\Horoscope\MangalDosha $mangalDosha, array $yogaDetails)
    {
        $this->nakshatraDetails = $nakshatraDetails;
        $this->mangalDosha = $mangalDosha;
        $this->yogaDetails = $yogaDetails;
    }
    public function getNakshatraDetails() : \Prokerala\Api\Astrology\Result\Horoscope\BirthDetails
    {
        return $this->nakshatraDetails;
    }
    public function getMangalDosha() : \Prokerala\Api\Astrology\Result\Horoscope\MangalDosha
    {
        return $this->mangalDosha;
    }
    /**
     * @return Yoga\YogaDetails[]
     */
    public function getYogaDetails() : array
    {
        return $this->yogaDetails;
    }
}
