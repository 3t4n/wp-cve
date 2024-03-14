<?php

declare (strict_types=1);
namespace DhlVendor\Octolize\DhlExpress\RestApi\ValueObjects;

use DhlVendor\Octolize\DhlExpress\RestApi\Exceptions\InvalidAddressException;
class RateAddress
{
    protected string $countryCode;
    protected string $postalCode;
    protected string $cityName;
    /**
     * @throws InvalidAddressException
     */
    public function __construct(string $countryCode, string $postalCode, string $cityName)
    {
        $this->cityName = $cityName;
        $this->postalCode = $postalCode;
        $this->countryCode = $countryCode;
        $this->countryCode = \strtoupper($this->countryCode);
        $this->validateData();
    }
    public function getCountryCode() : string
    {
        return $this->countryCode;
    }
    public function getPostalCode() : string
    {
        return $this->postalCode;
    }
    public function getCityName() : string
    {
        return $this->cityName;
    }
    /**
     * @throws InvalidAddressException
     */
    protected function validateData() : void
    {
        if (\strlen($this->countryCode) !== 2) {
            throw new \DhlVendor\Octolize\DhlExpress\RestApi\Exceptions\InvalidAddressException("Country Code must be 2 characters long. Entered: {$this->countryCode}");
        }
        if (\strlen($this->cityName) === 0) {
            throw new \DhlVendor\Octolize\DhlExpress\RestApi\Exceptions\InvalidAddressException("City name must not be empty. Entered: {$this->cityName}");
        }
    }
    public function getAsArray() : array
    {
        $result = ['countryCode' => $this->countryCode, 'postalCode' => $this->postalCode, 'cityName' => $this->cityName];
        return $result;
    }
}
