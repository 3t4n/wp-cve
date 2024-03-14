<?php

namespace Prokerala\Api\Numerology\Result\Pythagorean;

class NameChart
{
    /**
     * @var CharacterValues[]
     */
    private $firstName;
    /**
     * @var CharacterValues[]
     */
    private $lastName;
    /**
     * @var CharacterValues[]
     */
    private $middleName;
    /**
     * @param CharacterValues[] $firstName
     * @param CharacterValues[] $lastName
     * @param CharacterValues[] $middleName
     */
    public function __construct(array $firstName, array $lastName, array $middleName)
    {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->middleName = $middleName;
    }
    /**
     * @return CharacterValues[]
     */
    public function getFirstName() : array
    {
        return $this->firstName;
    }
    /**
     * @return CharacterValues[]
     */
    public function getLastName() : array
    {
        return $this->lastName;
    }
    /**
     * @return CharacterValues[]
     */
    public function getMiddleName() : array
    {
        return $this->middleName;
    }
}
