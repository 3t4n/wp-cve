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

use Prokerala\Api\Astrology\Result\ResultInterface;
use Prokerala\Api\Astrology\Traits\Result\RawResponseTrait;
final class ThirumanaPorutham implements ResultInterface
{
    use RawResponseTrait;
    /**
     * @var float
     */
    private $maximumPoints;
    /**
     * @var float
     */
    private $obtainedPoints;
    /**
     * @var Porutham\BasicMatch[]
     */
    private $matches;
    /**
     * @var \Prokerala\Api\Astrology\Result\HoroscopeMatching\Message
     */
    private $message;
    /**
     * ThirumanaPorutham constructor.
     *
     * @param Porutham\BasicMatch[] $matches
     */
    public function __construct(float $maximumPoints, float $obtainedPoints, \Prokerala\Api\Astrology\Result\HoroscopeMatching\Message $message, array $matches)
    {
        $this->maximumPoints = $maximumPoints;
        $this->obtainedPoints = $obtainedPoints;
        $this->matches = $matches;
        $this->message = $message;
    }
    public function getMessage() : \Prokerala\Api\Astrology\Result\HoroscopeMatching\Message
    {
        return $this->message;
    }
    public function getMaximumPoints() : float
    {
        return $this->maximumPoints;
    }
    public function getObtainedPoints() : float
    {
        return $this->obtainedPoints;
    }
    /**
     * @return Porutham\BasicMatch[]
     */
    public function getMatches() : array
    {
        return $this->matches;
    }
}
