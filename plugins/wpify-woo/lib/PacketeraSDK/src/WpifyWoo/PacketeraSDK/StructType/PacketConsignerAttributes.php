<?php

declare(strict_types=1);

namespace WpifyWoo\PacketeraSDK\StructType;

use InvalidArgumentException;
use WsdlToPhp\PackageBase\AbstractStructBase;

/**
 * This class stands for PacketConsignerAttributes StructType
 * @subpackage Structs
 */
class PacketConsignerAttributes extends AbstractStructBase
{
    /**
     * The id
     * @var string|null
     */
    protected ?string $id = null;
    /**
     * The consignerEmail
     * Meta information extracted from the WSDL
     * - base: xsd:string
     * - pattern: |.*@.*\..*
     * @var string|null
     */
    protected ?string $consignerEmail = null;
    /**
     * The consignerPhone
     * Meta information extracted from the WSDL
     * - base: xsd:string
     * - minOccurs: 0
     * - pattern: \s*(\+?42[01])?\s*[0-9]{3}\s*[0-9]{3}\s*[0-9]{3}\s*
     * @var string|null
     */
    protected ?string $consignerPhone = null;
    /**
     * The consignerCountry
     * Meta information extracted from the WSDL
     * - base: xsd:string
     * - length: 2
     * - minOccurs: 0
     * @var string|null
     */
    protected ?string $consignerCountry = null;
    /**
     * Constructor method for PacketConsignerAttributes
     * @uses PacketConsignerAttributes::setId()
     * @uses PacketConsignerAttributes::setConsignerEmail()
     * @uses PacketConsignerAttributes::setConsignerPhone()
     * @uses PacketConsignerAttributes::setConsignerCountry()
     * @param string $id
     * @param string $consignerEmail
     * @param string $consignerPhone
     * @param string $consignerCountry
     */
    public function __construct(?string $id = null, ?string $consignerEmail = null, ?string $consignerPhone = null, ?string $consignerCountry = null)
    {
        $this
            ->setId($id)
            ->setConsignerEmail($consignerEmail)
            ->setConsignerPhone($consignerPhone)
            ->setConsignerCountry($consignerCountry);
    }
    /**
     * Get id value
     * @return string|null
     */
    public function getId(): ?string
    {
        return $this->id;
    }
    /**
     * Set id value
     * @param string $id
     * @return \WpifyWoo\PacketeraSDK\StructType\PacketConsignerAttributes
     */
    public function setId(?string $id = null): self
    {
        // validation for constraint: string
        if (!is_null($id) && !is_string($id)) {
            throw new InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($id, true), gettype($id)), __LINE__);
        }
        $this->id = $id;
        
        return $this;
    }
    /**
     * Get consignerEmail value
     * @return string|null
     */
    public function getConsignerEmail(): ?string
    {
        return $this->consignerEmail;
    }
    /**
     * Set consignerEmail value
     * @param string $consignerEmail
     * @return \WpifyWoo\PacketeraSDK\StructType\PacketConsignerAttributes
     */
    public function setConsignerEmail(?string $consignerEmail = null): self
    {
        // validation for constraint: string
        if (!is_null($consignerEmail) && !is_string($consignerEmail)) {
            throw new InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($consignerEmail, true), gettype($consignerEmail)), __LINE__);
        }
        // validation for constraint: pattern(|.*@.*\..*)
        if (!is_null($consignerEmail) && !preg_match('/|.*@.*\\..*/', $consignerEmail)) {
            throw new InvalidArgumentException(sprintf('Invalid value %s, please provide a literal that is among the set of character sequences denoted by the regular expression /|.*@.*\\..*/', var_export($consignerEmail, true)), __LINE__);
        }
        $this->consignerEmail = $consignerEmail;
        
        return $this;
    }
    /**
     * Get consignerPhone value
     * @return string|null
     */
    public function getConsignerPhone(): ?string
    {
        return $this->consignerPhone;
    }
    /**
     * Set consignerPhone value
     * @param string $consignerPhone
     * @return \WpifyWoo\PacketeraSDK\StructType\PacketConsignerAttributes
     */
    public function setConsignerPhone(?string $consignerPhone = null): self
    {
        // validation for constraint: string
        if (!is_null($consignerPhone) && !is_string($consignerPhone)) {
            throw new InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($consignerPhone, true), gettype($consignerPhone)), __LINE__);
        }
        // validation for constraint: pattern(\s*(\+?42[01])?\s*[0-9]{3}\s*[0-9]{3}\s*[0-9]{3}\s*)
        if (!is_null($consignerPhone) && !preg_match('/\\s*(\\+?42[01])?\\s*[0-9]{3}\\s*[0-9]{3}\\s*[0-9]{3}\\s*/', $consignerPhone)) {
            throw new InvalidArgumentException(sprintf('Invalid value %s, please provide a literal that is among the set of character sequences denoted by the regular expression /\\s*(\\+?42[01])?\\s*[0-9]{3}\\s*[0-9]{3}\\s*[0-9]{3}\\s*/', var_export($consignerPhone, true)), __LINE__);
        }
        $this->consignerPhone = $consignerPhone;
        
        return $this;
    }
    /**
     * Get consignerCountry value
     * @return string|null
     */
    public function getConsignerCountry(): ?string
    {
        return $this->consignerCountry;
    }
    /**
     * Set consignerCountry value
     * @param string $consignerCountry
     * @return \WpifyWoo\PacketeraSDK\StructType\PacketConsignerAttributes
     */
    public function setConsignerCountry(?string $consignerCountry = null): self
    {
        // validation for constraint: string
        if (!is_null($consignerCountry) && !is_string($consignerCountry)) {
            throw new InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($consignerCountry, true), gettype($consignerCountry)), __LINE__);
        }
        // validation for constraint: length(2)
        if (!is_null($consignerCountry) && mb_strlen((string) $consignerCountry) !== 2) {
            throw new InvalidArgumentException(sprintf('Invalid length of %s, the number of characters/octets contained by the literal must be equal to 2', mb_strlen((string) $consignerCountry)), __LINE__);
        }
        $this->consignerCountry = $consignerCountry;
        
        return $this;
    }
}
