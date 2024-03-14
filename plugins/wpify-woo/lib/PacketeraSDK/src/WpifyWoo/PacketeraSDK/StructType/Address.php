<?php

declare(strict_types=1);

namespace WpifyWoo\PacketeraSDK\StructType;

use InvalidArgumentException;
use WsdlToPhp\PackageBase\AbstractStructBase;

/**
 * This class stands for Address StructType
 * @subpackage Structs
 */
class Address extends AbstractStructBase
{
    /**
     * The street
     * @var string|null
     */
    protected ?string $street = null;
    /**
     * The houseNumber
     * @var string|null
     */
    protected ?string $houseNumber = null;
    /**
     * The city
     * @var string|null
     */
    protected ?string $city = null;
    /**
     * The zip
     * Meta information extracted from the WSDL
     * - base: xsd:string
     * - pattern: \s*[0-9]{3} ?[0-9]{2}\s*
     * @var string|null
     */
    protected ?string $zip = null;
    /**
     * The countryCode
     * Meta information extracted from the WSDL
     * - base: xsd:string
     * - length: 2
     * @var string|null
     */
    protected ?string $countryCode = null;
    /**
     * Constructor method for Address
     * @uses Address::setStreet()
     * @uses Address::setHouseNumber()
     * @uses Address::setCity()
     * @uses Address::setZip()
     * @uses Address::setCountryCode()
     * @param string $street
     * @param string $houseNumber
     * @param string $city
     * @param string $zip
     * @param string $countryCode
     */
    public function __construct(?string $street = null, ?string $houseNumber = null, ?string $city = null, ?string $zip = null, ?string $countryCode = null)
    {
        $this
            ->setStreet($street)
            ->setHouseNumber($houseNumber)
            ->setCity($city)
            ->setZip($zip)
            ->setCountryCode($countryCode);
    }
    /**
     * Get street value
     * @return string|null
     */
    public function getStreet(): ?string
    {
        return $this->street;
    }
    /**
     * Set street value
     * @param string $street
     * @return \WpifyWoo\PacketeraSDK\StructType\Address
     */
    public function setStreet(?string $street = null): self
    {
        // validation for constraint: string
        if (!is_null($street) && !is_string($street)) {
            throw new InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($street, true), gettype($street)), __LINE__);
        }
        $this->street = $street;
        
        return $this;
    }
    /**
     * Get houseNumber value
     * @return string|null
     */
    public function getHouseNumber(): ?string
    {
        return $this->houseNumber;
    }
    /**
     * Set houseNumber value
     * @param string $houseNumber
     * @return \WpifyWoo\PacketeraSDK\StructType\Address
     */
    public function setHouseNumber(?string $houseNumber = null): self
    {
        // validation for constraint: string
        if (!is_null($houseNumber) && !is_string($houseNumber)) {
            throw new InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($houseNumber, true), gettype($houseNumber)), __LINE__);
        }
        $this->houseNumber = $houseNumber;
        
        return $this;
    }
    /**
     * Get city value
     * @return string|null
     */
    public function getCity(): ?string
    {
        return $this->city;
    }
    /**
     * Set city value
     * @param string $city
     * @return \WpifyWoo\PacketeraSDK\StructType\Address
     */
    public function setCity(?string $city = null): self
    {
        // validation for constraint: string
        if (!is_null($city) && !is_string($city)) {
            throw new InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($city, true), gettype($city)), __LINE__);
        }
        $this->city = $city;
        
        return $this;
    }
    /**
     * Get zip value
     * @return string|null
     */
    public function getZip(): ?string
    {
        return $this->zip;
    }
    /**
     * Set zip value
     * @param string $zip
     * @return \WpifyWoo\PacketeraSDK\StructType\Address
     */
    public function setZip(?string $zip = null): self
    {
        // validation for constraint: string
        if (!is_null($zip) && !is_string($zip)) {
            throw new InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($zip, true), gettype($zip)), __LINE__);
        }
        // validation for constraint: pattern(\s*[0-9]{3} ?[0-9]{2}\s*)
        if (!is_null($zip) && !preg_match('/\\s*[0-9]{3} ?[0-9]{2}\\s*/', $zip)) {
            throw new InvalidArgumentException(sprintf('Invalid value %s, please provide a literal that is among the set of character sequences denoted by the regular expression /\\s*[0-9]{3} ?[0-9]{2}\\s*/', var_export($zip, true)), __LINE__);
        }
        $this->zip = $zip;
        
        return $this;
    }
    /**
     * Get countryCode value
     * @return string|null
     */
    public function getCountryCode(): ?string
    {
        return $this->countryCode;
    }
    /**
     * Set countryCode value
     * @param string $countryCode
     * @return \WpifyWoo\PacketeraSDK\StructType\Address
     */
    public function setCountryCode(?string $countryCode = null): self
    {
        // validation for constraint: string
        if (!is_null($countryCode) && !is_string($countryCode)) {
            throw new InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($countryCode, true), gettype($countryCode)), __LINE__);
        }
        // validation for constraint: length(2)
        if (!is_null($countryCode) && mb_strlen((string) $countryCode) !== 2) {
            throw new InvalidArgumentException(sprintf('Invalid length of %s, the number of characters/octets contained by the literal must be equal to 2', mb_strlen((string) $countryCode)), __LINE__);
        }
        $this->countryCode = $countryCode;
        
        return $this;
    }
}
