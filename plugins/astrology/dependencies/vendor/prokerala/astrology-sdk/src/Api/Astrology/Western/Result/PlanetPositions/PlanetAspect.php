<?php

declare (strict_types=1);
namespace Prokerala\Api\Astrology\Western\Result\PlanetPositions;

use Prokerala\Api\Astrology\Result\ResultInterface;
use Prokerala\Api\Astrology\Traits\Result\RawResponseTrait;
class PlanetAspect implements ResultInterface
{
    use RawResponseTrait;
    /**
     * @var \Prokerala\Api\Astrology\Western\Result\PlanetPositions\Planet
     */
    private $planetOne;
    /**
     * @var \Prokerala\Api\Astrology\Western\Result\PlanetPositions\Planet
     */
    private $planetTwo;
    /**
     * @var \Prokerala\Api\Astrology\Western\Result\PlanetPositions\Aspect
     */
    private $aspect;
    /**
     * @var float
     */
    private $orb;
    public function __construct(\Prokerala\Api\Astrology\Western\Result\PlanetPositions\Planet $planetOne, \Prokerala\Api\Astrology\Western\Result\PlanetPositions\Planet $planetTwo, \Prokerala\Api\Astrology\Western\Result\PlanetPositions\Aspect $aspect, float $orb)
    {
        $this->planetOne = $planetOne;
        $this->planetTwo = $planetTwo;
        $this->aspect = $aspect;
        $this->orb = $orb;
    }
    public function getPlanetOne() : \Prokerala\Api\Astrology\Western\Result\PlanetPositions\Planet
    {
        return $this->planetOne;
    }
    public function getPlanetTwo() : \Prokerala\Api\Astrology\Western\Result\PlanetPositions\Planet
    {
        return $this->planetTwo;
    }
    public function getAspect() : \Prokerala\Api\Astrology\Western\Result\PlanetPositions\Aspect
    {
        return $this->aspect;
    }
    public function getOrb() : float
    {
        return $this->orb;
    }
}
