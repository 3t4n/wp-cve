<?php

declare(strict_types=1);

namespace WpifyWoo\PacketeraSDK\StructType;

use InvalidArgumentException;
use WsdlToPhp\PackageBase\AbstractStructBase;

/**
 * This class stands for PacketDetail StructType
 * @subpackage Structs
 */
class PacketDetail extends AbstractStructBase
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
     * The password
     * @var string|null
     */
    protected ?string $password = null;
    /**
     * Constructor method for PacketDetail
     * @uses PacketDetail::setId()
     * @uses PacketDetail::setBarcode()
     * @uses PacketDetail::setBarcodeText()
     * @uses PacketDetail::setPassword()
     * @param string $id
     * @param string $barcode
     * @param string $barcodeText
     * @param string $password
     */
    public function __construct(?string $id = null, ?string $barcode = null, ?string $barcodeText = null, ?string $password = null)
    {
        $this
            ->setId($id)
            ->setBarcode($barcode)
            ->setBarcodeText($barcodeText)
            ->setPassword($password);
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
     * @return \WpifyWoo\PacketeraSDK\StructType\PacketDetail
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
     * @return \WpifyWoo\PacketeraSDK\StructType\PacketDetail
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
     * @return \WpifyWoo\PacketeraSDK\StructType\PacketDetail
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
    /**
     * Get password value
     * @return string|null
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }
    /**
     * Set password value
     * @param string $password
     * @return \WpifyWoo\PacketeraSDK\StructType\PacketDetail
     */
    public function setPassword(?string $password = null): self
    {
        // validation for constraint: string
        if (!is_null($password) && !is_string($password)) {
            throw new InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($password, true), gettype($password)), __LINE__);
        }
        $this->password = $password;
        
        return $this;
    }
}
