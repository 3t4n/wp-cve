<?php

declare(strict_types=1);

namespace WpifyWoo\PacketeraSDK\StructType;

use InvalidArgumentException;
use WsdlToPhp\PackageBase\AbstractStructBase;

/**
 * This class stands for Attribute StructType
 * @subpackage Structs
 */
class Attribute extends AbstractStructBase
{
    /**
     * The key
     * Meta information extracted from the WSDL
     * - base: xsd:string
     * - maxLength: 32
     * @var string|null
     */
    protected ?string $key = null;
    /**
     * The value
     * Meta information extracted from the WSDL
     * - base: xsd:string
     * - maxLength: 256
     * @var string|null
     */
    protected ?string $value = null;
    /**
     * Constructor method for Attribute
     * @uses Attribute::setKey()
     * @uses Attribute::setValue()
     * @param string $key
     * @param string $value
     */
    public function __construct(?string $key = null, ?string $value = null)
    {
        $this
            ->setKey($key)
            ->setValue($value);
    }
    /**
     * Get key value
     * @return string|null
     */
    public function getKey(): ?string
    {
        return $this->key;
    }
    /**
     * Set key value
     * @param string $key
     * @return \WpifyWoo\PacketeraSDK\StructType\Attribute
     */
    public function setKey(?string $key = null): self
    {
        // validation for constraint: string
        if (!is_null($key) && !is_string($key)) {
            throw new InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($key, true), gettype($key)), __LINE__);
        }
        // validation for constraint: maxLength(32)
        if (!is_null($key) && mb_strlen((string) $key) > 32) {
            throw new InvalidArgumentException(sprintf('Invalid length of %s, the number of characters/octets contained by the literal must be less than or equal to 32', mb_strlen((string) $key)), __LINE__);
        }
        $this->key = $key;
        
        return $this;
    }
    /**
     * Get value value
     * @return string|null
     */
    public function getValue(): ?string
    {
        return $this->value;
    }
    /**
     * Set value value
     * @param string $value
     * @return \WpifyWoo\PacketeraSDK\StructType\Attribute
     */
    public function setValue(?string $value = null): self
    {
        // validation for constraint: string
        if (!is_null($value) && !is_string($value)) {
            throw new InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($value, true), gettype($value)), __LINE__);
        }
        // validation for constraint: maxLength(256)
        if (!is_null($value) && mb_strlen((string) $value) > 256) {
            throw new InvalidArgumentException(sprintf('Invalid length of %s, the number of characters/octets contained by the literal must be less than or equal to 256', mb_strlen((string) $value)), __LINE__);
        }
        $this->value = $value;
        
        return $this;
    }
}
