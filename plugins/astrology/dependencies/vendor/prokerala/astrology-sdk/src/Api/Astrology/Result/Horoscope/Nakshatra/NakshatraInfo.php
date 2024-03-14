<?php

/*
 * This file is part of Prokerala Astrology API PHP SDK
 *
 * © Ennexa Technologies <info@ennexa.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */
namespace Prokerala\Api\Astrology\Result\Horoscope\Nakshatra;

final class NakshatraInfo
{
    /**
     * @var string
     */
    private $deity;
    /**
     * @var string
     */
    private $ganam;
    /**
     * @var string
     */
    private $symbol;
    /**
     * @var string
     */
    private $animalSign;
    /**
     * @var string
     */
    private $nadi;
    /**
     * @var string
     */
    private $color;
    /**
     * @var string
     */
    private $bestDirection;
    /**
     * @var string
     */
    private $syllables;
    /**
     * @var string
     */
    private $birthStone;
    /**
     * @var string
     */
    private $gender;
    /**
     * @var string
     */
    private $planet;
    /**
     * @var string
     */
    private $enemyYoni;
    public function __construct(string $deity, string $ganam, string $symbol, string $animalSign, string $nadi, string $color, string $bestDirection, string $syllables, string $birthStone, string $gender, string $planet, string $enemyYoni)
    {
        $this->deity = $deity;
        $this->ganam = $ganam;
        $this->symbol = $symbol;
        $this->animalSign = $animalSign;
        $this->nadi = $nadi;
        $this->color = $color;
        $this->bestDirection = $bestDirection;
        $this->syllables = $syllables;
        $this->birthStone = $birthStone;
        $this->gender = $gender;
        $this->planet = $planet;
        $this->enemyYoni = $enemyYoni;
    }
    public function getDeity() : string
    {
        return $this->deity;
    }
    public function getGanam() : string
    {
        return $this->ganam;
    }
    public function getSymbol() : string
    {
        return $this->symbol;
    }
    public function getAnimalSign() : string
    {
        return $this->animalSign;
    }
    public function getNadi() : string
    {
        return $this->nadi;
    }
    public function getColor() : string
    {
        return $this->color;
    }
    public function getBestDirection() : string
    {
        return $this->bestDirection;
    }
    public function getSyllables() : string
    {
        return $this->syllables;
    }
    public function getBirthStone() : string
    {
        return $this->birthStone;
    }
    public function getGender() : string
    {
        return $this->gender;
    }
    public function getPlanet() : string
    {
        return $this->planet;
    }
    public function getEnemyYoni() : string
    {
        return $this->enemyYoni;
    }
}
