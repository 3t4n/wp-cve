<?php

namespace Prokerala\Api\Numerology\Result\Pythagorean;

use Prokerala\Api\Astrology\Result\ResultInterface;
use Prokerala\Api\Astrology\Traits\Result\RawResponseTrait;
class PlanesOfExpression implements ResultInterface
{
    use RawResponseTrait;
    /**
     * @var \Prokerala\Api\Numerology\Result\Pythagorean\Number
     */
    private $physical;
    /**
     * @var \Prokerala\Api\Numerology\Result\Pythagorean\Number
     */
    private $mental;
    /**
     * @var \Prokerala\Api\Numerology\Result\Pythagorean\Number
     */
    private $emotional;
    /**
     * @var \Prokerala\Api\Numerology\Result\Pythagorean\Number
     */
    private $spiritual;
    /**
     * @var \Prokerala\Api\Numerology\Result\Pythagorean\NameChart
     */
    private $nameChart;
    public function __construct(\Prokerala\Api\Numerology\Result\Pythagorean\Number $physical, \Prokerala\Api\Numerology\Result\Pythagorean\Number $mental, \Prokerala\Api\Numerology\Result\Pythagorean\Number $emotional, \Prokerala\Api\Numerology\Result\Pythagorean\Number $spiritual, \Prokerala\Api\Numerology\Result\Pythagorean\NameChart $nameChart)
    {
        $this->physical = $physical;
        $this->mental = $mental;
        $this->emotional = $emotional;
        $this->spiritual = $spiritual;
        $this->nameChart = $nameChart;
    }
    public function getNameChart() : \Prokerala\Api\Numerology\Result\Pythagorean\NameChart
    {
        return $this->nameChart;
    }
    public function getSpiritual() : \Prokerala\Api\Numerology\Result\Pythagorean\Number
    {
        return $this->spiritual;
    }
    public function getMental() : \Prokerala\Api\Numerology\Result\Pythagorean\Number
    {
        return $this->mental;
    }
    public function getEmotional() : \Prokerala\Api\Numerology\Result\Pythagorean\Number
    {
        return $this->emotional;
    }
    public function getPhysical() : \Prokerala\Api\Numerology\Result\Pythagorean\Number
    {
        return $this->physical;
    }
}
