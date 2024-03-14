<?php

declare (strict_types=1);
namespace DhlVendor\Octolize\DhlExpress\RestApi\ValueObjects;

use DhlVendor\Octolize\DhlExpress\RestApi\Exceptions\InvalidAddressException;
class Account
{
    private const ALLOWED_TYPE_CODES = ["shipper", "payer", "duties-taxes"];
    private string $typeCode;
    private string $number;
    public function __construct(string $typeCode, string $number)
    {
        $this->typeCode = $typeCode;
        $this->number = $number;
        $this->validateData();
    }
    public function getNumber() : string
    {
        return $this->number;
    }
    public function getAsArray() : array
    {
        return ['typeCode' => $this->typeCode, 'number' => $this->number];
    }
    protected function validateData() : void
    {
        if (!\in_array($this->typeCode, self::ALLOWED_TYPE_CODES, \true)) {
            $errMsg = "Incorrect account type code. Allowed codes: ";
            $errMsg .= \implode(', ', self::ALLOWED_TYPE_CODES);
            throw new \DhlVendor\Octolize\DhlExpress\RestApi\Exceptions\InvalidAddressException($errMsg);
        }
        if (\strlen($this->number) === 0) {
            throw new \DhlVendor\Octolize\DhlExpress\RestApi\Exceptions\InvalidAddressException("Account number must not be empty.");
        }
    }
}
