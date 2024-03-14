<?php

/*
 * This file is part of Prokerala Astrology API PHP SDK
 *
 * © Ennexa Technologies <info@ennexa.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */
namespace Prokerala\Api\Astrology\Result\Panchang;

use Prokerala\Api\Astrology\Result\ResultInterface;
use Prokerala\Api\Astrology\Traits\Result\RawResponseTrait;
final class Panchang implements ResultInterface
{
    use RawResponseTrait;
    /**
     * @var string
     */
    private $vaara;
    /**
     * @var \Prokerala\Api\Astrology\Result\EventTiming\Nakshatra[]
     */
    private $nakshatra;
    /**
     * @var \Prokerala\Api\Astrology\Result\EventTiming\Tithi[]
     */
    private $tithi;
    /**
     * @var \Prokerala\Api\Astrology\Result\EventTiming\Karana[]
     */
    private $karana;
    /**
     * @var \Prokerala\Api\Astrology\Result\EventTiming\Yoga[]
     */
    private $yoga;
    /**
     * @var \DateTimeInterface
     */
    private $sunrise;
    /**
     * @var \DateTimeInterface
     */
    private $sunset;
    /**
     * @var \DateTimeInterface
     */
    private $moonrise;
    /**
     * @var \DateTimeInterface
     */
    private $moonset;
    /**
     * Panchang constructor.
     *
     * @param \Prokerala\Api\Astrology\Result\EventTiming\Nakshatra[] $nakshatra
     * @param \Prokerala\Api\Astrology\Result\EventTiming\Tithi[]     $tithi
     * @param \Prokerala\Api\Astrology\Result\EventTiming\Karana[]    $karana
     * @param \Prokerala\Api\Astrology\Result\EventTiming\Yoga[]      $yoga
     */
    public function __construct(string $vaara, array $nakshatra, array $tithi, array $karana, array $yoga, \DateTimeInterface $sunrise, \DateTimeInterface $sunset, \DateTimeInterface $moonrise, \DateTimeInterface $moonset)
    {
        $this->vaara = $vaara;
        $this->nakshatra = $nakshatra;
        $this->tithi = $tithi;
        $this->karana = $karana;
        $this->yoga = $yoga;
        $this->sunrise = $sunrise;
        $this->sunset = $sunset;
        $this->moonrise = $moonrise;
        $this->moonset = $moonset;
    }
    public function getVaara() : string
    {
        return $this->vaara;
    }
    /**
     * @return \Prokerala\Api\Astrology\Result\EventTiming\Nakshatra[]
     */
    public function getNakshatra() : array
    {
        return $this->nakshatra;
    }
    /**
     * @return \Prokerala\Api\Astrology\Result\EventTiming\Tithi[]
     */
    public function getTithi() : array
    {
        return $this->tithi;
    }
    /**
     * @return \Prokerala\Api\Astrology\Result\EventTiming\Karana[]
     */
    public function getKarana() : array
    {
        return $this->karana;
    }
    /**
     * @return \Prokerala\Api\Astrology\Result\EventTiming\Yoga[]
     */
    public function getYoga() : array
    {
        return $this->yoga;
    }
    public function getSunrise() : \DateTimeInterface
    {
        return $this->sunrise;
    }
    public function getSunset() : \DateTimeInterface
    {
        return $this->sunset;
    }
    public function getMoonrise() : \DateTimeInterface
    {
        return $this->moonrise;
    }
    public function getMoonset() : \DateTimeInterface
    {
        return $this->moonset;
    }
}
