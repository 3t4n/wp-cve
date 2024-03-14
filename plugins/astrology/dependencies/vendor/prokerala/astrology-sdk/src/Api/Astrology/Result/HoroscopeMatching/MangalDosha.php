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

final class MangalDosha
{
    /**
     * @var bool
     */
    private $hasDosha;
    /**
     * @var bool
     */
    private $hasException;
    /**
     * @var string|null
     */
    private $doshaType;
    /**
     * @var string
     */
    private $description;
    /**
     * MangalDosha constructor.
     */
    public function __construct(bool $hasDosha, bool $hasException, ?string $doshaType, string $description)
    {
        $this->hasDosha = $hasDosha;
        $this->hasException = $hasException;
        $this->doshaType = $doshaType;
        $this->description = $description;
    }
    public function hasDosha() : bool
    {
        return $this->hasDosha;
    }
    public function hasException() : bool
    {
        return $this->hasException;
    }
    public function getDoshaType() : ?string
    {
        return $this->doshaType;
    }
    public function getDescription() : string
    {
        return $this->description;
    }
}
