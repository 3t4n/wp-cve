<?php

declare(strict_types=1);

namespace WpifyWoo\PacketeraSDK\StructType;

use InvalidArgumentException;
use WsdlToPhp\PackageBase\AbstractStructBase;

/**
 * This class stands for Size StructType
 * @subpackage Structs
 */
class Size extends AbstractStructBase
{
    /**
     * The length
     * @var int|null
     */
    protected ?int $length = null;
    /**
     * The width
     * @var int|null
     */
    protected ?int $width = null;
    /**
     * The height
     * @var int|null
     */
    protected ?int $height = null;
    /**
     * Constructor method for Size
     * @uses Size::setLength()
     * @uses Size::setWidth()
     * @uses Size::setHeight()
     * @param int $length
     * @param int $width
     * @param int $height
     */
    public function __construct(?int $length = null, ?int $width = null, ?int $height = null)
    {
        $this
            ->setLength($length)
            ->setWidth($width)
            ->setHeight($height);
    }
    /**
     * Get length value
     * @return int|null
     */
    public function getLength(): ?int
    {
        return $this->length;
    }
    /**
     * Set length value
     * @param int $length
     * @return \WpifyWoo\PacketeraSDK\StructType\Size
     */
    public function setLength(?int $length = null): self
    {
        // validation for constraint: int
        if (!is_null($length) && !(is_int($length) || ctype_digit($length))) {
            throw new InvalidArgumentException(sprintf('Invalid value %s, please provide an integer value, %s given', var_export($length, true), gettype($length)), __LINE__);
        }
        $this->length = $length;
        
        return $this;
    }
    /**
     * Get width value
     * @return int|null
     */
    public function getWidth(): ?int
    {
        return $this->width;
    }
    /**
     * Set width value
     * @param int $width
     * @return \WpifyWoo\PacketeraSDK\StructType\Size
     */
    public function setWidth(?int $width = null): self
    {
        // validation for constraint: int
        if (!is_null($width) && !(is_int($width) || ctype_digit($width))) {
            throw new InvalidArgumentException(sprintf('Invalid value %s, please provide an integer value, %s given', var_export($width, true), gettype($width)), __LINE__);
        }
        $this->width = $width;
        
        return $this;
    }
    /**
     * Get height value
     * @return int|null
     */
    public function getHeight(): ?int
    {
        return $this->height;
    }
    /**
     * Set height value
     * @param int $height
     * @return \WpifyWoo\PacketeraSDK\StructType\Size
     */
    public function setHeight(?int $height = null): self
    {
        // validation for constraint: int
        if (!is_null($height) && !(is_int($height) || ctype_digit($height))) {
            throw new InvalidArgumentException(sprintf('Invalid value %s, please provide an integer value, %s given', var_export($height, true), gettype($height)), __LINE__);
        }
        $this->height = $height;
        
        return $this;
    }
}
