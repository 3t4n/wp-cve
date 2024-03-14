<?php

declare(strict_types=1);

namespace WpifyWoo\PacketeraSDK\StructType;

use InvalidArgumentException;
use WsdlToPhp\PackageBase\AbstractStructBase;

/**
 * This class stands for PacketLiftagoAttributes StructType
 * @subpackage Structs
 */
class PacketLiftagoAttributes extends AbstractStructBase
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
     * - nillable: true
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
     * The carrierService
     * Meta information extracted from the WSDL
     * - nillable: true
     * @var string|null
     */
    protected ?string $carrierService = null;
    /**
     * The pickupAddress
     * @var \WpifyWoo\PacketeraSDK\StructType\Contact|null
     */
    protected ?\WpifyWoo\PacketeraSDK\StructType\Contact $pickupAddress = null;
    /**
     * Constructor method for PacketLiftagoAttributes
     * @uses PacketLiftagoAttributes::setId()
     * @uses PacketLiftagoAttributes::setNumber()
     * @uses PacketLiftagoAttributes::setName()
     * @uses PacketLiftagoAttributes::setSurname()
     * @uses PacketLiftagoAttributes::setCompany()
     * @uses PacketLiftagoAttributes::setEmail()
     * @uses PacketLiftagoAttributes::setPhone()
     * @uses PacketLiftagoAttributes::setAddressId()
     * @uses PacketLiftagoAttributes::setCurrency()
     * @uses PacketLiftagoAttributes::setValue()
     * @uses PacketLiftagoAttributes::setWeight()
     * @uses PacketLiftagoAttributes::setEshop()
     * @uses PacketLiftagoAttributes::setDeliverOn()
     * @uses PacketLiftagoAttributes::setNote()
     * @uses PacketLiftagoAttributes::setStreet()
     * @uses PacketLiftagoAttributes::setHouseNumber()
     * @uses PacketLiftagoAttributes::setCity()
     * @uses PacketLiftagoAttributes::setProvince()
     * @uses PacketLiftagoAttributes::setZip()
     * @uses PacketLiftagoAttributes::setDispatchOrder()
     * @uses PacketLiftagoAttributes::setCustomerBarcode()
     * @uses PacketLiftagoAttributes::setCarrierService()
     * @uses PacketLiftagoAttributes::setPickupAddress()
     * @param string $id
     * @param string $number
     * @param string $name
     * @param string $surname
     * @param string $company
     * @param string $email
     * @param string $phone
     * @param int $addressId
     * @param string $currency
     * @param float $value
     * @param float $weight
     * @param string $eshop
     * @param string $deliverOn
     * @param string $note
     * @param string $street
     * @param string $houseNumber
     * @param string $city
     * @param string $province
     * @param string $zip
     * @param \WpifyWoo\PacketeraSDK\StructType\DispatchOrder $dispatchOrder
     * @param string $customerBarcode
     * @param string $carrierService
     * @param \WpifyWoo\PacketeraSDK\StructType\Contact $pickupAddress
     */
    public function __construct(?string $id = null, ?string $number = null, ?string $name = null, ?string $surname = null, ?string $company = null, ?string $email = null, ?string $phone = null, ?int $addressId = null, ?string $currency = null, ?float $value = null, ?float $weight = null, ?string $eshop = null, ?string $deliverOn = null, ?string $note = null, ?string $street = null, ?string $houseNumber = null, ?string $city = null, ?string $province = null, ?string $zip = null, ?\WpifyWoo\PacketeraSDK\StructType\DispatchOrder $dispatchOrder = null, ?string $customerBarcode = null, ?string $carrierService = null, ?\WpifyWoo\PacketeraSDK\StructType\Contact $pickupAddress = null)
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
            ->setCurrency($currency)
            ->setValue($value)
            ->setWeight($weight)
            ->setEshop($eshop)
            ->setDeliverOn($deliverOn)
            ->setNote($note)
            ->setStreet($street)
            ->setHouseNumber($houseNumber)
            ->setCity($city)
            ->setProvince($province)
            ->setZip($zip)
            ->setDispatchOrder($dispatchOrder)
            ->setCustomerBarcode($customerBarcode)
            ->setCarrierService($carrierService)
            ->setPickupAddress($pickupAddress);
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
     * @return \WpifyWoo\PacketeraSDK\StructType\PacketLiftagoAttributes
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
     * @return \WpifyWoo\PacketeraSDK\StructType\PacketLiftagoAttributes
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
     * @return \WpifyWoo\PacketeraSDK\StructType\PacketLiftagoAttributes
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
     * @return \WpifyWoo\PacketeraSDK\StructType\PacketLiftagoAttributes
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
     * @return \WpifyWoo\PacketeraSDK\StructType\PacketLiftagoAttributes
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
     * @return \WpifyWoo\PacketeraSDK\StructType\PacketLiftagoAttributes
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
     * @return \WpifyWoo\PacketeraSDK\StructType\PacketLiftagoAttributes
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
     * @return \WpifyWoo\PacketeraSDK\StructType\PacketLiftagoAttributes
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
     * @return \WpifyWoo\PacketeraSDK\StructType\PacketLiftagoAttributes
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
     * @return \WpifyWoo\PacketeraSDK\StructType\PacketLiftagoAttributes
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
     * @return \WpifyWoo\PacketeraSDK\StructType\PacketLiftagoAttributes
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
     * @return \WpifyWoo\PacketeraSDK\StructType\PacketLiftagoAttributes
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
     * @return \WpifyWoo\PacketeraSDK\StructType\PacketLiftagoAttributes
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
     * @return \WpifyWoo\PacketeraSDK\StructType\PacketLiftagoAttributes
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
     * @return \WpifyWoo\PacketeraSDK\StructType\PacketLiftagoAttributes
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
     * @return \WpifyWoo\PacketeraSDK\StructType\PacketLiftagoAttributes
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
     * @return \WpifyWoo\PacketeraSDK\StructType\PacketLiftagoAttributes
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
     * @return \WpifyWoo\PacketeraSDK\StructType\PacketLiftagoAttributes
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
     * @return \WpifyWoo\PacketeraSDK\StructType\PacketLiftagoAttributes
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
     * @return \WpifyWoo\PacketeraSDK\StructType\PacketLiftagoAttributes
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
     * @return \WpifyWoo\PacketeraSDK\StructType\PacketLiftagoAttributes
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
     * @return \WpifyWoo\PacketeraSDK\StructType\PacketLiftagoAttributes
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
     * Get pickupAddress value
     * @return \WpifyWoo\PacketeraSDK\StructType\Contact|null
     */
    public function getPickupAddress(): ?\WpifyWoo\PacketeraSDK\StructType\Contact
    {
        return $this->pickupAddress;
    }
    /**
     * Set pickupAddress value
     * @param \WpifyWoo\PacketeraSDK\StructType\Contact $pickupAddress
     * @return \WpifyWoo\PacketeraSDK\StructType\PacketLiftagoAttributes
     */
    public function setPickupAddress(?\WpifyWoo\PacketeraSDK\StructType\Contact $pickupAddress = null): self
    {
        $this->pickupAddress = $pickupAddress;
        
        return $this;
    }
}
