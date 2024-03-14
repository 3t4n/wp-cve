<?php

declare (strict_types=1);
namespace DhlVendor\Octolize\DhlExpress\RestApi\ValueObjects;

class Package
{
    private float $weight;
    private int $height;
    private int $length;
    private int $width;
    private ?string $typeCode;
    public function __construct(float $weight, int $height, int $length, int $width, ?string $typeCode = null)
    {
        $this->width = $width;
        $this->length = $length;
        $this->height = $height;
        $this->weight = $weight;
        $this->typeCode = $typeCode;
    }
    public function getWeight() : float
    {
        return $this->weight;
    }
    public function getHeight() : int
    {
        return $this->height;
    }
    public function getLength() : int
    {
        return $this->length;
    }
    public function getWidth() : int
    {
        return $this->width;
    }
    public function getTypeCode() : ?string
    {
        return $this->typeCode;
    }
    public function setTypeCode(?string $typeCode) : void
    {
        $this->typeCode = $typeCode;
    }
}
