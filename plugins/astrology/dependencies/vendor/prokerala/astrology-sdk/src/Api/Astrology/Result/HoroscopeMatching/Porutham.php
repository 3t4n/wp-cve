<?php

/*
 * This file is part of Prokerala Astrology API PHP SDK
 *
 * © Ennexa Technologies <info@ennexa.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */
namespace Prokerala\Api\Astrology\Result\HoroscopeMatching;

use Prokerala\Api\Astrology\Result\HoroscopeMatching\Porutham\Profile;
use Prokerala\Api\Astrology\Result\ResultInterface;
use Prokerala\Api\Astrology\Traits\Result\RawResponseTrait;
final class Porutham implements ResultInterface
{
    use RawResponseTrait;
    /**
     * @var \Prokerala\Api\Astrology\Result\HoroscopeMatching\Porutham\Profile
     */
    private $girlInfo;
    /**
     * @var \Prokerala\Api\Astrology\Result\HoroscopeMatching\Porutham\Profile
     */
    private $boyInfo;
    /**
     * @var float
     */
    private $maximumPoints;
    /**
     * @var float
     */
    private $totalPoints;
    /**
     * @var \Prokerala\Api\Astrology\Result\HoroscopeMatching\Message
     */
    private $message;
    /**
     * @var Porutham\BasicMatch[]
     */
    private $matches;
    /**
     * Porutham constructor.
     *
     * @param Porutham\BasicMatch[] $matches
     */
    public function __construct(Profile $girlInfo, Profile $boyInfo, float $maximumPoints, float $totalPoints, \Prokerala\Api\Astrology\Result\HoroscopeMatching\Message $message, array $matches)
    {
        $this->girlInfo = $girlInfo;
        $this->boyInfo = $boyInfo;
        $this->maximumPoints = $maximumPoints;
        $this->totalPoints = $totalPoints;
        $this->message = $message;
        $this->matches = $matches;
    }
    public function getGirlInfo() : Profile
    {
        return $this->girlInfo;
    }
    public function getBoyInfo() : Profile
    {
        return $this->boyInfo;
    }
    public function getMaximumPoints() : float
    {
        return $this->maximumPoints;
    }
    public function getTotalPoints() : float
    {
        return $this->totalPoints;
    }
    public function getMessage() : \Prokerala\Api\Astrology\Result\HoroscopeMatching\Message
    {
        return $this->message;
    }
    /**
     * @return Porutham\BasicMatch[]
     */
    public function getMatches() : array
    {
        return $this->matches;
    }
}
