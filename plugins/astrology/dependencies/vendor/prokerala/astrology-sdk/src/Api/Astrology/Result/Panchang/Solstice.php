<?php

/*
 * This file is part of Prokerala Astrology API PHP SDK
 *
 * © Ennexa Technologies <info@ennexa.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */
namespace Prokerala\Api\Astrology\Result\Panchang;

use Prokerala\Api\Astrology\Result\ResultInterface;
use Prokerala\Api\Astrology\Traits\Result\RawResponseTrait;
final class Solstice implements ResultInterface
{
    use RawResponseTrait;
    /**
     * @var \Prokerala\Api\Astrology\Result\Panchang\SolsticeResult
     */
    private $solstice;
    public function __construct(\Prokerala\Api\Astrology\Result\Panchang\SolsticeResult $solstice)
    {
        $this->solstice = $solstice;
    }
    public function getDishaShool() : \Prokerala\Api\Astrology\Result\Panchang\SolsticeResult
    {
        return $this->solstice;
    }
}
