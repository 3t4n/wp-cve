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

use Prokerala\Api\Astrology\Result\HoroscopeMatching\GunaMilan\AdvancedGunaMilan;
use Prokerala\Api\Astrology\Result\ResultInterface;
use Prokerala\Api\Astrology\Traits\Result\RawResponseTrait;
final class AdvancedKundliMatching implements ResultInterface
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
     * @var \Prokerala\Api\Astrology\Result\HoroscopeMatching\GunaMilan\AdvancedGunaMilan
     */
    private $gunaMilan;
    /**
     * @var \Prokerala\Api\Astrology\Result\HoroscopeMatching\MangalDosha
     */
    private $girlMangalDoshaDetails;
    /**
     * @var \Prokerala\Api\Astrology\Result\HoroscopeMatching\MangalDosha
     */
    private $boyMangalDoshaDetails;
    /** @var string[] */
    private $exceptions;
    /**
     * AdvancedKundliMatching constructor.
     *
     * @param string[] $exceptions
     */
    public function __construct(\Prokerala\Api\Astrology\Result\HoroscopeMatching\ProfileInfo $girlInfo, \Prokerala\Api\Astrology\Result\HoroscopeMatching\ProfileInfo $boyInfo, \Prokerala\Api\Astrology\Result\HoroscopeMatching\Message $message, AdvancedGunaMilan $gunaMilan, \Prokerala\Api\Astrology\Result\HoroscopeMatching\MangalDosha $girlMangalDoshaDetails, \Prokerala\Api\Astrology\Result\HoroscopeMatching\MangalDosha $boyMangalDoshaDetails, array $exceptions)
    {
        $this->girlInfo = $girlInfo;
        $this->boyInfo = $boyInfo;
        $this->message = $message;
        $this->gunaMilan = $gunaMilan;
        $this->girlMangalDoshaDetails = $girlMangalDoshaDetails;
        $this->boyMangalDoshaDetails = $boyMangalDoshaDetails;
        $this->exceptions = $exceptions;
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
    public function getGunaMilan() : AdvancedGunaMilan
    {
        return $this->gunaMilan;
    }
    public function getGirlMangalDoshaDetails() : \Prokerala\Api\Astrology\Result\HoroscopeMatching\MangalDosha
    {
        return $this->girlMangalDoshaDetails;
    }
    public function getBoyMangalDoshaDetails() : \Prokerala\Api\Astrology\Result\HoroscopeMatching\MangalDosha
    {
        return $this->boyMangalDoshaDetails;
    }
    /**
     * @return string[]
     */
    public function getExceptions() : array
    {
        return $this->exceptions;
    }
}
