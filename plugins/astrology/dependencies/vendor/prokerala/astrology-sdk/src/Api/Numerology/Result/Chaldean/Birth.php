<?php

namespace Prokerala\Api\Numerology\Result\Chaldean;

use Prokerala\Api\Astrology\Result\ResultInterface;
use Prokerala\Api\Astrology\Traits\Result\RawResponseTrait;
class Birth implements ResultInterface
{
    use RawResponseTrait;
    /**
     * @var \Prokerala\Api\Numerology\Result\Chaldean\BirthNumber
     */
    private $birthNumber;
    public function __construct(\Prokerala\Api\Numerology\Result\Chaldean\BirthNumber $birthNumber)
    {
        $this->birthNumber = $birthNumber;
    }
    public function getBirthNumber() : \Prokerala\Api\Numerology\Result\Chaldean\BirthNumber
    {
        return $this->birthNumber;
    }
}
