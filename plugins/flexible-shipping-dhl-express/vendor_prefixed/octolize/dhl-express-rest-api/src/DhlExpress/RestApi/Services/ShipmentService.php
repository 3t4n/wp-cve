<?php

declare (strict_types=1);
namespace DhlVendor\Octolize\DhlExpress\RestApi\Services;

use DateTimeImmutable;
use DhlVendor\Octolize\DhlExpress\RestApi\Client;
use DhlVendor\Octolize\DhlExpress\RestApi\Exceptions\MissingArgumentException;
use DhlVendor\Octolize\DhlExpress\RestApi\ResponseParsers\ShipmentResponseParser;
use DhlVendor\Octolize\DhlExpress\RestApi\ValueObjects\Account;
use DhlVendor\Octolize\DhlExpress\RestApi\ValueObjects\Address;
use DhlVendor\Octolize\DhlExpress\RestApi\ValueObjects\BuyerTypeCode;
use DhlVendor\Octolize\DhlExpress\RestApi\ValueObjects\Contact;
use DhlVendor\Octolize\DhlExpress\RestApi\Exceptions\InvalidArgumentException;
use DhlVendor\Octolize\DhlExpress\RestApi\ValueObjects\CustomerTypeCode;
use DhlVendor\Octolize\DhlExpress\RestApi\ValueObjects\Package;
use DhlVendor\Octolize\DhlExpress\RestApi\ValueObjects\Shipment;
use DhlVendor\Octolize\DhlExpress\RestApi\ValueObjects\Incoterm;
use DhlVendor\Octolize\DhlExpress\RestApi\ValueObjects\ValueAddedService;
class ShipmentService
{
    private \DateTimeImmutable $plannedShippingDateAndTime;
    private bool $isPickupRequested;
    private string $pickupCloseTime;
    private string $pickupLocation;
    private \DhlVendor\Octolize\DhlExpress\RestApi\ValueObjects\Address $pickupAddress;
    private \DhlVendor\Octolize\DhlExpress\RestApi\ValueObjects\Contact $pickupContact;
    private string $productCode;
    private string $localProductCode;
    private \DhlVendor\Octolize\DhlExpress\RestApi\ValueObjects\Address $shipperAddress;
    private \DhlVendor\Octolize\DhlExpress\RestApi\ValueObjects\Contact $shipperContact;
    private \DhlVendor\Octolize\DhlExpress\RestApi\ValueObjects\Address $receiverAddress;
    private \DhlVendor\Octolize\DhlExpress\RestApi\ValueObjects\Contact $receiverContact;
    private bool $getRateEstimates = \false;
    private bool $isCustomsDeclarable = \false;
    private string $description;
    private \DhlVendor\Octolize\DhlExpress\RestApi\ValueObjects\Incoterm $incoterm;
    private \DhlVendor\Octolize\DhlExpress\RestApi\ValueObjects\CustomerTypeCode $shipperTypeCode;
    private \DhlVendor\Octolize\DhlExpress\RestApi\ValueObjects\CustomerTypeCode $receiverTypeCode;
    /** @var ValueAddedService[] */
    private array $valueAddedServices = [];
    protected string $unitOfMeasurement = 'metric';
    /**
     * @var Account[]
     */
    private array $accounts;
    /**
     * @var Package[]
     */
    private array $packages;
    private array $requiredArguments = ['plannedShippingDateAndTime', 'isPickupRequested', 'productCode', 'shipperAddress', 'shipperContact', 'receiverAddress', 'receiverContact', 'accounts', 'packages'];
    private array $lastResponse;
    private const CREATE_SHIPMENT_URL = 'shipments';
    private \DhlVendor\Octolize\DhlExpress\RestApi\Client $client;
    public function __construct(\DhlVendor\Octolize\DhlExpress\RestApi\Client $client)
    {
        $this->client = $client;
    }
    public function createShipment() : \DhlVendor\Octolize\DhlExpress\RestApi\ValueObjects\Shipment
    {
        $this->validateParams();
        $query = $this->prepareQuery();
        $this->lastResponse = $this->client->post(self::CREATE_SHIPMENT_URL, $query);
        return (new \DhlVendor\Octolize\DhlExpress\RestApi\ResponseParsers\ShipmentResponseParser($this->lastResponse))->parse();
    }
    public function getLastRawResponse() : array
    {
        return $this->lastResponse;
    }
    public function setPlannedShippingDateAndTime(\DateTimeImmutable $date) : self
    {
        $this->plannedShippingDateAndTime = $date;
        return $this;
    }
    public function setDescription(string $description) : self
    {
        $this->description = $description;
        return $this;
    }
    /**
     * @param bool $isPickupRequested Please advise if a pickup is needed for this shipment
     * @param string $pickupCloseTime The latest time the location premises is available to dispatch the DHL Express shipment. (HH:MM)
     * @param string $pickupLocation Provides information on where the package should be picked up by DHL courier
     * @return $this
     */
    public function setPickup(bool $isPickupRequested, string $pickupCloseTime = '', string $pickupLocation = '') : self
    {
        $this->isPickupRequested = $isPickupRequested;
        $this->pickupCloseTime = $pickupCloseTime;
        $this->pickupLocation = $pickupLocation;
        return $this;
    }
    public function isCustomsDeclarable(bool $isCustomsDeclarable) : self
    {
        $this->isCustomsDeclarable = $isCustomsDeclarable;
        return $this;
    }
    public function setShipperTypeCode(\DhlVendor\Octolize\DhlExpress\RestApi\ValueObjects\CustomerTypeCode $typeCode) : self
    {
        $this->shipperTypeCode = $typeCode;
        return $this;
    }
    public function setReceiverTypeCode(\DhlVendor\Octolize\DhlExpress\RestApi\ValueObjects\CustomerTypeCode $typeCode) : self
    {
        $this->receiverTypeCode = $typeCode;
        return $this;
    }
    public function setPickupDetails(\DhlVendor\Octolize\DhlExpress\RestApi\ValueObjects\Address $pickupAddress, \DhlVendor\Octolize\DhlExpress\RestApi\ValueObjects\Contact $pickupContact) : self
    {
        $this->pickupAddress = $pickupAddress;
        $this->pickupContact = $pickupContact;
        return $this;
    }
    public function setProductCode(string $productCode) : self
    {
        $this->productCode = $productCode;
        return $this;
    }
    public function setLocalProductCode(string $localProductCode) : self
    {
        $this->localProductCode = $localProductCode;
        return $this;
    }
    /**
     * @param Account[] $accounts
     * @return $this
     * @throws InvalidArgumentException
     */
    public function setAccounts(array $accounts) : self
    {
        foreach ($accounts as $account) {
            if (!$account instanceof \DhlVendor\Octolize\DhlExpress\RestApi\ValueObjects\Account) {
                throw new \DhlVendor\Octolize\DhlExpress\RestApi\Exceptions\InvalidArgumentException("Array should contain values of type Account");
            }
        }
        $this->accounts = $accounts;
        return $this;
    }
    public function setShipperDetails(\DhlVendor\Octolize\DhlExpress\RestApi\ValueObjects\Address $shipperAddress, \DhlVendor\Octolize\DhlExpress\RestApi\ValueObjects\Contact $shipperContact) : self
    {
        $this->shipperAddress = $shipperAddress;
        $this->shipperContact = $shipperContact;
        return $this;
    }
    public function setReceiverDetails(\DhlVendor\Octolize\DhlExpress\RestApi\ValueObjects\Address $receiverAddress, \DhlVendor\Octolize\DhlExpress\RestApi\ValueObjects\Contact $receiverContact) : self
    {
        $this->receiverAddress = $receiverAddress;
        $this->receiverContact = $receiverContact;
        return $this;
    }
    public function setGetRateEstimates(bool $getRateEstimates) : self
    {
        $this->getRateEstimates = $getRateEstimates;
        return $this;
    }
    /**
     * @param array<Package> $packages
     * @return $this
     * @throws InvalidArgumentException
     */
    public function setPackages(array $packages) : self
    {
        foreach ($packages as $package) {
            if (!$package instanceof \DhlVendor\Octolize\DhlExpress\RestApi\ValueObjects\Package) {
                throw new \DhlVendor\Octolize\DhlExpress\RestApi\Exceptions\InvalidArgumentException("Array should contain values of type Package");
            }
        }
        $this->packages = $packages;
        return $this;
    }
    public function setIncoterm(\DhlVendor\Octolize\DhlExpress\RestApi\ValueObjects\Incoterm $incoterm) : self
    {
        $this->incoterm = $incoterm;
        return $this;
    }
    public function setValueAddedServices(array $valueAddedServices) : self
    {
        foreach ($valueAddedServices as $valueAddedService) {
            if (!$valueAddedService instanceof \DhlVendor\Octolize\DhlExpress\RestApi\ValueObjects\ValueAddedService) {
                throw new \DhlVendor\Octolize\DhlExpress\RestApi\Exceptions\InvalidArgumentException("Array should contain values of type ValueAddedService");
            }
        }
        $this->valueAddedServices = $valueAddedServices;
        return $this;
    }
    public function prepareQuery() : array
    {
        $query = ['plannedShippingDateAndTime' => $this->plannedShippingDateAndTime->format('DhlVendor\\Y-m-d\\TH:i:s \\G\\M\\TP'), 'accounts' => $this->prepareAccountsQuery(), 'customerDetails' => ['shipperDetails' => ['postalAddress' => $this->shipperAddress->getAsArray(), 'contactInformation' => $this->shipperContact->getAsArray()], 'receiverDetails' => ['postalAddress' => $this->receiverAddress->getAsArray(), 'contactInformation' => $this->receiverContact->getAsArray()]], 'content' => ['packages' => $this->preparePackagesQuery(), 'unitOfMeasurement' => $this->unitOfMeasurement, 'isCustomsDeclarable' => $this->isCustomsDeclarable, 'incoterm' => (string) $this->incoterm, 'description' => $this->description], 'getRateEstimates' => $this->getRateEstimates, 'productCode' => $this->productCode];
        if (isset($this->shipperTypeCode)) {
            $query['customerDetails']['shipperDetails']['typeCode'] = (string) $this->shipperTypeCode;
        }
        if (isset($this->receiverTypeCode)) {
            $query['customerDetails']['receiverDetails']['typeCode'] = (string) $this->receiverTypeCode;
        }
        if (isset($this->localProductCode) && $this->localProductCode !== '') {
            $query['localProductCode'] = $this->localProductCode;
        }
        if ($this->receiverContact->getEmail() !== '') {
            $query['shipmentNotification'][] = ['typeCode' => 'email', 'languageCountryCode' => $this->receiverAddress->getCountryCode(), 'receiverId' => $this->receiverContact->getEmail()];
        }
        if ($this->isPickupRequested) {
            $query['pickup'] = ['isRequested' => $this->isPickupRequested, 'closeTime' => $this->pickupCloseTime, 'location' => $this->pickupLocation];
            $query['pickup']['pickupDetails'] = ['postalAddress' => $this->pickupAddress->getAsArray(), 'contactInformation' => $this->pickupContact->getAsArray()];
        }
        if (\count($this->valueAddedServices)) {
            $query['valueAddedServices'] = [];
            foreach ($this->valueAddedServices as $valueAddedService) {
                $query['valueAddedServices'][] = $valueAddedService->getAsArray();
            }
        }
        return $query;
    }
    private function prepareAccountsQuery() : array
    {
        $accounts = [];
        /** @var Account $account */
        foreach ($this->accounts as $account) {
            $accounts[] = $account->getAsArray();
        }
        return $accounts;
    }
    private function preparePackagesQuery() : array
    {
        $packages = [];
        foreach ($this->packages as $package) {
            $packages[] = ['weight' => $package->getWeight(), 'dimensions' => ['length' => $package->getLength(), 'width' => $package->getWidth(), 'height' => $package->getHeight()]];
        }
        return $packages;
    }
    /**
     * @return void
     * @throws MissingArgumentException
     */
    private function validateParams() : void
    {
        if (!isset($this->incoterm)) {
            $this->incoterm = new \DhlVendor\Octolize\DhlExpress\RestApi\ValueObjects\Incoterm('');
        }
        foreach ($this->requiredArguments as $param) {
            if (!isset($this->{$param})) {
                throw new \DhlVendor\Octolize\DhlExpress\RestApi\Exceptions\MissingArgumentException("Missing argument: {$param}");
            }
        }
        if ($this->receiverContact->getPhone() == '') {
            throw new \DhlVendor\Octolize\DhlExpress\RestApi\Exceptions\MissingArgumentException("Missing phone number for receiver");
        }
        if ($this->receiverContact->getPhone() == '') {
            throw new \DhlVendor\Octolize\DhlExpress\RestApi\Exceptions\MissingArgumentException("Missing phone number for shipper");
        }
    }
}
