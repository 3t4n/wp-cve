<?php

declare(strict_types=1);

namespace WpifyWoo\PacketeraSDK\StructType;

use InvalidArgumentException;
use WsdlToPhp\PackageBase\AbstractStructBase;

/**
 * This class stands for ShipmentIdDetail StructType
 * @subpackage Structs
 */
class ShipmentIdDetail extends AbstractStructBase
{
    /**
     * The id
     * @var int|null
     */
    protected ?int $id = null;
    /**
     * The checksum
     * @var string|null
     */
    protected ?string $checksum = null;
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
     * Constructor method for ShipmentIdDetail
     * @uses ShipmentIdDetail::setId()
     * @uses ShipmentIdDetail::setChecksum()
     * @uses ShipmentIdDetail::setBarcode()
     * @uses ShipmentIdDetail::setBarcodeText()
     * @param int $id
     * @param string $checksum
     * @param string $barcode
     * @param string $barcodeText
     */
    public function __construct(?int $id = null, ?string $checksum = null, ?string $barcode = null, ?string $barcodeText = null)
    {
        $this
            ->setId($id)
            ->setChecksum($checksum)
            ->setBarcode($barcode)
            ->setBarcodeText($barcodeText);
    }
    /**
     * Get id value
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }
    /**
     * Set id value
     * @param int $id
     * @return \WpifyWoo\PacketeraSDK\StructType\ShipmentIdDetail
     */
    public function setId(?int $id = null): self
    {
        // validation for constraint: int
        if (!is_null($id) && !(is_int($id) || ctype_digit($id))) {
            throw new InvalidArgumentException(sprintf('Invalid value %s, please provide an integer value, %s given', var_export($id, true), gettype($id)), __LINE__);
        }
        $this->id = $id;
        
        return $this;
    }
    /**
     * Get checksum value
     * @return string|null
     */
    public function getChecksum(): ?string
    {
        return $this->checksum;
    }
    /**
     * Set checksum value
     * @param string $checksum
     * @return \WpifyWoo\PacketeraSDK\StructType\ShipmentIdDetail
     */
    public function setChecksum(?string $checksum = null): self
    {
        // validation for constraint: string
        if (!is_null($checksum) && !is_string($checksum)) {
            throw new InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($checksum, true), gettype($checksum)), __LINE__);
        }
        $this->checksum = $checksum;
        
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
     * @return \WpifyWoo\PacketeraSDK\StructType\ShipmentIdDetail
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
     * @return \WpifyWoo\PacketeraSDK\StructType\ShipmentIdDetail
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
