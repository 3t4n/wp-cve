<?php

declare(strict_types=1);

namespace WpifyWoo\PacketeraSDK\StructType;

use InvalidArgumentException;
use WsdlToPhp\PackageBase\AbstractStructBase;

/**
 * This class stands for CustomsDeclarationItem StructType
 * @subpackage Structs
 */
class CustomsDeclarationItem extends AbstractStructBase
{
    /**
     * The customsCode
     * @var string|null
     */
    protected ?string $customsCode = null;
    /**
     * The value
     * Meta information extracted from the WSDL
     * - base: xsd:decimal
     * - fractionDigits: 2
     * @var float|null
     */
    protected ?float $value = null;
    /**
     * The productNameEn
     * Meta information extracted from the WSDL
     * - base: xsd:string
     * - maxLength: 32
     * @var string|null
     */
    protected ?string $productNameEn = null;
    /**
     * The productName
     * Meta information extracted from the WSDL
     * - base: xsd:string
     * - maxLength: 32
     * - minOccurs: 0
     * @var string|null
     */
    protected ?string $productName = null;
    /**
     * The unitsCount
     * Meta information extracted from the WSDL
     * - default: 0
     * @var int|null
     */
    protected ?int $unitsCount = null;
    /**
     * The countryOfOrigin
     * Meta information extracted from the WSDL
     * - base: xsd:string
     * - maxLength: 16
     * @var string|null
     */
    protected ?string $countryOfOrigin = null;
    /**
     * The weight
     * @var int|null
     */
    protected ?int $weight = null;
    /**
     * The isFoodBook
     * Meta information extracted from the WSDL
     * - default: 0
     * - minOccurs: 0
     * @var bool|null
     */
    protected ?bool $isFoodBook = null;
    /**
     * The isVoc
     * Meta information extracted from the WSDL
     * - default: 0
     * - minOccurs: 0
     * @var bool|null
     */
    protected ?bool $isVoc = null;
    /**
     * Constructor method for CustomsDeclarationItem
     * @uses CustomsDeclarationItem::setCustomsCode()
     * @uses CustomsDeclarationItem::setValue()
     * @uses CustomsDeclarationItem::setProductNameEn()
     * @uses CustomsDeclarationItem::setProductName()
     * @uses CustomsDeclarationItem::setUnitsCount()
     * @uses CustomsDeclarationItem::setCountryOfOrigin()
     * @uses CustomsDeclarationItem::setWeight()
     * @uses CustomsDeclarationItem::setIsFoodBook()
     * @uses CustomsDeclarationItem::setIsVoc()
     * @param string $customsCode
     * @param float $value
     * @param string $productNameEn
     * @param string $productName
     * @param int $unitsCount
     * @param string $countryOfOrigin
     * @param int $weight
     * @param bool $isFoodBook
     * @param bool $isVoc
     */
    public function __construct(?string $customsCode = null, ?float $value = null, ?string $productNameEn = null, ?string $productName = null, ?int $unitsCount = 0, ?string $countryOfOrigin = null, ?int $weight = null, ?bool $isFoodBook = false, ?bool $isVoc = false)
    {
        $this
            ->setCustomsCode($customsCode)
            ->setValue($value)
            ->setProductNameEn($productNameEn)
            ->setProductName($productName)
            ->setUnitsCount($unitsCount)
            ->setCountryOfOrigin($countryOfOrigin)
            ->setWeight($weight)
            ->setIsFoodBook($isFoodBook)
            ->setIsVoc($isVoc);
    }
    /**
     * Get customsCode value
     * @return string|null
     */
    public function getCustomsCode(): ?string
    {
        return $this->customsCode;
    }
    /**
     * Set customsCode value
     * @param string $customsCode
     * @return \WpifyWoo\PacketeraSDK\StructType\CustomsDeclarationItem
     */
    public function setCustomsCode(?string $customsCode = null): self
    {
        // validation for constraint: string
        if (!is_null($customsCode) && !is_string($customsCode)) {
            throw new InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($customsCode, true), gettype($customsCode)), __LINE__);
        }
        $this->customsCode = $customsCode;
        
        return $this;
    }
    /**
     * Get value value
     * @return float|null
     */
    public function getValue(): ?float
    {
        return $this->value;
    }
    /**
     * Set value value
     * @param float $value
     * @return \WpifyWoo\PacketeraSDK\StructType\CustomsDeclarationItem
     */
    public function setValue(?float $value = null): self
    {
        // validation for constraint: float
        if (!is_null($value) && !(is_float($value) || is_numeric($value))) {
            throw new InvalidArgumentException(sprintf('Invalid value %s, please provide a float value, %s given', var_export($value, true), gettype($value)), __LINE__);
        }
        // validation for constraint: fractionDigits(2)
        if (!is_null($value) && mb_strlen(mb_substr((string) $value, false !== mb_strpos((string) $value, '.') ? mb_strpos((string) $value, '.') + 1 : mb_strlen((string) $value))) > 2) {
            throw new InvalidArgumentException(sprintf('Invalid value %s, the value must at most contain 2 fraction digits, %d given', var_export($value, true), mb_strlen(mb_substr((string) $value, mb_strpos((string) $value, '.') + 1))), __LINE__);
        }
        $this->value = $value;
        
        return $this;
    }
    /**
     * Get productNameEn value
     * @return string|null
     */
    public function getProductNameEn(): ?string
    {
        return $this->productNameEn;
    }
    /**
     * Set productNameEn value
     * @param string $productNameEn
     * @return \WpifyWoo\PacketeraSDK\StructType\CustomsDeclarationItem
     */
    public function setProductNameEn(?string $productNameEn = null): self
    {
        // validation for constraint: string
        if (!is_null($productNameEn) && !is_string($productNameEn)) {
            throw new InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($productNameEn, true), gettype($productNameEn)), __LINE__);
        }
        // validation for constraint: maxLength(32)
        if (!is_null($productNameEn) && mb_strlen((string) $productNameEn) > 32) {
            throw new InvalidArgumentException(sprintf('Invalid length of %s, the number of characters/octets contained by the literal must be less than or equal to 32', mb_strlen((string) $productNameEn)), __LINE__);
        }
        $this->productNameEn = $productNameEn;
        
        return $this;
    }
    /**
     * Get productName value
     * @return string|null
     */
    public function getProductName(): ?string
    {
        return $this->productName;
    }
    /**
     * Set productName value
     * @param string $productName
     * @return \WpifyWoo\PacketeraSDK\StructType\CustomsDeclarationItem
     */
    public function setProductName(?string $productName = null): self
    {
        // validation for constraint: string
        if (!is_null($productName) && !is_string($productName)) {
            throw new InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($productName, true), gettype($productName)), __LINE__);
        }
        // validation for constraint: maxLength(32)
        if (!is_null($productName) && mb_strlen((string) $productName) > 32) {
            throw new InvalidArgumentException(sprintf('Invalid length of %s, the number of characters/octets contained by the literal must be less than or equal to 32', mb_strlen((string) $productName)), __LINE__);
        }
        $this->productName = $productName;
        
        return $this;
    }
    /**
     * Get unitsCount value
     * @return int|null
     */
    public function getUnitsCount(): ?int
    {
        return $this->unitsCount;
    }
    /**
     * Set unitsCount value
     * @param int $unitsCount
     * @return \WpifyWoo\PacketeraSDK\StructType\CustomsDeclarationItem
     */
    public function setUnitsCount(?int $unitsCount = 0): self
    {
        // validation for constraint: int
        if (!is_null($unitsCount) && !(is_int($unitsCount) || ctype_digit($unitsCount))) {
            throw new InvalidArgumentException(sprintf('Invalid value %s, please provide an integer value, %s given', var_export($unitsCount, true), gettype($unitsCount)), __LINE__);
        }
        $this->unitsCount = $unitsCount;
        
        return $this;
    }
    /**
     * Get countryOfOrigin value
     * @return string|null
     */
    public function getCountryOfOrigin(): ?string
    {
        return $this->countryOfOrigin;
    }
    /**
     * Set countryOfOrigin value
     * @param string $countryOfOrigin
     * @return \WpifyWoo\PacketeraSDK\StructType\CustomsDeclarationItem
     */
    public function setCountryOfOrigin(?string $countryOfOrigin = null): self
    {
        // validation for constraint: string
        if (!is_null($countryOfOrigin) && !is_string($countryOfOrigin)) {
            throw new InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($countryOfOrigin, true), gettype($countryOfOrigin)), __LINE__);
        }
        // validation for constraint: maxLength(16)
        if (!is_null($countryOfOrigin) && mb_strlen((string) $countryOfOrigin) > 16) {
            throw new InvalidArgumentException(sprintf('Invalid length of %s, the number of characters/octets contained by the literal must be less than or equal to 16', mb_strlen((string) $countryOfOrigin)), __LINE__);
        }
        $this->countryOfOrigin = $countryOfOrigin;
        
        return $this;
    }
    /**
     * Get weight value
     * @return int|null
     */
    public function getWeight(): ?int
    {
        return $this->weight;
    }
    /**
     * Set weight value
     * @param int $weight
     * @return \WpifyWoo\PacketeraSDK\StructType\CustomsDeclarationItem
     */
    public function setWeight(?int $weight = null): self
    {
        // validation for constraint: int
        if (!is_null($weight) && !(is_int($weight) || ctype_digit($weight))) {
            throw new InvalidArgumentException(sprintf('Invalid value %s, please provide an integer value, %s given', var_export($weight, true), gettype($weight)), __LINE__);
        }
        $this->weight = $weight;
        
        return $this;
    }
    /**
     * Get isFoodBook value
     * @return bool|null
     */
    public function getIsFoodBook(): ?bool
    {
        return $this->isFoodBook;
    }
    /**
     * Set isFoodBook value
     * @param bool $isFoodBook
     * @return \WpifyWoo\PacketeraSDK\StructType\CustomsDeclarationItem
     */
    public function setIsFoodBook(?bool $isFoodBook = false): self
    {
        // validation for constraint: boolean
        if (!is_null($isFoodBook) && !is_bool($isFoodBook)) {
            throw new InvalidArgumentException(sprintf('Invalid value %s, please provide a bool, %s given', var_export($isFoodBook, true), gettype($isFoodBook)), __LINE__);
        }
        $this->isFoodBook = $isFoodBook;
        
        return $this;
    }
    /**
     * Get isVoc value
     * @return bool|null
     */
    public function getIsVoc(): ?bool
    {
        return $this->isVoc;
    }
    /**
     * Set isVoc value
     * @param bool $isVoc
     * @return \WpifyWoo\PacketeraSDK\StructType\CustomsDeclarationItem
     */
    public function setIsVoc(?bool $isVoc = false): self
    {
        // validation for constraint: boolean
        if (!is_null($isVoc) && !is_bool($isVoc)) {
            throw new InvalidArgumentException(sprintf('Invalid value %s, please provide a bool, %s given', var_export($isVoc, true), gettype($isVoc)), __LINE__);
        }
        $this->isVoc = $isVoc;
        
        return $this;
    }
}
