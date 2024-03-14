<?php

namespace Prokerala\Api\Numerology\Result\Pythagorean;

use Prokerala\Api\Astrology\Result\ResultInterface;
use Prokerala\Api\Astrology\Traits\Result\RawResponseTrait;
class Birthday implements ResultInterface
{
    use RawResponseTrait;
    /**
     * @var \Prokerala\Api\Numerology\Result\Pythagorean\BirthdayNumber
     */
    private $birthdayNumber;
    public function __construct(\Prokerala\Api\Numerology\Result\Pythagorean\BirthdayNumber $birthdayNumber)
    {
        $this->birthdayNumber = $birthdayNumber;
    }
    public function getBirthdayNumber() : \Prokerala\Api\Numerology\Result\Pythagorean\BirthdayNumber
    {
        return $this->birthdayNumber;
    }
}
