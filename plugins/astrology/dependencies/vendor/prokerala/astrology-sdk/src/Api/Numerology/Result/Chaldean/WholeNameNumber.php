<?php

namespace Prokerala\Api\Numerology\Result\Chaldean;

class WholeNameNumber
{
    /**
     * @var string
     */
    private $name;
    /**
     * @var Number[]
     */
    private $energies;
    /**
     * @param Number[] $energies
     */
    public function __construct(string $name, array $energies)
    {
        $this->name = $name;
        $this->energies = $energies;
    }
    public function getName() : string
    {
        return $this->name;
    }
    /**
     * @return Number[]
     */
    public function getEnergies() : array
    {
        return $this->energies;
    }
}
