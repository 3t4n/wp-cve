<?php

declare(strict_types=1);

namespace WpifyWoo\PacketeraSDK\StructType;

use InvalidArgumentException;
use WsdlToPhp\PackageBase\AbstractStructBase;

/**
 * This class stands for Contact StructType
 * @subpackage Structs
 */
class Contact extends AbstractStructBase
{
    /**
     * The name
     * Meta information extracted from the WSDL
     * - minOccurs: 0
     * @var string|null
     */
    protected ?string $name = null;
    /**
     * The surname
     * @var string|null
     */
    protected ?string $surname = null;
    /**
     * The company
     * Meta information extracted from the WSDL
     * - minOccurs: 0
     * @var string|null
     */
    protected ?string $company = null;
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
     * The phone
     * Meta information extracted from the WSDL
     * - base: xsd:string
     * - minOccurs: 0
     * - pattern: \s*(\+?42[01])?\s*[0-9]{3}\s*[0-9]{3}\s*[0-9]{3}\s*
     * @var string|null
     */
    protected ?string $phone = null;
    /**
     * The email
     * Meta information extracted from the WSDL
     * - base: xsd:string
     * - minOccurs: 0
     * - pattern: |.*@.*\..*
     * @var string|null
     */
    protected ?string $email = null;
    /**
     * The note
     * Meta information extracted from the WSDL
     * - minOccurs: 0
     * @var string|null
     */
    protected ?string $note = null;
    /**
     * Constructor method for Contact
     * @uses Contact::setName()
     * @uses Contact::setSurname()
     * @uses Contact::setCompany()
     * @uses Contact::setStreet()
     * @uses Contact::setHouseNumber()
     * @uses Contact::setCity()
     * @uses Contact::setZip()
     * @uses Contact::setCountryCode()
     * @uses Contact::setPhone()
     * @uses Contact::setEmail()
     * @uses Contact::setNote()
     * @param string $name
     * @param string $surname
     * @param string $company
     * @param string $street
     * @param string $houseNumber
     * @param string $city
     * @param string $zip
     * @param string $countryCode
     * @param string $phone
     * @param string $email
     * @param string $note
     */
    public function __construct(?string $name = null, ?string $surname = null, ?string $company = null, ?string $street = null, ?string $houseNumber = null, ?string $city = null, ?string $zip = null, ?string $countryCode = null, ?string $phone = null, ?string $email = null, ?string $note = null)
    {
        $this
            ->setName($name)
            ->setSurname($surname)
            ->setCompany($company)
            ->setStreet($street)
            ->setHouseNumber($houseNumber)
            ->setCity($city)
            ->setZip($zip)
            ->setCountryCode($countryCode)
            ->setPhone($phone)
            ->setEmail($email)
            ->setNote($note);
    }
    /**
     * Get name value
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }
    /**
     * Set name value
     * @param string $name
     * @return \WpifyWoo\PacketeraSDK\StructType\Contact
     */
    public function setName(?string $name = null): self
    {
        // validation for constraint: string
        if (!is_null($name) && !is_string($name)) {
            throw new InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($name, true), gettype($name)), __LINE__);
        }
        $this->name = $name;
        
        return $this;
    }
    /**
     * Get surname value
     * @return string|null
     */
    public function getSurname(): ?string
    {
        return $this->surname;
    }
    /**
     * Set surname value
     * @param string $surname
     * @return \WpifyWoo\PacketeraSDK\StructType\Contact
     */
    public function setSurname(?string $surname = null): self
    {
        // validation for constraint: string
        if (!is_null($surname) && !is_string($surname)) {
            throw new InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($surname, true), gettype($surname)), __LINE__);
        }
        $this->surname = $surname;
        
        return $this;
    }
    /**
     * Get company value
     * @return string|null
     */
    public function getCompany(): ?string
    {
        return $this->company;
    }
    /**
     * Set company value
     * @param string $company
     * @return \WpifyWoo\PacketeraSDK\StructType\Contact
     */
    public function setCompany(?string $company = null): self
    {
        // validation for constraint: string
        if (!is_null($company) && !is_string($company)) {
            throw new InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($company, true), gettype($company)), __LINE__);
        }
        $this->company = $company;
        
        return $this;
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
     * @return \WpifyWoo\PacketeraSDK\StructType\Contact
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
     * @return \WpifyWoo\PacketeraSDK\StructType\Contact
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
     * @return \WpifyWoo\PacketeraSDK\StructType\Contact
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
     * @return \WpifyWoo\PacketeraSDK\StructType\Contact
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
     * @return \WpifyWoo\PacketeraSDK\StructType\Contact
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
    /**
     * Get phone value
     * @return string|null
     */
    public function getPhone(): ?string
    {
        return $this->phone;
    }
    /**
     * Set phone value
     * @param string $phone
     * @return \WpifyWoo\PacketeraSDK\StructType\Contact
     */
    public function setPhone(?string $phone = null): self
    {
        // validation for constraint: string
        if (!is_null($phone) && !is_string($phone)) {
            throw new InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($phone, true), gettype($phone)), __LINE__);
        }
        // validation for constraint: pattern(\s*(\+?42[01])?\s*[0-9]{3}\s*[0-9]{3}\s*[0-9]{3}\s*)
        if (!is_null($phone) && !preg_match('/\\s*(\\+?42[01])?\\s*[0-9]{3}\\s*[0-9]{3}\\s*[0-9]{3}\\s*/', $phone)) {
            throw new InvalidArgumentException(sprintf('Invalid value %s, please provide a literal that is among the set of character sequences denoted by the regular expression /\\s*(\\+?42[01])?\\s*[0-9]{3}\\s*[0-9]{3}\\s*[0-9]{3}\\s*/', var_export($phone, true)), __LINE__);
        }
        $this->phone = $phone;
        
        return $this;
    }
    /**
     * Get email value
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }
    /**
     * Set email value
     * @param string $email
     * @return \WpifyWoo\PacketeraSDK\StructType\Contact
     */
    public function setEmail(?string $email = null): self
    {
        // validation for constraint: string
        if (!is_null($email) && !is_string($email)) {
            throw new InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($email, true), gettype($email)), __LINE__);
        }
        // validation for constraint: pattern(|.*@.*\..*)
        if (!is_null($email) && !preg_match('/|.*@.*\\..*/', $email)) {
            throw new InvalidArgumentException(sprintf('Invalid value %s, please provide a literal that is among the set of character sequences denoted by the regular expression /|.*@.*\\..*/', var_export($email, true)), __LINE__);
        }
        $this->email = $email;
        
        return $this;
    }
    /**
     * Get note value
     * @return string|null
     */
    public function getNote(): ?string
    {
        return $this->note;
    }
    /**
     * Set note value
     * @param string $note
     * @return \WpifyWoo\PacketeraSDK\StructType\Contact
     */
    public function setNote(?string $note = null): self
    {
        // validation for constraint: string
        if (!is_null($note) && !is_string($note)) {
            throw new InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($note, true), gettype($note)), __LINE__);
        }
        $this->note = $note;
        
        return $this;
    }
}
