<?php

namespace Prokerala\Api\Numerology\Result\Pythagorean;

use Prokerala\Api\Astrology\Result\ResultInterface;
use Prokerala\Api\Astrology\Traits\Result\RawResponseTrait;
class LifeCycle implements ResultInterface
{
    use RawResponseTrait;
    /**
     * @var \Prokerala\Api\Numerology\Result\Pythagorean\LifeCycleNumber
     */
    private $lifeCycle;
    public function __construct(\Prokerala\Api\Numerology\Result\Pythagorean\LifeCycleNumber $lifeCycle)
    {
        $this->lifeCycle = $lifeCycle;
    }
    public function getLifeCycleNumber() : \Prokerala\Api\Numerology\Result\Pythagorean\LifeCycleNumber
    {
        return $this->lifeCycle;
    }
}
