<?php

declare(strict_types=1);

namespace WpifyWoo\PacketeraSDK\StructType;

use InvalidArgumentException;
use WsdlToPhp\PackageBase\AbstractStructBase;

/**
 * This class stands for PacketAttributes StructType
 * @subpackage Structs
 */
class PacketAttributes extends AbstractStructBase
{
    /**
     * The id
     * Meta information extracted from the WSDL
     * - nillable: true
     * @var string|null
     */
    protected ?string $id = null;
    /**
     * The number
     * Meta information extracted from the WSDL
     * - base: xsd:string
     * - maxLength: 24
     * @var string|null
     */
    protected ?string $number = null;
    /**
     * The name
     * Meta information extracted from the WSDL
     * - base: xsd:string
     * - maxLength: 32
     * @var string|null
     */
    protected ?string $name = null;
    /**
     * The surname
     * Meta information extracted from the WSDL
     * - base: xsd:string
     * - maxLength: 32
     * @var string|null
     */
    protected ?string $surname = null;
    /**
     * The company
     * Meta information extracted from the WSDL
     * - base: xsd:string
     * - maxLength: 32
     * - nillable: true
     * @var string|null
     */
    protected ?string $company = null;
    /**
     * The email
     * Meta information extracted from the WSDL
     * - base: xsd:string
     * - nillable: true
     * - pattern: |.*@.*\..*
     * @var string|null
     */
    protected ?string $email = null;
    /**
     * The phone
     * Meta information extracted from the WSDL
     * - base: xsd:string
     * - nillable: true
     * - pattern: \s*(\+?42[01])?\s*[0-9]{3}\s*[0-9]{3}\s*[0-9]{3}\s*
     * @var string|null
     */
    protected ?string $phone = null;
    /**
     * The addressId
     * @var int|null
     */
    protected ?int $addressId = null;
    /**
     * The cod
     * Meta information extracted from the WSDL
     * - base: xsd:decimal
     * - default: 0
     * - fractionDigits: 2
     * - nillable: true
     * @var float|null
     */
    protected ?float $cod = null;
    /**
     * The currency
     * Meta information extracted from the WSDL
     * - base: xsd:string
     * - length: 3
     * - nillable: true
     * @var string|null
     */
    protected ?string $currency = null;
    /**
     * The value
     * Meta information extracted from the WSDL
     * - base: xsd:decimal
     * - fractionDigits: 2
     * @var float|null
     */
    protected ?float $value = null;
    /**
     * The weight
     * Meta information extracted from the WSDL
     * - base: xsd:decimal
     * - fractionDigits: 3
     * @var float|null
     */
    protected ?float $weight = null;
    /**
     * The eshop
     * Meta information extracted from the WSDL
     * - base: xsd:string
     * - maxLength: 64
     * - nillable: true
     * @var string|null
     */
    protected ?string $eshop = null;
    /**
     * The adultContent
     * Meta information extracted from the WSDL
     * - default: 0
     * - nillable: true
     * @var int|null
     */
    protected ?int $adultContent = null;
    /**
     * The deliverOn
     * Meta information extracted from the WSDL
     * - nillable: true
     * @var string|null
     */
    protected ?string $deliverOn = null;
    /**
     * The note
     * Meta information extracted from the WSDL
     * - nillable: true
     * @var string|null
     */
    protected ?string $note = null;
    /**
     * The street
     * Meta information extracted from the WSDL
     * - base: xsd:string
     * - maxLength: 64
     * - nillable: true
     * @var string|null
     */
    protected ?string $street = null;
    /**
     * The houseNumber
     * Meta information extracted from the WSDL
     * - base: xsd:string
     * - maxLength: 16
     * - nillable: true
     * @var string|null
     */
    protected ?string $houseNumber = null;
    /**
     * The city
     * Meta information extracted from the WSDL
     * - base: xsd:string
     * - maxLength: 32
     * - nillable: true
     * @var string|null
     */
    protected ?string $city = null;
    /**
     * The province
     * Meta information extracted from the WSDL
     * - base: xsd:string
     * - maxLength: 32
     * - nillable: true
     * @var string|null
     */
    protected ?string $province = null;
    /**
     * The zip
     * Meta information extracted from the WSDL
     * - base: xsd:string
     * - nillable: true
     * - pattern: \s*[0-9]{3} ?[0-9]{2}\s*
     * @var string|null
     */
    protected ?string $zip = null;
    /**
     * The dispatchOrder
     * Meta information extracted from the WSDL
     * - nillable: true
     * @var \WpifyWoo\PacketeraSDK\StructType\DispatchOrder|null
     */
    protected ?\WpifyWoo\PacketeraSDK\StructType\DispatchOrder $dispatchOrder = null;
    /**
     * The customerBarcode
     * Meta information extracted from the WSDL
     * - base: xsd:string
     * - maxLength: 32
     * - nillable: true
     * @var string|null
     */
    protected ?string $customerBarcode = null;
    /**
     * The carrierPickupPoint
     * Meta information extracted from the WSDL
     * - base: xsd:string
     * - maxLength: 64
     * - nillable: true
     * @var string|null
     */
    protected ?string $carrierPickupPoint = null;
    /**
     * The carrierService
     * Meta information extracted from the WSDL
     * - nillable: true
     * @var string|null
     */
    protected ?string $carrierService = null;
    /**
     * The customsDeclaration
     * Meta information extracted from the WSDL
     * - nillable: true
     * @var \WpifyWoo\PacketeraSDK\StructType\CustomsDeclaration|null
     */
    protected ?\WpifyWoo\PacketeraSDK\StructType\CustomsDeclaration $customsDeclaration = null;
    /**
     * The size
     * Meta information extracted from the WSDL
     * - nillable: true
     * @var \WpifyWoo\PacketeraSDK\StructType\Size|null
     */
    protected ?\WpifyWoo\PacketeraSDK\StructType\Size $size = null;
    /**
     * The attributes
     * Meta information extracted from the WSDL
     * - minOccurs: 0
     * @var \WpifyWoo\PacketeraSDK\StructType\AttributeCollection|null
     */
    protected ?\WpifyWoo\PacketeraSDK\StructType\AttributeCollection $attributes = null;
    /**
     * The items
     * Meta information extracted from the WSDL
     * - minOccurs: 0
     * @var \WpifyWoo\PacketeraSDK\StructType\ItemCollection|null
     */
    protected ?\WpifyWoo\PacketeraSDK\StructType\ItemCollection $items = null;
    /**
     * Constructor method for PacketAttributes
     * @uses PacketAttributes::setId()
     * @uses PacketAttributes::setNumber()
     * @uses PacketAttributes::setName()
     * @uses PacketAttributes::setSurname()
     * @uses PacketAttributes::setCompany()
     * @uses PacketAttributes::setEmail()
     * @uses PacketAttributes::setPhone()
     * @uses PacketAttributes::setAddressId()
     * @uses PacketAttributes::setCod()
     * @uses PacketAttributes::setCurrency()
     * @uses PacketAttributes::setValue()
     * @uses PacketAttributes::setWeight()
     * @uses PacketAttributes::setEshop()
     * @uses PacketAttributes::setAdultContent()
     * @uses PacketAttributes::setDeliverOn()
     * @uses PacketAttributes::setNote()
     * @uses PacketAttributes::setStreet()
     * @uses PacketAttributes::setHouseNumber()
     * @uses PacketAttributes::setCity()
     * @uses PacketAttributes::setProvince()
     * @uses PacketAttributes::setZip()
     * @uses PacketAttributes::setDispatchOrder()
     * @uses PacketAttributes::setCustomerBarcode()
     * @uses PacketAttributes::setCarrierPickupPoint()
     * @uses PacketAttributes::setCarrierService()
     * @uses PacketAttributes::setCustomsDeclaration()
     * @uses PacketAttributes::setSize()
     * @uses PacketAttributes::setAttributes()
     * @uses PacketAttributes::setItems()
     * @param string $id
     * @param string $number
     * @param string $name
     * @param string $surname
     * @param string $company
     * @param string $email
     * @param string $phone
     * @param int $addressId
     * @param float $cod
     * @param string $currency
     * @param float $value
     * @param float $weight
     * @param string $eshop
     * @param int $adultContent
     * @param string $deliverOn
     * @param string $note
     * @param string $street
     * @param string $houseNumber
     * @param string $city
     * @param string $province
     * @param string $zip
     * @param \WpifyWoo\PacketeraSDK\StructType\DispatchOrder $dispatchOrder
     * @param string $customerBarcode
     * @param string $carrierPickupPoint
     * @param string $carrierService
     * @param \WpifyWoo\PacketeraSDK\StructType\CustomsDeclaration $customsDeclaration
     * @param \WpifyWoo\PacketeraSDK\StructType\Size $size
     * @param \WpifyWoo\PacketeraSDK\StructType\AttributeCollection $attributes
     * @param \WpifyWoo\PacketeraSDK\StructType\ItemCollection $items
     */
    public function __construct(?string $id = null, ?string $number = null, ?string $name = null, ?string $surname = null, ?string $company = null, ?string $email = null, ?string $phone = null, ?int $addressId = null, ?float $cod = 0, ?string $currency = null, ?float $value = null, ?float $weight = null, ?string $eshop = null, ?int $adultContent = 0, ?string $deliverOn = null, ?string $note = null, ?string $street = null, ?string $houseNumber = null, ?string $city = null, ?string $province = null, ?string $zip = null, ?\WpifyWoo\PacketeraSDK\StructType\DispatchOrder $dispatchOrder = null, ?string $customerBarcode = null, ?string $carrierPickupPoint = null, ?string $carrierService = null, ?\WpifyWoo\PacketeraSDK\StructType\CustomsDeclaration $customsDeclaration = null, ?\WpifyWoo\PacketeraSDK\StructType\Size $size = null, ?\WpifyWoo\PacketeraSDK\StructType\AttributeCollection $attributes = null, ?\WpifyWoo\PacketeraSDK\StructType\ItemCollection $items = null)
    {
        $this
            ->setId($id)
            ->setNumber($number)
            ->setName($name)
            ->setSurname($surname)
            ->setCompany($company)
            ->setEmail($email)
            ->setPhone($phone)
            ->setAddressId($addressId)
            ->setCod($cod)
            ->setCurrency($currency)
            ->setValue($value)
            ->setWeight($weight)
            ->setEshop($eshop)
            ->setAdultContent($adultContent)
            ->setDeliverOn($deliverOn)
            ->setNote($note)
            ->setStreet($street)
            ->setHouseNumber($houseNumber)
            ->setCity($city)
            ->setProvince($province)
            ->setZip($zip)
            ->setDispatchOrder($dispatchOrder)
            ->setCustomerBarcode($customerBarcode)
            ->setCarrierPickupPoint($carrierPickupPoint)
            ->setCarrierService($carrierService)
            ->setCustomsDeclaration($customsDeclaration)
            ->setSize($size)
            ->setAttributes($attributes)
            ->setItems($items);
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
     * @return \WpifyWoo\PacketeraSDK\StructType\PacketAttributes
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
     * Get number value
     * @return string|null
     */
    public function getNumber(): ?string
    {
        return $this->number;
    }
    /**
     * Set number value
     * @param string $number
     * @return \WpifyWoo\PacketeraSDK\StructType\PacketAttributes
     */
    public function setNumber(?string $number = null): self
    {
        // validation for constraint: string
        if (!is_null($number) && !is_string($number)) {
            throw new InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($number, true), gettype($number)), __LINE__);
        }
        // validation for constraint: maxLength(24)
        if (!is_null($number) && mb_strlen((string) $number) > 24) {
            throw new InvalidArgumentException(sprintf('Invalid length of %s, the number of characters/octets contained by the literal must be less than or equal to 24', mb_strlen((string) $number)), __LINE__);
        }
        $this->number = $number;
        
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
     * @return \WpifyWoo\PacketeraSDK\StructType\PacketAttributes
     */
    public function setName(?string $name = null): self
    {
        // validation for constraint: string
        if (!is_null($name) && !is_string($name)) {
            throw new InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($name, true), gettype($name)), __LINE__);
        }
        // validation for constraint: maxLength(32)
        if (!is_null($name) && mb_strlen((string) $name) > 32) {
            throw new InvalidArgumentException(sprintf('Invalid length of %s, the number of characters/octets contained by the literal must be less than or equal to 32', mb_strlen((string) $name)), __LINE__);
        }
        $this->name = $name;
        
        return $this;
    }
    /**
     * Get surname value
     * @return string|null
     */
    public function getSurname(): ?string
    {
        return $this->surname;
    }
    /**
     * Set surname value
     * @param string $surname
     * @return \WpifyWoo\PacketeraSDK\StructType\PacketAttributes
     */
    public function setSurname(?string $surname = null): self
    {
        // validation for constraint: string
        if (!is_null($surname) && !is_string($surname)) {
            throw new InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($surname, true), gettype($surname)), __LINE__);
        }
        // validation for constraint: maxLength(32)
        if (!is_null($surname) && mb_strlen((string) $surname) > 32) {
            throw new InvalidArgumentException(sprintf('Invalid length of %s, the number of characters/octets contained by the literal must be less than or equal to 32', mb_strlen((string) $surname)), __LINE__);
        }
        $this->surname = $surname;
        
        return $this;
    }
    /**
     * Get company value
     * @return string|null
     */
    public function getCompany(): ?string
    {
        return $this->company;
    }
    /**
     * Set company value
     * @param string $company
     * @return \WpifyWoo\PacketeraSDK\StructType\PacketAttributes
     */
    public function setCompany(?string $company = null): self
    {
        // validation for constraint: string
        if (!is_null($company) && !is_string($company)) {
            throw new InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($company, true), gettype($company)), __LINE__);
        }
        // validation for constraint: maxLength(32)
        if (!is_null($company) && mb_strlen((string) $company) > 32) {
            throw new InvalidArgumentException(sprintf('Invalid length of %s, the number of characters/octets contained by the literal must be less than or equal to 32', mb_strlen((string) $company)), __LINE__);
        }
        $this->company = $company;
        
        return $this;
    }
    /**
     * Get email value
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }
    /**
     * Set email value
     * @param string $email
     * @return \WpifyWoo\PacketeraSDK\StructType\PacketAttributes
     */
    public function setEmail(?string $email = null): self
    {
        // validation for constraint: string
        if (!is_null($email) && !is_string($email)) {
            throw new InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($email, true), gettype($email)), __LINE__);
        }
        // validation for constraint: pattern(|.*@.*\..*)
        if (!is_null($email) && !preg_match('/|.*@.*\\..*/', $email)) {
            throw new InvalidArgumentException(sprintf('Invalid value %s, please provide a literal that is among the set of character sequences denoted by the regular expression /|.*@.*\\..*/', var_export($email, true)), __LINE__);
        }
        $this->email = $email;
        
        return $this;
    }
    /**
     * Get phone value
     * @return string|null
     */
    public function getPhone(): ?string
    {
        return $this->phone;
    }
    /**
     * Set phone value
     * @param string $phone
     * @return \WpifyWoo\PacketeraSDK\StructType\PacketAttributes
     */
    public function setPhone(?string $phone = null): self
    {
        // validation for constraint: string
        if (!is_null($phone) && !is_string($phone)) {
            throw new InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($phone, true), gettype($phone)), __LINE__);
        }
        // validation for constraint: pattern(\s*(\+?42[01])?\s*[0-9]{3}\s*[0-9]{3}\s*[0-9]{3}\s*)
        if (!is_null($phone) && !preg_match('/\\s*(\\+?42[01])?\\s*[0-9]{3}\\s*[0-9]{3}\\s*[0-9]{3}\\s*/', $phone)) {
            throw new InvalidArgumentException(sprintf('Invalid value %s, please provide a literal that is among the set of character sequences denoted by the regular expression /\\s*(\\+?42[01])?\\s*[0-9]{3}\\s*[0-9]{3}\\s*[0-9]{3}\\s*/', var_export($phone, true)), __LINE__);
        }
        $this->phone = $phone;
        
        return $this;
    }
    /**
     * Get addressId value
     * @return int|null
     */
    public function getAddressId(): ?int
    {
        return $this->addressId;
    }
    /**
     * Set addressId value
     * @param int $addressId
     * @return \WpifyWoo\PacketeraSDK\StructType\PacketAttributes
     */
    public function setAddressId(?int $addressId = null): self
    {
        // validation for constraint: int
        if (!is_null($addressId) && !(is_int($addressId) || ctype_digit($addressId))) {
            throw new InvalidArgumentException(sprintf('Invalid value %s, please provide an integer value, %s given', var_export($addressId, true), gettype($addressId)), __LINE__);
        }
        $this->addressId = $addressId;
        
        return $this;
    }
    /**
     * Get cod value
     * @return float|null
     */
    public function getCod(): ?float
    {
        return $this->cod;
    }
    /**
     * Set cod value
     * @param float $cod
     * @return \WpifyWoo\PacketeraSDK\StructType\PacketAttributes
     */
    public function setCod(?float $cod = 0): self
    {
        // validation for constraint: float
        if (!is_null($cod) && !(is_float($cod) || is_numeric($cod))) {
            throw new InvalidArgumentException(sprintf('Invalid value %s, please provide a float value, %s given', var_export($cod, true), gettype($cod)), __LINE__);
        }
        // validation for constraint: fractionDigits(2)
        if (!is_null($cod) && mb_strlen(mb_substr((string) $cod, false !== mb_strpos((string) $cod, '.') ? mb_strpos((string) $cod, '.') + 1 : mb_strlen((string) $cod))) > 2) {
            throw new InvalidArgumentException(sprintf('Invalid value %s, the value must at most contain 2 fraction digits, %d given', var_export($cod, true), mb_strlen(mb_substr((string) $cod, mb_strpos((string) $cod, '.') + 1))), __LINE__);
        }
        $this->cod = $cod;
        
        return $this;
    }
    /**
     * Get currency value
     * @return string|null
     */
    public function getCurrency(): ?string
    {
        return $this->currency;
    }
    /**
     * Set currency value
     * @param string $currency
     * @return \WpifyWoo\PacketeraSDK\StructType\PacketAttributes
     */
    public function setCurrency(?string $currency = null): self
    {
        // validation for constraint: string
        if (!is_null($currency) && !is_string($currency)) {
            throw new InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($currency, true), gettype($currency)), __LINE__);
        }
        // validation for constraint: length(3)
        if (!is_null($currency) && mb_strlen((string) $currency) !== 3) {
            throw new InvalidArgumentException(sprintf('Invalid length of %s, the number of characters/octets contained by the literal must be equal to 3', mb_strlen((string) $currency)), __LINE__);
        }
        $this->currency = $currency;
        
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
     * @return \WpifyWoo\PacketeraSDK\StructType\PacketAttributes
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
     * Get weight value
     * @return float|null
     */
    public function getWeight(): ?float
    {
        return $this->weight;
    }
    /**
     * Set weight value
     * @param float $weight
     * @return \WpifyWoo\PacketeraSDK\StructType\PacketAttributes
     */
    public function setWeight(?float $weight = null): self
    {
        // validation for constraint: float
        if (!is_null($weight) && !(is_float($weight) || is_numeric($weight))) {
            throw new InvalidArgumentException(sprintf('Invalid value %s, please provide a float value, %s given', var_export($weight, true), gettype($weight)), __LINE__);
        }
        // validation for constraint: fractionDigits(3)
        if (!is_null($weight) && mb_strlen(mb_substr((string) $weight, false !== mb_strpos((string) $weight, '.') ? mb_strpos((string) $weight, '.') + 1 : mb_strlen((string) $weight))) > 3) {
            throw new InvalidArgumentException(sprintf('Invalid value %s, the value must at most contain 3 fraction digits, %d given', var_export($weight, true), mb_strlen(mb_substr((string) $weight, mb_strpos((string) $weight, '.') + 1))), __LINE__);
        }
        $this->weight = $weight;
        
        return $this;
    }
    /**
     * Get eshop value
     * @return string|null
     */
    public function getEshop(): ?string
    {
        return $this->eshop;
    }
    /**
     * Set eshop value
     * @param string $eshop
     * @return \WpifyWoo\PacketeraSDK\StructType\PacketAttributes
     */
    public function setEshop(?string $eshop = null): self
    {
        // validation for constraint: string
        if (!is_null($eshop) && !is_string($eshop)) {
            throw new InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($eshop, true), gettype($eshop)), __LINE__);
        }
        // validation for constraint: maxLength(64)
        if (!is_null($eshop) && mb_strlen((string) $eshop) > 64) {
            throw new InvalidArgumentException(sprintf('Invalid length of %s, the number of characters/octets contained by the literal must be less than or equal to 64', mb_strlen((string) $eshop)), __LINE__);
        }
        $this->eshop = $eshop;
        
        return $this;
    }
    /**
     * Get adultContent value
     * @return int|null
     */
    public function getAdultContent(): ?int
    {
        return $this->adultContent;
    }
    /**
     * Set adultContent value
     * @param int $adultContent
     * @return \WpifyWoo\PacketeraSDK\StructType\PacketAttributes
     */
    public function setAdultContent(?int $adultContent = 0): self
    {
        // validation for constraint: int
        if (!is_null($adultContent) && !(is_int($adultContent) || ctype_digit($adultContent))) {
            throw new InvalidArgumentException(sprintf('Invalid value %s, please provide an integer value, %s given', var_export($adultContent, true), gettype($adultContent)), __LINE__);
        }
        $this->adultContent = $adultContent;
        
        return $this;
    }
    /**
     * Get deliverOn value
     * @return string|null
     */
    public function getDeliverOn(): ?string
    {
        return $this->deliverOn;
    }
    /**
     * Set deliverOn value
     * @param string $deliverOn
     * @return \WpifyWoo\PacketeraSDK\StructType\PacketAttributes
     */
    public function setDeliverOn(?string $deliverOn = null): self
    {
        // validation for constraint: string
        if (!is_null($deliverOn) && !is_string($deliverOn)) {
            throw new InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($deliverOn, true), gettype($deliverOn)), __LINE__);
        }
        $this->deliverOn = $deliverOn;
        
        return $this;
    }
    /**
     * Get note value
     * @return string|null
     */
    public function getNote(): ?string
    {
        return $this->note;
    }
    /**
     * Set note value
     * @param string $note
     * @return \WpifyWoo\PacketeraSDK\StructType\PacketAttributes
     */
    public function setNote(?string $note = null): self
    {
        // validation for constraint: string
        if (!is_null($note) && !is_string($note)) {
            throw new InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($note, true), gettype($note)), __LINE__);
        }
        $this->note = $note;
        
        return $this;
    }
    /**
     * Get street value
     * @return string|null
     */
    public function getStreet(): ?string
    {
        return $this->street;
    }
    /**
     * Set street value
     * @param string $street
     * @return \WpifyWoo\PacketeraSDK\StructType\PacketAttributes
     */
    public function setStreet(?string $street = null): self
    {
        // validation for constraint: string
        if (!is_null($street) && !is_string($street)) {
            throw new InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($street, true), gettype($street)), __LINE__);
        }
        // validation for constraint: maxLength(64)
        if (!is_null($street) && mb_strlen((string) $street) > 64) {
            throw new InvalidArgumentException(sprintf('Invalid length of %s, the number of characters/octets contained by the literal must be less than or equal to 64', mb_strlen((string) $street)), __LINE__);
        }
        $this->street = $street;
        
        return $this;
    }
    /**
     * Get houseNumber value
     * @return string|null
     */
    public function getHouseNumber(): ?string
    {
        return $this->houseNumber;
    }
    /**
     * Set houseNumber value
     * @param string $houseNumber
     * @return \WpifyWoo\PacketeraSDK\StructType\PacketAttributes
     */
    public function setHouseNumber(?string $houseNumber = null): self
    {
        // validation for constraint: string
        if (!is_null($houseNumber) && !is_string($houseNumber)) {
            throw new InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($houseNumber, true), gettype($houseNumber)), __LINE__);
        }
        // validation for constraint: maxLength(16)
        if (!is_null($houseNumber) && mb_strlen((string) $houseNumber) > 16) {
            throw new InvalidArgumentException(sprintf('Invalid length of %s, the number of characters/octets contained by the literal must be less than or equal to 16', mb_strlen((string) $houseNumber)), __LINE__);
        }
        $this->houseNumber = $houseNumber;
        
        return $this;
    }
    /**
     * Get city value
     * @return string|null
     */
    public function getCity(): ?string
    {
        return $this->city;
    }
    /**
     * Set city value
     * @param string $city
     * @return \WpifyWoo\PacketeraSDK\StructType\PacketAttributes
     */
    public function setCity(?string $city = null): self
    {
        // validation for constraint: string
        if (!is_null($city) && !is_string($city)) {
            throw new InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($city, true), gettype($city)), __LINE__);
        }
        // validation for constraint: maxLength(32)
        if (!is_null($city) && mb_strlen((string) $city) > 32) {
            throw new InvalidArgumentException(sprintf('Invalid length of %s, the number of characters/octets contained by the literal must be less than or equal to 32', mb_strlen((string) $city)), __LINE__);
        }
        $this->city = $city;
        
        return $this;
    }
    /**
     * Get province value
     * @return string|null
     */
    public function getProvince(): ?string
    {
        return $this->province;
    }
    /**
     * Set province value
     * @param string $province
     * @return \WpifyWoo\PacketeraSDK\StructType\PacketAttributes
     */
    public function setProvince(?string $province = null): self
    {
        // validation for constraint: string
        if (!is_null($province) && !is_string($province)) {
            throw new InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($province, true), gettype($province)), __LINE__);
        }
        // validation for constraint: maxLength(32)
        if (!is_null($province) && mb_strlen((string) $province) > 32) {
            throw new InvalidArgumentException(sprintf('Invalid length of %s, the number of characters/octets contained by the literal must be less than or equal to 32', mb_strlen((string) $province)), __LINE__);
        }
        $this->province = $province;
        
        return $this;
    }
    /**
     * Get zip value
     * @return string|null
     */
    public function getZip(): ?string
    {
        return $this->zip;
    }
    /**
     * Set zip value
     * @param string $zip
     * @return \WpifyWoo\PacketeraSDK\StructType\PacketAttributes
     */
    public function setZip(?string $zip = null): self
    {
        // validation for constraint: string
        if (!is_null($zip) && !is_string($zip)) {
            throw new InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($zip, true), gettype($zip)), __LINE__);
        }
        // validation for constraint: pattern(\s*[0-9]{3} ?[0-9]{2}\s*)
        if (!is_null($zip) && !preg_match('/\\s*[0-9]{3} ?[0-9]{2}\\s*/', $zip)) {
            throw new InvalidArgumentException(sprintf('Invalid value %s, please provide a literal that is among the set of character sequences denoted by the regular expression /\\s*[0-9]{3} ?[0-9]{2}\\s*/', var_export($zip, true)), __LINE__);
        }
        $this->zip = $zip;
        
        return $this;
    }
    /**
     * Get dispatchOrder value
     * @return \WpifyWoo\PacketeraSDK\StructType\DispatchOrder|null
     */
    public function getDispatchOrder(): ?\WpifyWoo\PacketeraSDK\StructType\DispatchOrder
    {
        return $this->dispatchOrder;
    }
    /**
     * Set dispatchOrder value
     * @param \WpifyWoo\PacketeraSDK\StructType\DispatchOrder $dispatchOrder
     * @return \WpifyWoo\PacketeraSDK\StructType\PacketAttributes
     */
    public function setDispatchOrder(?\WpifyWoo\PacketeraSDK\StructType\DispatchOrder $dispatchOrder = null): self
    {
        $this->dispatchOrder = $dispatchOrder;
        
        return $this;
    }
    /**
     * Get customerBarcode value
     * @return string|null
     */
    public function getCustomerBarcode(): ?string
    {
        return $this->customerBarcode;
    }
    /**
     * Set customerBarcode value
     * @param string $customerBarcode
     * @return \WpifyWoo\PacketeraSDK\StructType\PacketAttributes
     */
    public function setCustomerBarcode(?string $customerBarcode = null): self
    {
        // validation for constraint: string
        if (!is_null($customerBarcode) && !is_string($customerBarcode)) {
            throw new InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($customerBarcode, true), gettype($customerBarcode)), __LINE__);
        }
        // validation for constraint: maxLength(32)
        if (!is_null($customerBarcode) && mb_strlen((string) $customerBarcode) > 32) {
            throw new InvalidArgumentException(sprintf('Invalid length of %s, the number of characters/octets contained by the literal must be less than or equal to 32', mb_strlen((string) $customerBarcode)), __LINE__);
        }
        $this->customerBarcode = $customerBarcode;
        
        return $this;
    }
    /**
     * Get carrierPickupPoint value
     * @return string|null
     */
    public function getCarrierPickupPoint(): ?string
    {
        return $this->carrierPickupPoint;
    }
    /**
     * Set carrierPickupPoint value
     * @param string $carrierPickupPoint
     * @return \WpifyWoo\PacketeraSDK\StructType\PacketAttributes
     */
    public function setCarrierPickupPoint(?string $carrierPickupPoint = null): self
    {
        // validation for constraint: string
        if (!is_null($carrierPickupPoint) && !is_string($carrierPickupPoint)) {
            throw new InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($carrierPickupPoint, true), gettype($carrierPickupPoint)), __LINE__);
        }
        // validation for constraint: maxLength(64)
        if (!is_null($carrierPickupPoint) && mb_strlen((string) $carrierPickupPoint) > 64) {
            throw new InvalidArgumentException(sprintf('Invalid length of %s, the number of characters/octets contained by the literal must be less than or equal to 64', mb_strlen((string) $carrierPickupPoint)), __LINE__);
        }
        $this->carrierPickupPoint = $carrierPickupPoint;
        
        return $this;
    }
    /**
     * Get carrierService value
     * @return string|null
     */
    public function getCarrierService(): ?string
    {
        return $this->carrierService;
    }
    /**
     * Set carrierService value
     * @param string $carrierService
     * @return \WpifyWoo\PacketeraSDK\StructType\PacketAttributes
     */
    public function setCarrierService(?string $carrierService = null): self
    {
        // validation for constraint: string
        if (!is_null($carrierService) && !is_string($carrierService)) {
            throw new InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($carrierService, true), gettype($carrierService)), __LINE__);
        }
        $this->carrierService = $carrierService;
        
        return $this;
    }
    /**
     * Get customsDeclaration value
     * @return \WpifyWoo\PacketeraSDK\StructType\CustomsDeclaration|null
     */
    public function getCustomsDeclaration(): ?\WpifyWoo\PacketeraSDK\StructType\CustomsDeclaration
    {
        return $this->customsDeclaration;
    }
    /**
     * Set customsDeclaration value
     * @param \WpifyWoo\PacketeraSDK\StructType\CustomsDeclaration $customsDeclaration
     * @return \WpifyWoo\PacketeraSDK\StructType\PacketAttributes
     */
    public function setCustomsDeclaration(?\WpifyWoo\PacketeraSDK\StructType\CustomsDeclaration $customsDeclaration = null): self
    {
        $this->customsDeclaration = $customsDeclaration;
        
        return $this;
    }
    /**
     * Get size value
     * @return \WpifyWoo\PacketeraSDK\StructType\Size|null
     */
    public function getSize(): ?\WpifyWoo\PacketeraSDK\StructType\Size
    {
        return $this->size;
    }
    /**
     * Set size value
     * @param \WpifyWoo\PacketeraSDK\StructType\Size $size
     * @return \WpifyWoo\PacketeraSDK\StructType\PacketAttributes
     */
    public function setSize(?\WpifyWoo\PacketeraSDK\StructType\Size $size = null): self
    {
        $this->size = $size;
        
        return $this;
    }
    /**
     * Get attributes value
     * @return \WpifyWoo\PacketeraSDK\StructType\AttributeCollection|null
     */
    public function getAttributes(): ?\WpifyWoo\PacketeraSDK\StructType\AttributeCollection
    {
        return $this->attributes;
    }
    /**
     * Set attributes value
     * @param \WpifyWoo\PacketeraSDK\StructType\AttributeCollection $attributes
     * @return \WpifyWoo\PacketeraSDK\StructType\PacketAttributes
     */
    public function setAttributes(?\WpifyWoo\PacketeraSDK\StructType\AttributeCollection $attributes = null): self
    {
        $this->attributes = $attributes;
        
        return $this;
    }
    /**
     * Get items value
     * @return \WpifyWoo\PacketeraSDK\StructType\ItemCollection|null
     */
    public function getItems(): ?\WpifyWoo\PacketeraSDK\StructType\ItemCollection
    {
        return $this->items;
    }
    /**
     * Set items value
     * @param \WpifyWoo\PacketeraSDK\StructType\ItemCollection $items
     * @return \WpifyWoo\PacketeraSDK\StructType\PacketAttributes
     */
    public function setItems(?\WpifyWoo\PacketeraSDK\StructType\ItemCollection $items = null): self
    {
        $this->items = $items;
        
        return $this;
    }
}
