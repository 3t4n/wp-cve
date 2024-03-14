<?php

/*
 * This file is part of Prokerala Astrology API PHP SDK
 *
 * © Ennexa Technologies <info@ennexa.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */
namespace Prokerala\Api\Astrology\Result\HoroscopeMatching;

use Prokerala\Api\Astrology\Result\Horoscope\Papasamyam;
use Prokerala\Api\Astrology\Result\ResultInterface;
use Prokerala\Api\Astrology\Traits\Result\RawResponseTrait;
final class PapaSamyamCheck implements ResultInterface
{
    use RawResponseTrait;
    /**
     * @var \Prokerala\Api\Astrology\Result\Horoscope\Papasamyam
     */
    private $girlPapasamyam;
    /**
     * @var \Prokerala\Api\Astrology\Result\Horoscope\Papasamyam
     */
    private $boyPapasamyam;
    /**
     * @var \Prokerala\Api\Astrology\Result\HoroscopeMatching\Message
     */
    private $message;
    public function __construct(Papasamyam $girlPapasamyam, Papasamyam $boyPapasamyam, \Prokerala\Api\Astrology\Result\HoroscopeMatching\Message $message)
    {
        $this->girlPapasamyam = $girlPapasamyam;
        $this->boyPapasamyam = $boyPapasamyam;
        $this->message = $message;
    }
    public function getGirlPapasamyam() : Papasamyam
    {
        return $this->girlPapasamyam;
    }
    public function getBoyPapasamyam() : Papasamyam
    {
        return $this->boyPapasamyam;
    }
    public function getMessage() : \Prokerala\Api\Astrology\Result\HoroscopeMatching\Message
    {
        return $this->message;
    }
}
