<?php

declare (strict_types=1);
namespace Prokerala\Api\Astrology\Western\Result\PlanetPositions;

use Prokerala\Api\Astrology\Result\ResultInterface;
use Prokerala\Api\Astrology\Traits\Result\RawResponseTrait;
class SolarReturnChart implements ResultInterface
{
    use RawResponseTrait;
    /**
     * @var \Prokerala\Api\Astrology\Western\Result\PlanetPositions\SolarReturnDetails
     */
    private $solarReturnDetails;
    /**
     * @var PlanetAspect[]
     */
    private $solarReturnNatalAspects;
    /**
     * @var string
     */
    private $solarReturnDatetime;
    /**
     * @var int
     */
    private $solarReturnYear;
    /**
     * @param PlanetAspect[] $solarReturnNatalAspects
     */
    public function __construct(\Prokerala\Api\Astrology\Western\Result\PlanetPositions\SolarReturnDetails $solarReturnDetails, array $solarReturnNatalAspects, string $solarReturnDatetime, int $solarReturnYear)
    {
        $this->solarReturnDetails = $solarReturnDetails;
        $this->solarReturnNatalAspects = $solarReturnNatalAspects;
        $this->solarReturnDatetime = $solarReturnDatetime;
        $this->solarReturnYear = $solarReturnYear;
    }
    public function getSolarDetails() : \Prokerala\Api\Astrology\Western\Result\PlanetPositions\SolarReturnDetails
    {
        return $this->solarReturnDetails;
    }
    /**
     * @return PlanetAspect[]
     */
    public function getSolarNatalAspect() : array
    {
        return $this->solarReturnNatalAspects;
    }
    public function getSolarDatetime() : \DateTimeImmutable
    {
        return new \DateTimeImmutable($this->solarReturnDatetime);
    }
    public function getSolarReturnYear() : int
    {
        return $this->solarReturnYear;
    }
}
