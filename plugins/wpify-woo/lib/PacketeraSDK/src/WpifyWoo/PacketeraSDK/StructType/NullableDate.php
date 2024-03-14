<?php

declare(strict_types=1);

namespace WpifyWoo\PacketeraSDK\StructType;

use InvalidArgumentException;
use WsdlToPhp\PackageBase\AbstractStructBase;

/**
 * This class stands for NullableDate StructType
 * @subpackage Structs
 */
class NullableDate extends AbstractStructBase
{
    /**
     * The date
     * Meta information extracted from the WSDL
     * - nillable: true
     * @var string|null
     */
    protected ?string $date = null;
    /**
     * Constructor method for NullableDate
     * @uses NullableDate::setDate()
     * @param string $date
     */
    public function __construct(?string $date = null)
    {
        $this
            ->setDate($date);
    }
    /**
     * Get date value
     * @return string|null
     */
    public function getDate(): ?string
    {
        return $this->date;
    }
    /**
     * Set date value
     * @param string $date
     * @return \WpifyWoo\PacketeraSDK\StructType\NullableDate
     */
    public function setDate(?string $date = null): self
    {
        // validation for constraint: string
        if (!is_null($date) && !is_string($date)) {
            throw new InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($date, true), gettype($date)), __LINE__);
        }
        $this->date = $date;
        
        return $this;
    }
}
