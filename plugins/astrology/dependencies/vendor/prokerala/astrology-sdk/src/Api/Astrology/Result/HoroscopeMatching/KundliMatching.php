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

use Prokerala\Api\Astrology\Result\HoroscopeMatching\GunaMilan\GunaMilan;
use Prokerala\Api\Astrology\Result\ResultInterface;
use Prokerala\Api\Astrology\Traits\Result\RawResponseTrait;
final class KundliMatching implements ResultInterface
{
    use RawResponseTrait;
    /**
     * @var \Prokerala\Api\Astrology\Result\HoroscopeMatching\ProfileInfo
     */
    private $girlInfo;
    /**
     * @var \Prokerala\Api\Astrology\Result\HoroscopeMatching\ProfileInfo
     */
    private $boyInfo;
    /**
     * @var \Prokerala\Api\Astrology\Result\HoroscopeMatching\Message
     */
    private $message;
    /**
     * @var \Prokerala\Api\Astrology\Result\HoroscopeMatching\GunaMilan\GunaMilan
     */
    private $gunaMilan;
    public function __construct(\Prokerala\Api\Astrology\Result\HoroscopeMatching\ProfileInfo $girlInfo, \Prokerala\Api\Astrology\Result\HoroscopeMatching\ProfileInfo $boyInfo, \Prokerala\Api\Astrology\Result\HoroscopeMatching\Message $message, GunaMilan $gunaMilan)
    {
        $this->girlInfo = $girlInfo;
        $this->boyInfo = $boyInfo;
        $this->message = $message;
        $this->gunaMilan = $gunaMilan;
    }
    public function getGirlInfo() : \Prokerala\Api\Astrology\Result\HoroscopeMatching\ProfileInfo
    {
        return $this->girlInfo;
    }
    public function getBoyInfo() : \Prokerala\Api\Astrology\Result\HoroscopeMatching\ProfileInfo
    {
        return $this->boyInfo;
    }
    public function getMessage() : \Prokerala\Api\Astrology\Result\HoroscopeMatching\Message
    {
        return $this->message;
    }
    public function getGunaMilan() : GunaMilan
    {
        return $this->gunaMilan;
    }
}
