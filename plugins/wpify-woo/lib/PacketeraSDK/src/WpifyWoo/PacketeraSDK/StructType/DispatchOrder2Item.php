<?php

declare(strict_types=1);

namespace WpifyWoo\PacketeraSDK\StructType;

use InvalidArgumentException;
use WsdlToPhp\PackageBase\AbstractStructBase;

/**
 * This class stands for DispatchOrder2Item StructType
 * @subpackage Structs
 */
class DispatchOrder2Item extends AbstractStructBase
{
    /**
     * The code
     * @var string|null
     */
    protected ?string $code = null;
    /**
     * The name
     * @var string|null
     */
    protected ?string $name = null;
    /**
     * The unit_price
     * @var string|null
     */
    protected ?string $unit_price = null;
    /**
     * The pieces
     * @var string|null
     */
    protected ?string $pieces = null;
    /**
     * The price
     * @var string|null
     */
    protected ?string $price = null;
    /**
     * The vat
     * @var string|null
     */
    protected ?string $vat = null;
    /**
     * The price_vat
     * @var string|null
     */
    protected ?string $price_vat = null;
    /**
     * Constructor method for DispatchOrder2Item
     * @uses DispatchOrder2Item::setCode()
     * @uses DispatchOrder2Item::setName()
     * @uses DispatchOrder2Item::setUnit_price()
     * @uses DispatchOrder2Item::setPieces()
     * @uses DispatchOrder2Item::setPrice()
     * @uses DispatchOrder2Item::setVat()
     * @uses DispatchOrder2Item::setPrice_vat()
     * @param string $code
     * @param string $name
     * @param string $unit_price
     * @param string $pieces
     * @param string $price
     * @param string $vat
     * @param string $price_vat
     */
    public function __construct(?string $code = null, ?string $name = null, ?string $unit_price = null, ?string $pieces = null, ?string $price = null, ?string $vat = null, ?string $price_vat = null)
    {
        $this
            ->setCode($code)
            ->setName($name)
            ->setUnit_price($unit_price)
            ->setPieces($pieces)
            ->setPrice($price)
            ->setVat($vat)
            ->setPrice_vat($price_vat);
    }
    /**
     * Get code value
     * @return string|null
     */
    public function getCode(): ?string
    {
        return $this->code;
    }
    /**
     * Set code value
     * @param string $code
     * @return \WpifyWoo\PacketeraSDK\StructType\DispatchOrder2Item
     */
    public function setCode(?string $code = null): self
    {
        // validation for constraint: string
        if (!is_null($code) && !is_string($code)) {
            throw new InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($code, true), gettype($code)), __LINE__);
        }
        $this->code = $code;
        
        return $this;
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
     * @return \WpifyWoo\PacketeraSDK\StructType\DispatchOrder2Item
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
     * Get unit_price value
     * @return string|null
     */
    public function getUnit_price(): ?string
    {
        return $this->unit_price;
    }
    /**
     * Set unit_price value
     * @param string $unit_price
     * @return \WpifyWoo\PacketeraSDK\StructType\DispatchOrder2Item
     */
    public function setUnit_price(?string $unit_price = null): self
    {
        // validation for constraint: string
        if (!is_null($unit_price) && !is_string($unit_price)) {
            throw new InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($unit_price, true), gettype($unit_price)), __LINE__);
        }
        $this->unit_price = $unit_price;
        
        return $this;
    }
    /**
     * Get pieces value
     * @return string|null
     */
    public function getPieces(): ?string
    {
        return $this->pieces;
    }
    /**
     * Set pieces value
     * @param string $pieces
     * @return \WpifyWoo\PacketeraSDK\StructType\DispatchOrder2Item
     */
    public function setPieces(?string $pieces = null): self
    {
        // validation for constraint: string
        if (!is_null($pieces) && !is_string($pieces)) {
            throw new InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($pieces, true), gettype($pieces)), __LINE__);
        }
        $this->pieces = $pieces;
        
        return $this;
    }
    /**
     * Get price value
     * @return string|null
     */
    public function getPrice(): ?string
    {
        return $this->price;
    }
    /**
     * Set price value
     * @param string $price
     * @return \WpifyWoo\PacketeraSDK\StructType\DispatchOrder2Item
     */
    public function setPrice(?string $price = null): self
    {
        // validation for constraint: string
        if (!is_null($price) && !is_string($price)) {
            throw new InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($price, true), gettype($price)), __LINE__);
        }
        $this->price = $price;
        
        return $this;
    }
    /**
     * Get vat value
     * @return string|null
     */
    public function getVat(): ?string
    {
        return $this->vat;
    }
    /**
     * Set vat value
     * @param string $vat
     * @return \WpifyWoo\PacketeraSDK\StructType\DispatchOrder2Item
     */
    public function setVat(?string $vat = null): self
    {
        // validation for constraint: string
        if (!is_null($vat) && !is_string($vat)) {
            throw new InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($vat, true), gettype($vat)), __LINE__);
        }
        $this->vat = $vat;
        
        return $this;
    }
    /**
     * Get price_vat value
     * @return string|null
     */
    public function getPrice_vat(): ?string
    {
        return $this->price_vat;
    }
    /**
     * Set price_vat value
     * @param string $price_vat
     * @return \WpifyWoo\PacketeraSDK\StructType\DispatchOrder2Item
     */
    public function setPrice_vat(?string $price_vat = null): self
    {
        // validation for constraint: string
        if (!is_null($price_vat) && !is_string($price_vat)) {
            throw new InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($price_vat, true), gettype($price_vat)), __LINE__);
        }
        $this->price_vat = $price_vat;
        
        return $this;
    }
}
