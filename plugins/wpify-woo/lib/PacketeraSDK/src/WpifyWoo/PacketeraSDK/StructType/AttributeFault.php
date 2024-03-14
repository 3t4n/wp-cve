<?php

declare(strict_types=1);

namespace WpifyWoo\PacketeraSDK\StructType;

use InvalidArgumentException;
use WsdlToPhp\PackageBase\AbstractStructBase;

/**
 * This class stands for AttributeFault StructType
 * @subpackage Structs
 */
class AttributeFault extends AbstractStructBase
{
    /**
     * The name
     * @var string|null
     */
    protected ?string $name = null;
    /**
     * The fault
     * @var string|null
     */
    protected ?string $fault = null;
    /**
     * The field
     * Meta information extracted from the WSDL
     * - minOccurs: 0
     * - nillable: true
     * @var string|null
     */
    protected ?string $field = null;
    /**
     * Constructor method for AttributeFault
     * @uses AttributeFault::setName()
     * @uses AttributeFault::setFault()
     * @uses AttributeFault::setField()
     * @param string $name
     * @param string $fault
     * @param string $field
     */
    public function __construct(?string $name = null, ?string $fault = null, ?string $field = null)
    {
        $this
            ->setName($name)
            ->setFault($fault)
            ->setField($field);
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
     * @return \WpifyWoo\PacketeraSDK\StructType\AttributeFault
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
     * Get fault value
     * @return string|null
     */
    public function getFault(): ?string
    {
        return $this->fault;
    }
    /**
     * Set fault value
     * @param string $fault
     * @return \WpifyWoo\PacketeraSDK\StructType\AttributeFault
     */
    public function setFault(?string $fault = null): self
    {
        // validation for constraint: string
        if (!is_null($fault) && !is_string($fault)) {
            throw new InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($fault, true), gettype($fault)), __LINE__);
        }
        $this->fault = $fault;
        
        return $this;
    }
    /**
     * Get field value
     * An additional test has been added (isset) before returning the property value as
     * this property may have been unset before, due to the fact that this property is
     * removable from the request (nillable=true+minOccurs=0)
     * @return string|null
     */
    public function getField(): ?string
    {
        return isset($this->field) ? $this->field : null;
    }
    /**
     * Set field value
     * This property is removable from request (nillable=true+minOccurs=0), therefore
     * if the value assigned to this property is null, it is removed from this object
     * @param string $field
     * @return \WpifyWoo\PacketeraSDK\StructType\AttributeFault
     */
    public function setField(?string $field = null): self
    {
        // validation for constraint: string
        if (!is_null($field) && !is_string($field)) {
            throw new InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($field, true), gettype($field)), __LINE__);
        }
        if (is_null($field) || (is_array($field) && empty($field))) {
            unset($this->field);
        } else {
            $this->field = $field;
        }
        
        return $this;
    }
}
