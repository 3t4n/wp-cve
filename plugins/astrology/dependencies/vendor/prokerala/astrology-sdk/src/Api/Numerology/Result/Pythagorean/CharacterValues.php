<?php

namespace Prokerala\Api\Numerology\Result\Pythagorean;

class CharacterValues
{
    /**
     * @var string
     */
    private $character;
    /**
     * @var int
     */
    private $number;
    public function __construct(string $character, int $number)
    {
        $this->character = $character;
        $this->number = $number;
    }
    public function getCharacter() : string
    {
        return $this->character;
    }
    public function getNumber() : int
    {
        return $this->number;
    }
}
