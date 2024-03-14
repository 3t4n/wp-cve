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
final class AdvancedNakshatraPorutham implements ResultInterface
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
     * @var \Prokerala\Api\Astrology\Result\HoroscopeMatching\Message
     */
    private $message;
    /**
     * @var Porutham\AdvancedMatch[]
     */
    private $matches;
    /**
     * AdvancedNakshatraPorutham constructor.
     *
     * @param Porutham\AdvancedMatch[] $matches
     */
    public function __construct(float $maximumPoints, float $obtainedPoints, \Prokerala\Api\Astrology\Result\HoroscopeMatching\Message $message, array $matches)
    {
        $this->maximumPoints = $maximumPoints;
        $this->obtainedPoints = $obtainedPoints;
        $this->message = $message;
        $this->matches = $matches;
    }
    public function getMaximumPoints() : float
    {
        return $this->maximumPoints;
    }
    public function getObtainedPoints() : float
    {
        return $this->obtainedPoints;
    }
    public function getMessage() : \Prokerala\Api\Astrology\Result\HoroscopeMatching\Message
    {
        return $this->message;
    }
    /**
     * @return Porutham\AdvancedMatch[]
     */
    public function getMatches() : array
    {
        return $this->matches;
    }
}
