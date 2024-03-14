<?php

declare(strict_types=1);

namespace WpifyWoo\PacketeraSDK\StructType;

use InvalidArgumentException;
use WsdlToPhp\PackageBase\AbstractStructBase;

/**
 * This class stands for PacketIdDetail StructType
 * @subpackage Structs
 */
class PacketIdDetail extends AbstractStructBase
{
    /**
     * The id
     * @var string|null
     */
    protected ?string $id = null;
    /**
     * The barcode
     * @var string|null
     */
    protected ?string $barcode = null;
    /**
     * The barcodeText
     * @var string|null
     */
    protected ?string $barcodeText = null;
    /**
     * Constructor method for PacketIdDetail
     * @uses PacketIdDetail::setId()
     * @uses PacketIdDetail::setBarcode()
     * @uses PacketIdDetail::setBarcodeText()
     * @param string $id
     * @param string $barcode
     * @param string $barcodeText
     */
    public function __construct(?string $id = null, ?string $barcode = null, ?string $barcodeText = null)
    {
        $this
            ->setId($id)
            ->setBarcode($barcode)
            ->setBarcodeText($barcodeText);
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
     * @return \WpifyWoo\PacketeraSDK\StructType\PacketIdDetail
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
     * Get barcode value
     * @return string|null
     */
    public function getBarcode(): ?string
    {
        return $this->barcode;
    }
    /**
     * Set barcode value
     * @param string $barcode
     * @return \WpifyWoo\PacketeraSDK\StructType\PacketIdDetail
     */
    public function setBarcode(?string $barcode = null): self
    {
        // validation for constraint: string
        if (!is_null($barcode) && !is_string($barcode)) {
            throw new InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($barcode, true), gettype($barcode)), __LINE__);
        }
        $this->barcode = $barcode;
        
        return $this;
    }
    /**
     * Get barcodeText value
     * @return string|null
     */
    public function getBarcodeText(): ?string
    {
        return $this->barcodeText;
    }
    /**
     * Set barcodeText value
     * @param string $barcodeText
     * @return \WpifyWoo\PacketeraSDK\StructType\PacketIdDetail
     */
    public function setBarcodeText(?string $barcodeText = null): self
    {
        // validation for constraint: string
        if (!is_null($barcodeText) && !is_string($barcodeText)) {
            throw new InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($barcodeText, true), gettype($barcodeText)), __LINE__);
        }
        $this->barcodeText = $barcodeText;
        
        return $this;
    }
}
