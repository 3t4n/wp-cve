<?php

declare (strict_types=1);
namespace DhlVendor\Octolize\DhlExpress\RestApi\Services;

use DateTimeImmutable;
use DhlVendor\Octolize\DhlExpress\RestApi\Client;
use DhlVendor\Octolize\DhlExpress\RestApi\Exceptions\InvalidArgumentException;
use DhlVendor\Octolize\DhlExpress\RestApi\Exceptions\MissingArgumentException;
use DhlVendor\Octolize\DhlExpress\RestApi\ResponseParsers\RateResponseParser;
use DhlVendor\Octolize\DhlExpress\RestApi\ValueObjects\Account;
use DhlVendor\Octolize\DhlExpress\RestApi\ValueObjects\MonetaryAmount;
use DhlVendor\Octolize\DhlExpress\RestApi\ValueObjects\Package;
use DhlVendor\Octolize\DhlExpress\RestApi\ValueObjects\Rate;
use DhlVendor\Octolize\DhlExpress\RestApi\ValueObjects\RateAddress;
use DhlVendor\Octolize\DhlExpress\RestApi\ValueObjects\ValueAddedService;
class RateService
{
    /**
     * @var Account[]
     */
    private array $accounts = [];
    /**
     * @var Package[]
     */
    private array $packages = [];
    private \DhlVendor\Octolize\DhlExpress\RestApi\ValueObjects\RateAddress $destinationAddress;
    private \DhlVendor\Octolize\DhlExpress\RestApi\ValueObjects\RateAddress $originAddress;
    private \DateTimeImmutable $shippingDate;
    // predefined defaults
    private bool $isCustomsDeclarable = \false;
    private bool $nextBusinessDay = \true;
    private string $unitOfMeasurement = 'metric';
    private string $payerCountryCode;
    protected ?\DhlVendor\Octolize\DhlExpress\RestApi\ValueObjects\MonetaryAmount $declaredValue;
    protected ?\DhlVendor\Octolize\DhlExpress\RestApi\ValueObjects\MonetaryAmount $insuredValue;
    private array $valueAddedServices = [];
    private array $requiredArguments = ['accounts', 'destinationAddress', 'originAddress', 'packages', 'shippingDate'];
    private array $lastResponse;
    private const RETRIEVE_RATES_ONE_PIECE_URL = 'rates';
    private ?\DhlVendor\Octolize\DhlExpress\RestApi\Client $client;
    public function __construct(?\DhlVendor\Octolize\DhlExpress\RestApi\Client $client = null)
    {
        $this->client = $client;
    }
    public function setClient(\DhlVendor\Octolize\DhlExpress\RestApi\Client $client) : self
    {
        $this->client = $client;
        return $this;
    }
    /**
     * @return Rate[]
     * @throws MissingArgumentException
     * @throws \Octolize\DhlExpress\RestApi\Exceptions\ClientException
     * @throws \Octolize\DhlExpress\RestApi\Exceptions\TotalPriceNotFoundException
     */
    public function getRates() : array
    {
        $this->validateParams();
        $query = $this->prepareQuery();
        $this->lastResponse = $this->client->post(self::RETRIEVE_RATES_ONE_PIECE_URL, $query);
        return (new \DhlVendor\Octolize\DhlExpress\RestApi\ResponseParsers\RateResponseParser($this->lastResponse))->parse();
    }
    public function setPayerCountryCode(string $payerCountryCode) : self
    {
        $this->payerCountryCode = $payerCountryCode;
        return $this;
    }
    public function getLastRawResponse() : array
    {
        return $this->lastResponse;
    }
    public function addAccount(\DhlVendor\Octolize\DhlExpress\RestApi\ValueObjects\Account $account) : self
    {
        $this->accounts[] = $account;
        return $this;
    }
    public function setOriginAddress(\DhlVendor\Octolize\DhlExpress\RestApi\ValueObjects\RateAddress $address) : self
    {
        $this->originAddress = $address;
        return $this;
    }
    public function setUnitOfMeasurement(string $unitOfMeasurement) : self
    {
        $this->unitOfMeasurement = $unitOfMeasurement;
        return $this;
    }
    public function setDestinationAddress(\DhlVendor\Octolize\DhlExpress\RestApi\ValueObjects\RateAddress $address) : self
    {
        $this->destinationAddress = $address;
        return $this;
    }
    public function addPackage(\DhlVendor\Octolize\DhlExpress\RestApi\ValueObjects\Package $package) : self
    {
        $this->packages[] = $package;
        return $this;
    }
    public function setPlannedShippingDate(\DateTimeImmutable $date) : self
    {
        $this->shippingDate = $date;
        return $this;
    }
    public function setCustomsDeclarable(bool $isCustomsDeclarable) : self
    {
        $this->isCustomsDeclarable = $isCustomsDeclarable;
        return $this;
    }
    public function setNextBusinessDay(bool $nextBusinessDay) : self
    {
        $this->nextBusinessDay = $nextBusinessDay;
        return $this;
    }
    public function setDeclaredValue(float $value, string $currency) : self
    {
        $this->declaredValue = new \DhlVendor\Octolize\DhlExpress\RestApi\ValueObjects\MonetaryAmount('declaredValue', $value, $currency);
        return $this;
    }
    public function setInsuredValue(float $value, string $currency) : self
    {
        $this->insuredValue = new \DhlVendor\Octolize\DhlExpress\RestApi\ValueObjects\MonetaryAmount('insuredValue', $value, $currency);
        return $this;
    }
    /**
     * @return void
     * @throws MissingArgumentException
     */
    private function validateParams() : void
    {
        foreach ($this->requiredArguments as $param) {
            if (!isset($this->{$param})) {
                throw new \DhlVendor\Octolize\DhlExpress\RestApi\Exceptions\MissingArgumentException("Missing argument: {$param}");
            }
        }
    }
    public function prepareQuery() : array
    {
        $query = ['customerDetails' => ['shipperDetails' => $this->originAddress->getAsArray(), 'receiverDetails' => $this->destinationAddress->getAsArray()], 'accounts' => $this->prepareAccountsQuery(), 'packages' => $this->preparePackagesQuery(), 'plannedShippingDateAndTime' => $this->shippingDate->format('Y-m-d\\TH:i:sZ'), 'isCustomsDeclarable' => $this->isCustomsDeclarable, 'unitOfMeasurement' => $this->unitOfMeasurement, 'nextBusinessDay' => $this->nextBusinessDay, 'monetaryAmount' => $this->prepareMonetaryAmountQuery(), 'payerCountryCode' => $this->payerCountryCode ?? ''];
        if (\count($this->valueAddedServices)) {
            $query['valueAddedServices'] = [];
            foreach ($this->valueAddedServices as $valueAddedService) {
                $query['valueAddedServices'][] = $valueAddedService->getAsArray();
            }
        }
        return $query;
    }
    private function prepareMonetaryAmountQuery() : array
    {
        $monatary_amount = [];
        if (isset($this->declaredValue)) {
            $monatary_amount[] = $this->declaredValue->getAsArray();
        }
        if (isset($this->insuredValue)) {
            $monatary_amount[] = $this->insuredValue->getAsArray();
        }
        return $monatary_amount;
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
            $package_data = ['weight' => $package->getWeight(), 'dimensions' => ['length' => $package->getLength(), 'width' => $package->getWidth(), 'height' => $package->getHeight()]];
            if ($package->getTypeCode() !== null) {
                $package_data['typeCode'] = $package->getTypeCode();
            }
            $packages[] = $package_data;
        }
        return $packages;
    }
    private function convertBoolToString(bool $value) : string
    {
        return $value ? 'true' : 'false';
    }
    public function getPackages() : array
    {
        return $this->packages;
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
    public function addValueAddedService(\DhlVendor\Octolize\DhlExpress\RestApi\ValueObjects\ValueAddedService $valueAddedService) : self
    {
        $this->valueAddedServices[] = $valueAddedService;
        return $this;
    }
}
