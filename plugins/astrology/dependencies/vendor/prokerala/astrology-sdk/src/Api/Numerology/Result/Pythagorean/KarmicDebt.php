<?php

namespace Prokerala\Api\Numerology\Result\Pythagorean;

use Prokerala\Api\Astrology\Result\ResultInterface;
use Prokerala\Api\Astrology\Traits\Result\RawResponseTrait;
class KarmicDebt implements ResultInterface
{
    use RawResponseTrait;
    /**
     * @var \Prokerala\Api\Numerology\Result\Pythagorean\KarmicDebtNumber
     */
    private $karmicDebtNumber;
    public function __construct(\Prokerala\Api\Numerology\Result\Pythagorean\KarmicDebtNumber $karmicDebtNumber)
    {
        $this->karmicDebtNumber = $karmicDebtNumber;
    }
    public function getKarmicDebtNumber() : \Prokerala\Api\Numerology\Result\Pythagorean\KarmicDebtNumber
    {
        return $this->karmicDebtNumber;
    }
}
