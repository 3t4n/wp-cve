<?php

declare (strict_types=1);
namespace Prokerala\Api\Astrology\Western\Result\PlanetPositions;

use Prokerala\Api\Astrology\Result\ResultInterface;
use Prokerala\Api\Astrology\Traits\Result\RawResponseTrait;
class TransitChart implements ResultInterface
{
    use RawResponseTrait;
    /**
     * @var \Prokerala\Api\Astrology\Western\Result\PlanetPositions\TransitDetails
     */
    private $transitDetails;
    /**
     * @var PlanetAspect[]
     */
    private $transitNatalAspect;
    /**
     * @var string
     */
    private $transitDatetime;
    /**
     * @param PlanetAspect[] $transitNatalAspects
     */
    public function __construct(\Prokerala\Api\Astrology\Western\Result\PlanetPositions\TransitDetails $transitDetails, array $transitNatalAspects, string $transitDatetime)
    {
        $this->transitDetails = $transitDetails;
        $this->transitNatalAspect = $transitNatalAspects;
        $this->transitDatetime = $transitDatetime;
    }
    public function getTransitDetails() : \Prokerala\Api\Astrology\Western\Result\PlanetPositions\TransitDetails
    {
        return $this->transitDetails;
    }
    /**
     * @return PlanetAspect[]
     */
    public function getTransitNatalAspect() : array
    {
        return $this->transitNatalAspect;
    }
    public function getTransitDatetime() : \DateTimeImmutable
    {
        return new \DateTimeImmutable($this->transitDatetime);
    }
}
