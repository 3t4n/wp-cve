<?php

namespace Prokerala\Api\Numerology\Result\Chaldean;

class Number
{
    /**
     * @var string
     */
    private $name;
    /**
     * @var int|null
     */
    private $number;
    /**
     * @var string
     */
    private $description;
    public function __construct(string $name, ?int $number, string $description)
    {
        $this->name = $name;
        $this->number = $number;
        $this->description = $description;
    }
    public function getName() : string
    {
        return $this->name;
    }
    public function getNumber() : ?int
    {
        return $this->number;
    }
    public function getDescription() : string
    {
        return $this->description;
    }
}
