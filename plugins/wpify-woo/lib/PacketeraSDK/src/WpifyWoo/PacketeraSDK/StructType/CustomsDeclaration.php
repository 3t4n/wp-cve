<?php

declare(strict_types=1);

namespace WpifyWoo\PacketeraSDK\StructType;

use InvalidArgumentException;
use WsdlToPhp\PackageBase\AbstractStructBase;

/**
 * This class stands for CustomsDeclaration StructType
 * @subpackage Structs
 */
class CustomsDeclaration extends AbstractStructBase
{
    /**
     * The ead
     * @var string|null
     */
    protected ?string $ead = null;
    /**
     * The deliveryCost
     * Meta information extracted from the WSDL
     * - base: xsd:decimal
     * - fractionDigits: 2
     * @var float|null
     */
    protected ?float $deliveryCost = null;
    /**
     * The isDocument
     * Meta information extracted from the WSDL
     * - minOccurs: 0
     * @var bool|null
     */
    protected ?bool $isDocument = null;
    /**
     * The invoiceNumber
     * Meta information extracted from the WSDL
     * - base: xsd:string
     * - maxLength: 16
     * - minOccurs: 0
     * @var string|null
     */
    protected ?string $invoiceNumber = null;
    /**
     * The invoiceIssueDate
     * Meta information extracted from the WSDL
     * - minOccurs: 0
     * @var string|null
     */
    protected ?string $invoiceIssueDate = null;
    /**
     * The mrn
     * Meta information extracted from the WSDL
     * - base: xsd:string
     * - maxLength: 32
     * - minOccurs: 0
     * @var string|null
     */
    protected ?string $mrn = null;
    /**
     * The eadFile
     * Meta information extracted from the WSDL
     * - minOccurs: 0
     * @var int|null
     */
    protected ?int $eadFile = null;
    /**
     * The invoiceFile
     * Meta information extracted from the WSDL
     * - minOccurs: 0
     * @var int|null
     */
    protected ?int $invoiceFile = null;
    /**
     * The items
     * @var \WpifyWoo\PacketeraSDK\StructType\CustomsDeclarationItems|null
     */
    protected ?\WpifyWoo\PacketeraSDK\StructType\CustomsDeclarationItems $items = null;
    /**
     * Constructor method for CustomsDeclaration
     * @uses CustomsDeclaration::setEad()
     * @uses CustomsDeclaration::setDeliveryCost()
     * @uses CustomsDeclaration::setIsDocument()
     * @uses CustomsDeclaration::setInvoiceNumber()
     * @uses CustomsDeclaration::setInvoiceIssueDate()
     * @uses CustomsDeclaration::setMrn()
     * @uses CustomsDeclaration::setEadFile()
     * @uses CustomsDeclaration::setInvoiceFile()
     * @uses CustomsDeclaration::setItems()
     * @param string $ead
     * @param float $deliveryCost
     * @param bool $isDocument
     * @param string $invoiceNumber
     * @param string $invoiceIssueDate
     * @param string $mrn
     * @param int $eadFile
     * @param int $invoiceFile
     * @param \WpifyWoo\PacketeraSDK\StructType\CustomsDeclarationItems $items
     */
    public function __construct(?string $ead = null, ?float $deliveryCost = null, ?bool $isDocument = null, ?string $invoiceNumber = null, ?string $invoiceIssueDate = null, ?string $mrn = null, ?int $eadFile = null, ?int $invoiceFile = null, ?\WpifyWoo\PacketeraSDK\StructType\CustomsDeclarationItems $items = null)
    {
        $this
            ->setEad($ead)
            ->setDeliveryCost($deliveryCost)
            ->setIsDocument($isDocument)
            ->setInvoiceNumber($invoiceNumber)
            ->setInvoiceIssueDate($invoiceIssueDate)
            ->setMrn($mrn)
            ->setEadFile($eadFile)
            ->setInvoiceFile($invoiceFile)
            ->setItems($items);
    }
    /**
     * Get ead value
     * @return string|null
     */
    public function getEad(): ?string
    {
        return $this->ead;
    }
    /**
     * Set ead value
     * @uses \WpifyWoo\PacketeraSDK\EnumType\Ead::valueIsValid()
     * @uses \WpifyWoo\PacketeraSDK\EnumType\Ead::getValidValues()
     * @throws InvalidArgumentException
     * @param string $ead
     * @return \WpifyWoo\PacketeraSDK\StructType\CustomsDeclaration
     */
    public function setEad(?string $ead = null): self
    {
        // validation for constraint: enumeration
        if (!\WpifyWoo\PacketeraSDK\EnumType\Ead::valueIsValid($ead)) {
            throw new InvalidArgumentException(sprintf('Invalid value(s) %s, please use one of: %s from enumeration class \WpifyWoo\PacketeraSDK\EnumType\Ead', is_array($ead) ? implode(', ', $ead) : var_export($ead, true), implode(', ', \WpifyWoo\PacketeraSDK\EnumType\Ead::getValidValues())), __LINE__);
        }
        $this->ead = $ead;
        
        return $this;
    }
    /**
     * Get deliveryCost value
     * @return float|null
     */
    public function getDeliveryCost(): ?float
    {
        return $this->deliveryCost;
    }
    /**
     * Set deliveryCost value
     * @param float $deliveryCost
     * @return \WpifyWoo\PacketeraSDK\StructType\CustomsDeclaration
     */
    public function setDeliveryCost(?float $deliveryCost = null): self
    {
        // validation for constraint: float
        if (!is_null($deliveryCost) && !(is_float($deliveryCost) || is_numeric($deliveryCost))) {
            throw new InvalidArgumentException(sprintf('Invalid value %s, please provide a float value, %s given', var_export($deliveryCost, true), gettype($deliveryCost)), __LINE__);
        }
        // validation for constraint: fractionDigits(2)
        if (!is_null($deliveryCost) && mb_strlen(mb_substr((string) $deliveryCost, false !== mb_strpos((string) $deliveryCost, '.') ? mb_strpos((string) $deliveryCost, '.') + 1 : mb_strlen((string) $deliveryCost))) > 2) {
            throw new InvalidArgumentException(sprintf('Invalid value %s, the value must at most contain 2 fraction digits, %d given', var_export($deliveryCost, true), mb_strlen(mb_substr((string) $deliveryCost, mb_strpos((string) $deliveryCost, '.') + 1))), __LINE__);
        }
        $this->deliveryCost = $deliveryCost;
        
        return $this;
    }
    /**
     * Get isDocument value
     * @return bool|null
     */
    public function getIsDocument(): ?bool
    {
        return $this->isDocument;
    }
    /**
     * Set isDocument value
     * @param bool $isDocument
     * @return \WpifyWoo\PacketeraSDK\StructType\CustomsDeclaration
     */
    public function setIsDocument(?bool $isDocument = null): self
    {
        // validation for constraint: boolean
        if (!is_null($isDocument) && !is_bool($isDocument)) {
            throw new InvalidArgumentException(sprintf('Invalid value %s, please provide a bool, %s given', var_export($isDocument, true), gettype($isDocument)), __LINE__);
        }
        $this->isDocument = $isDocument;
        
        return $this;
    }
    /**
     * Get invoiceNumber value
     * @return string|null
     */
    public function getInvoiceNumber(): ?string
    {
        return $this->invoiceNumber;
    }
    /**
     * Set invoiceNumber value
     * @param string $invoiceNumber
     * @return \WpifyWoo\PacketeraSDK\StructType\CustomsDeclaration
     */
    public function setInvoiceNumber(?string $invoiceNumber = null): self
    {
        // validation for constraint: string
        if (!is_null($invoiceNumber) && !is_string($invoiceNumber)) {
            throw new InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($invoiceNumber, true), gettype($invoiceNumber)), __LINE__);
        }
        // validation for constraint: maxLength(16)
        if (!is_null($invoiceNumber) && mb_strlen((string) $invoiceNumber) > 16) {
            throw new InvalidArgumentException(sprintf('Invalid length of %s, the number of characters/octets contained by the literal must be less than or equal to 16', mb_strlen((string) $invoiceNumber)), __LINE__);
        }
        $this->invoiceNumber = $invoiceNumber;
        
        return $this;
    }
    /**
     * Get invoiceIssueDate value
     * @return string|null
     */
    public function getInvoiceIssueDate(): ?string
    {
        return $this->invoiceIssueDate;
    }
    /**
     * Set invoiceIssueDate value
     * @param string $invoiceIssueDate
     * @return \WpifyWoo\PacketeraSDK\StructType\CustomsDeclaration
     */
    public function setInvoiceIssueDate(?string $invoiceIssueDate = null): self
    {
        // validation for constraint: string
        if (!is_null($invoiceIssueDate) && !is_string($invoiceIssueDate)) {
            throw new InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($invoiceIssueDate, true), gettype($invoiceIssueDate)), __LINE__);
        }
        $this->invoiceIssueDate = $invoiceIssueDate;
        
        return $this;
    }
    /**
     * Get mrn value
     * @return string|null
     */
    public function getMrn(): ?string
    {
        return $this->mrn;
    }
    /**
     * Set mrn value
     * @param string $mrn
     * @return \WpifyWoo\PacketeraSDK\StructType\CustomsDeclaration
     */
    public function setMrn(?string $mrn = null): self
    {
        // validation for constraint: string
        if (!is_null($mrn) && !is_string($mrn)) {
            throw new InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($mrn, true), gettype($mrn)), __LINE__);
        }
        // validation for constraint: maxLength(32)
        if (!is_null($mrn) && mb_strlen((string) $mrn) > 32) {
            throw new InvalidArgumentException(sprintf('Invalid length of %s, the number of characters/octets contained by the literal must be less than or equal to 32', mb_strlen((string) $mrn)), __LINE__);
        }
        $this->mrn = $mrn;
        
        return $this;
    }
    /**
     * Get eadFile value
     * @return int|null
     */
    public function getEadFile(): ?int
    {
        return $this->eadFile;
    }
    /**
     * Set eadFile value
     * @param int $eadFile
     * @return \WpifyWoo\PacketeraSDK\StructType\CustomsDeclaration
     */
    public function setEadFile(?int $eadFile = null): self
    {
        // validation for constraint: int
        if (!is_null($eadFile) && !(is_int($eadFile) || ctype_digit($eadFile))) {
            throw new InvalidArgumentException(sprintf('Invalid value %s, please provide an integer value, %s given', var_export($eadFile, true), gettype($eadFile)), __LINE__);
        }
        $this->eadFile = $eadFile;
        
        return $this;
    }
    /**
     * Get invoiceFile value
     * @return int|null
     */
    public function getInvoiceFile(): ?int
    {
        return $this->invoiceFile;
    }
    /**
     * Set invoiceFile value
     * @param int $invoiceFile
     * @return \WpifyWoo\PacketeraSDK\StructType\CustomsDeclaration
     */
    public function setInvoiceFile(?int $invoiceFile = null): self
    {
        // validation for constraint: int
        if (!is_null($invoiceFile) && !(is_int($invoiceFile) || ctype_digit($invoiceFile))) {
            throw new InvalidArgumentException(sprintf('Invalid value %s, please provide an integer value, %s given', var_export($invoiceFile, true), gettype($invoiceFile)), __LINE__);
        }
        $this->invoiceFile = $invoiceFile;
        
        return $this;
    }
    /**
     * Get items value
     * @return \WpifyWoo\PacketeraSDK\StructType\CustomsDeclarationItems|null
     */
    public function getItems(): ?\WpifyWoo\PacketeraSDK\StructType\CustomsDeclarationItems
    {
        return $this->items;
    }
    /**
     * Set items value
     * @param \WpifyWoo\PacketeraSDK\StructType\CustomsDeclarationItems $items
     * @return \WpifyWoo\PacketeraSDK\StructType\CustomsDeclaration
     */
    public function setItems(?\WpifyWoo\PacketeraSDK\StructType\CustomsDeclarationItems $items = null): self
    {
        $this->items = $items;
        
        return $this;
    }
}
