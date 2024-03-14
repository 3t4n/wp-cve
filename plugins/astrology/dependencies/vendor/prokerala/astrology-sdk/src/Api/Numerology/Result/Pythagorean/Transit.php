<?php

namespace Prokerala\Api\Numerology\Result\Pythagorean;

use Prokerala\Api\Astrology\Result\ResultInterface;
use Prokerala\Api\Astrology\Traits\Result\RawResponseTrait;
class Transit implements ResultInterface
{
    use RawResponseTrait;
    /**
     * @var Cycle[]
     */
    private $physical;
    /**
     * @var Cycle[]
     */
    private $mental;
    /**
     * @var Cycle[]
     */
    private $spiritual;
    /**
     * @var \Prokerala\Api\Numerology\Result\Pythagorean\NameChart
     */
    private $nameChart;
    /**
     * @param Cycle[] $physical
     * @param Cycle[] $mental
     * @param Cycle[] $spiritual
     */
    public function __construct(array $physical, array $mental, array $spiritual, \Prokerala\Api\Numerology\Result\Pythagorean\NameChart $nameChart)
    {
        $this->physical = $physical;
        $this->mental = $mental;
        $this->spiritual = $spiritual;
        $this->nameChart = $nameChart;
    }
    public function getNameChart() : \Prokerala\Api\Numerology\Result\Pythagorean\NameChart
    {
        return $this->nameChart;
    }
    /**
     * @return Cycle[]
     */
    public function getPhysical() : array
    {
        return $this->physical;
    }
    /**
     * @return Cycle[]
     */
    public function getMental() : array
    {
        return $this->mental;
    }
    /**
     * @return Cycle[]
     */
    public function getSpiritual() : array
    {
        return $this->spiritual;
    }
}
