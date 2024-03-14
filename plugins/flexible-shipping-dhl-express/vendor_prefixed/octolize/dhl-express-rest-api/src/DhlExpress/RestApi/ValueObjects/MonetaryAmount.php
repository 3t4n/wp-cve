<?php

declare (strict_types=1);
namespace DhlVendor\Octolize\DhlExpress\RestApi\ValueObjects;

use DhlVendor\Octolize\DhlExpress\RestApi\Exceptions\InvalidAddressException;
class MonetaryAmount
{
    private const ALLOWED_TYPE_CODES = ["declaredValue", "insuredValue"];
    private string $typeCode;
    private float $value;
    private string $currency;
    public function __construct(string $typeCode, float $value, string $currency)
    {
        $this->typeCode = $typeCode;
        $this->value = $value;
        $this->currency = $currency;
        $this->validateData();
    }
    public function getValue() : float
    {
        return $this->value;
    }
    public function getAsArray() : array
    {
        return ['typeCode' => $this->typeCode, 'value' => $this->value, 'currency' => $this->currency];
    }
    protected function validateData() : void
    {
        if (!\in_array($this->typeCode, self::ALLOWED_TYPE_CODES, \true)) {
            $errMsg = "Incorrect account type code. Allowed codes: ";
            $errMsg .= \implode(', ', self::ALLOWED_TYPE_CODES);
            throw new \DhlVendor\Octolize\DhlExpress\RestApi\Exceptions\InvalidAddressException($errMsg);
        }
    }
}
