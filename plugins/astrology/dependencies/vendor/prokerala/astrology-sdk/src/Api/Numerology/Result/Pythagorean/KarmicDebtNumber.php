<?php

namespace Prokerala\Api\Numerology\Result\Pythagorean;

class KarmicDebtNumber
{
    /**
     * @var string
     */
    private $name;
    /**
     * @var Number[]
     */
    private $debts;
    /**
     * @param Number[] $debts
     */
    public function __construct(string $name, array $debts)
    {
        $this->name = $name;
        $this->debts = $debts;
    }
    public function getName() : string
    {
        return $this->name;
    }
    /**
     * @return Number[]
     */
    public function getDebts() : array
    {
        return $this->debts;
    }
}
