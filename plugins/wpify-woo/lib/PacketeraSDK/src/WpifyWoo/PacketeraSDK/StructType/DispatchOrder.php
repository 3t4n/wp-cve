<?php

declare(strict_types=1);

namespace WpifyWoo\PacketeraSDK\StructType;

use InvalidArgumentException;
use WsdlToPhp\PackageBase\AbstractStructBase;

/**
 * This class stands for DispatchOrder StructType
 * @subpackage Structs
 */
class DispatchOrder extends AbstractStructBase
{
    /**
     * The goods
     * @var string|null
     */
    protected ?string $goods = null;
    /**
     * The pdf
     * @var string|null
     */
    protected ?string $pdf = null;
    /**
     * Constructor method for DispatchOrder
     * @uses DispatchOrder::setGoods()
     * @uses DispatchOrder::setPdf()
     * @param string $goods
     * @param string $pdf
     */
    public function __construct(?string $goods = null, ?string $pdf = null)
    {
        $this
            ->setGoods($goods)
            ->setPdf($pdf);
    }
    /**
     * Get goods value
     * @return string|null
     */
    public function getGoods(): ?string
    {
        return $this->goods;
    }
    /**
     * Set goods value
     * @param string $goods
     * @return \WpifyWoo\PacketeraSDK\StructType\DispatchOrder
     */
    public function setGoods(?string $goods = null): self
    {
        // validation for constraint: string
        if (!is_null($goods) && !is_string($goods)) {
            throw new InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($goods, true), gettype($goods)), __LINE__);
        }
        $this->goods = $goods;
        
        return $this;
    }
    /**
     * Get pdf value
     * @return string|null
     */
    public function getPdf(): ?string
    {
        return $this->pdf;
    }
    /**
     * Set pdf value
     * @param string $pdf
     * @return \WpifyWoo\PacketeraSDK\StructType\DispatchOrder
     */
    public function setPdf(?string $pdf = null): self
    {
        // validation for constraint: string
        if (!is_null($pdf) && !is_string($pdf)) {
            throw new InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($pdf, true), gettype($pdf)), __LINE__);
        }
        $this->pdf = $pdf;
        
        return $this;
    }
}
