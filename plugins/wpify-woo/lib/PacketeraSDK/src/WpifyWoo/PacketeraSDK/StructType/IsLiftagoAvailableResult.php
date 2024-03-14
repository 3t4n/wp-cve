<?php

declare(strict_types=1);

namespace WpifyWoo\PacketeraSDK\StructType;

use InvalidArgumentException;
use WsdlToPhp\PackageBase\AbstractStructBase;

/**
 * This class stands for isLiftagoAvailableResult StructType
 * @subpackage Structs
 */
class IsLiftagoAvailableResult extends AbstractStructBase
{
    /**
     * The key
     * @var string|null
     */
    protected ?string $key = null;
    /**
     * The value
     * @var bool|null
     */
    protected ?bool $value = null;
    /**
     * Constructor method for isLiftagoAvailableResult
     * @uses IsLiftagoAvailableResult::setKey()
     * @uses IsLiftagoAvailableResult::setValue()
     * @param string $key
     * @param bool $value
     */
    public function __construct(?string $key = null, ?bool $value = null)
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
     * @return \WpifyWoo\PacketeraSDK\StructType\IsLiftagoAvailableResult
     */
    public function setKey(?string $key = null): self
    {
        // validation for constraint: string
        if (!is_null($key) && !is_string($key)) {
            throw new InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($key, true), gettype($key)), __LINE__);
        }
        $this->key = $key;
        
        return $this;
    }
    /**
     * Get value value
     * @return bool|null
     */
    public function getValue(): ?bool
    {
        return $this->value;
    }
    /**
     * Set value value
     * @param bool $value
     * @return \WpifyWoo\PacketeraSDK\StructType\IsLiftagoAvailableResult
     */
    public function setValue(?bool $value = null): self
    {
        // validation for constraint: boolean
        if (!is_null($value) && !is_bool($value)) {
            throw new InvalidArgumentException(sprintf('Invalid value %s, please provide a bool, %s given', var_export($value, true), gettype($value)), __LINE__);
        }
        $this->value = $value;
        
        return $this;
    }
}
