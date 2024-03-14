<?php

namespace Prokerala\Api\Numerology\Result\Pythagorean;

use Prokerala\Api\Astrology\Result\ResultInterface;
use Prokerala\Api\Astrology\Traits\Result\RawResponseTrait;
class UniversalYear implements ResultInterface
{
    use RawResponseTrait;
    /**
     * @var \Prokerala\Api\Numerology\Result\Pythagorean\UniversalYearNumber
     */
    private $universalYearNumber;
    public function __construct(\Prokerala\Api\Numerology\Result\Pythagorean\UniversalYearNumber $universalYearNumber)
    {
        $this->universalYearNumber = $universalYearNumber;
    }
    public function getUniversalYearNumber() : \Prokerala\Api\Numerology\Result\Pythagorean\UniversalYearNumber
    {
        return $this->universalYearNumber;
    }
}
