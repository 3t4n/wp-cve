<?php

declare (strict_types=1);
namespace Prokerala\Api\Astrology\Western\Result\PlanetPositions;

use Prokerala\Api\Astrology\Result\ResultInterface;
use Prokerala\Api\Astrology\Traits\Result\RawResponseTrait;
class House implements ResultInterface
{
    use RawResponseTrait;
    /**
     * @var int
     */
    private $id;
    /**
     * @var int
     */
    private $number;
    /**
     * @var \Prokerala\Api\Astrology\Western\Result\PlanetPositions\HouseCusp
     */
    private $startCusp;
    /**
     * @var \Prokerala\Api\Astrology\Western\Result\PlanetPositions\HouseCusp
     */
    private $endCusp;
    public function __construct(int $id, int $number, \Prokerala\Api\Astrology\Western\Result\PlanetPositions\HouseCusp $startCusp, \Prokerala\Api\Astrology\Western\Result\PlanetPositions\HouseCusp $endCusp)
    {
        $this->id = $id;
        $this->number = $number;
        $this->startCusp = $startCusp;
        $this->endCusp = $endCusp;
    }
    public function getId() : int
    {
        return $this->id;
    }
    public function getNumber() : int
    {
        return $this->number;
    }
    public function getStartCusp() : \Prokerala\Api\Astrology\Western\Result\PlanetPositions\HouseCusp
    {
        return $this->startCusp;
    }
    public function getEndCusp() : \Prokerala\Api\Astrology\Western\Result\PlanetPositions\HouseCusp
    {
        return $this->endCusp;
    }
}
